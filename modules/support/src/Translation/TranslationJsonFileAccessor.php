<?php

namespace CmsTool\Support\Translation;

use CmsTool\Support\JsonAccess\Exception\JsonConversionException;
use CmsTool\Support\JsonAccess\JsonArrayAccessor;
use CmsTool\Support\Translation\Exception\NotFoundTranslationException;
use CmsTool\Support\Translation\Exception\TranslationConversionException;
use CmsTool\Support\Translation\Exception\TranslationResourceException;
use DI\Attribute\Inject;
use Takemo101\Chubby\Filesystem\PathHelper;

/**
 * @implements TranslationAccessor<string>
 */
class TranslationJsonFileAccessor implements TranslationAccessor
{
    /**
     * @var array<string,array<string,mixed>>
     */
    private array $cache = [];

    /**
     * constructor
     *
     * @param PathHelper $pathHelper
     * @param JsonArrayAccessor $accessor
     * @param string[] $locations
     */
    public function __construct(
        private PathHelper $pathHelper,
        private JsonArrayAccessor $accessor,
        #[Inject('config.support.translation.file.locations')]
        private array $locations = [],
    ) {
        //
    }

    /**
     * Load the translation and return the data in array format
     *
     * {@inheritdoc}
     */
    public function load(string $domain, string $locale = Translator::DefaultLocale): array
    {
        foreach ($this->locations as $location) {
            $path = $this->getJsonPath(
                location: $location,
                domain: $domain,
                locale: $locale,
            );

            if (isset($this->cache[$path])) {
                return $this->cache[$path];
            }

            if ($this->accessor->exists($path)) {
                try {
                    $data = $this->accessor->load($path);
                } catch (JsonConversionException $e) {
                    throw TranslationConversionException::decodeError(
                        domain: $domain,
                        locale: $locale,
                        previous: $e,
                    );
                }

                $this->cache[$path] = $data;

                return $data;
            }
        }

        throw NotFoundTranslationException::notFoundError(
            domain: $domain,
            locale: $locale,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function exists(string $domain, string $locale = Translator::DefaultLocale): bool
    {
        foreach ($this->locations as $location) {
            $path = $this->getJsonPath(
                location: $location,
                domain: $domain,
                locale: $locale,
            );

            if (
                isset($this->cache[$path])
                || $this->accessor->exists($path)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add the resource of the translation
     *
     * @param string $resource
     * @return void
     * @throws TranslationResourceException
     */
    public function addResource($resource): void
    {
        if (!is_string($resource)) {
            throw TranslationResourceException::invalidResourceType(
                type: 'string',
            );
        }

        $this->locations[] = $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        string $domain,
        string $locale = Translator::DefaultLocale,
        array $data = [],
    ): void {
        foreach ($this->locations as $location) {
            $path = $this->getJsonPath(
                location: $location,
                domain: $domain,
                locale: $locale,
            );
            try {
                $this->accessor->save(
                    path: $path,
                    data: $data,
                    flags: JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
                );
            } catch (JsonConversionException $e) {
                throw TranslationConversionException::encodeError(
                    domain: $domain,
                    locale: $locale,
                    previous: $e,
                );
            }
        }
    }

    /**
     * Get the locations
     *
     * @return string[]
     */
    public function getLocations(): array
    {
        return $this->locations;
    }

    /**
     * Get the json file path
     *
     * @param string $location
     * @param string $domain
     * @param string $locale
     * @return string
     */
    private function getJsonPath(string $location, string $domain, string $locale): string
    {
        return $this->pathHelper->join(
            $location,
            "{$domain}.{$locale}.json",
        );
    }
}
