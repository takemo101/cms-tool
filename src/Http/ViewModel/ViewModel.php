<?php

namespace Takemo101\CmsTool\Http\ViewModel;

use ReflectionClass;
use ReflectionMethod;
use Takemo101\Chubby\Contract\Arrayable;

/**
 * @implements Arrayable<string,mixed>
 */
abstract class ViewModel implements Arrayable
{
    /**
     * @var string The method name to retrieve data in bulk
     */
    public const DataMethod = '__data';

    /**
     * @var string[] Method excluding data
     */
    protected const IgnoreMethods = [
        //
    ];

    /**
     * Get the instance as an array.
     *
     * @return mixed[]
     */
    public function toArray(): array
    {
        // Method excluding data
        $ignoreMethods = [
            '__construct',
            static::DataMethod,
            'toArray',
            ...static::IgnoreMethods,
        ];

        /** @var string[] */
        $methods = array_filter(
            // Get the array of the Public method name in the reflection
            array_map(
                function (ReflectionMethod $reflection) use ($ignoreMethods) {
                    $name = $reflection->getName();

                    return in_array($name, $ignoreMethods)
                        ? null
                        : $name;
                },
                (new ReflectionClass($this))
                    ->getMethods(ReflectionMethod::IS_PUBLIC),
            ),
        );

        return [
            ...get_object_vars($this),
            ...collect($methods)
                ->mapWithKeys(
                    fn (string $method) => [
                        $method => container()->call([$this, $method]),
                    ]
                ),
            ...(
                method_exists($this, static::DataMethod)
                ? (array) container()->call([$this, static::DataMethod])
                : []
            ),
        ];
    }
}
