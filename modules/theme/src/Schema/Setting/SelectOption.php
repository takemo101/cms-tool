<?php

namespace CmsTool\Theme\Schema\Setting;

readonly class SelectOption
{
    /**
     * constructor
     *
     * @param string $value
     * @param string $label
     */
    public function __construct(
        public string $value,
        public string $label,
    ) {
        assert(
            empty($label) === false,
            'The option label must not be empty',
        );
    }
}
