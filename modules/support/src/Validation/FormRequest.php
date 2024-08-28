<?php

namespace CmsTool\Support\Validation;

use Takemo101\Chubby\Contract\Arrayable;

/**
 * @template T of object
 *
 * @implements Arrayable<string,mixed>
 */
interface FormRequest extends RequestInputs, ValidatedCondition, Arrayable
{
    /**
     * Get the hydrated object.
     *
     * @return T
     */
    public function getHydratedObject(): object;
}
