<?php

namespace CmsTool\View;

use Stringable;

use InvalidArgumentException;
use RuntimeException;

readonly class ViewName implements Stringable
{
    /**
     * @var string
     */
    public const NamespaceSeparator = '::';

    /**
     * @var string
     */
    public const DirectorySeparator = '.';

    /**
     * constructor
     *
     * @param string $name
     * @param string|null $namespace
     */
    public function __construct(
        private readonly string $name,
        private readonly ?string $namespace = null,
    ) {
        //
    }

    /**
     * Get the path to the template file.
     *
     * @return string
     */
    public function getPath(): string
    {
        return str_replace(self::DirectorySeparator, DIRECTORY_SEPARATOR, $this->name);
    }

    /**
     * Get the name of the view.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the namespace of the view name.
     *
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace ?? throw new RuntimeException(
            'View name does not have a namespace.',
        );
    }

    /**
     * @return bool
     */
    public function hasNamespace(): bool
    {
        return !empty($this->namespace);
    }

    /**
     * Get the name to the template file.
     *
     * @return string
     */
    public function getOriginal(): string
    {
        return $this->namespace . self::NamespaceSeparator . $this->name;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getOriginal();
    }

    /**
     * Create a new view name instance from the given string.
     *
     * @param string $original
     * @return self
     */
    public static function fromOriginal(string $original): self
    {
        if (!str_contains($original, self::NamespaceSeparator)) {
            return new self(
                name: (string) str_replace(DIRECTORY_SEPARATOR, self::DirectorySeparator, $original),
                namespace: null,
            );
        }

        $segments = explode(self::NamespaceSeparator, $original);

        if (count($segments) !== 2) {
            throw new InvalidArgumentException(
                sprintf('View name "%s" is invalid.', $original),
            );
        }

        $namespace = $segments[0];
        $name = $segments[1];

        return new self(
            name: (string) str_replace(DIRECTORY_SEPARATOR, self::DirectorySeparator, $name),
            namespace: $namespace,
        );
    }
}
