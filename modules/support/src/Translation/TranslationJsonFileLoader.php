<?php

namespace CmsTool\Support\Translation;

use CmsTool\Support\JsonAccess\Exception\JsonAccessException;
use CmsTool\Support\JsonAccess\JsonArrayLoader;
use CmsTool\Support\Translation\Exception\NotFoundTranslationException;
use CmsTool\Support\Translation\Exception\TranslationDecodeErrorException;
use CmsTool\Support\Translation\Exception\TranslationResourceException;
use DI\Attribute\Inject;
use Takemo101\Chubby\Filesystem\PathHelper;

/**
 * @implements TranslationLoader<string>
 */
class TranslationJsonFileLoader implements TranslationLoader
{
    /**
     * @var array<string,mixed>
     */
    private array $cache = [];

    /**
     * constructor
     *
     * @param PathHelper $pathHelper
     * @param JsonArrayLoader $loader
     * @param string[] $locations
     */
    public function __construct(
        private PathHelper $pathHelper,
        private JsonArrayLoader $loader,
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

            if ($this->loader->exists($path)) {
                try {
                    $data = $this->loader->load($path);
                } catch (JsonAccessException $e) {
                    throw new TranslationDecodeErrorException(
                        domain: $domain,
                        locale: $locale,
                        message: $e->getMessage(),
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
                || $this->loader->exists($path)
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
