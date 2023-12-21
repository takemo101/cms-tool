<?php

namespace CmsTool\Theme\Routing;

use RuntimeException;

class ThemeRoutePresets
{
    /**
     * @var array<string,ThemeRoute|class-string<ThemeRoute>>
     */
    private array $presets = [];

    /**
     * @param array<string,ThemeRoute|class-string<ThemeRoute>> $presets
     */
    public function __construct(array $presets)
    {
        foreach ($presets as $name => $class) {
            $this->add($name, $class);
        }
    }

    /**
     * Add route preset
     *
     * @param string $name
     * @param ThemeRoute|class-string<ThemeRoute> $class
     * @return self
     * @throws RuntimeException
     */
    public function add(string $name, ThemeRoute|string $class): self
    {
        if (is_string($class)) {
            if (class_exists($class) === false) {
                throw new RuntimeException("Class {$class} not found.");
            }

            if (is_subclass_of($class, ThemeRoute::class) === false) {
                throw new RuntimeException("Class {$class} is not a subclass of " . ThemeRoute::class);
            }
        }

        $this->presets[$name] = $class;

        return $this;
    }

    /**
     * Find route preset
     *
     * @param string $name
     * @return ThemeRoute|class-string<ThemeRoute>
     * @throws NotFoundThemePresetException
     */
    public function find(string $name): ThemeRoute|string
    {
        $class = $this->presets[$name] ?? null;

        if ($class === null) {
            throw new NotFoundThemePresetException($name);
        }

        return $class;
    }

    /**
     * Get all route presets
     *
     * @return array<string,class-string<ThemeRoute>>
     */
    public function presets(): array
    {
        return $this->presets;
    }
}
