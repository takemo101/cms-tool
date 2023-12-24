<?php

namespace CmsTool\View\Accessor;

use DI\Attribute\Inject;

/**
 * Register the accessor to the key and get a value from the accessor
 */
class DataAccessorsFactory
{
    /**
     * constructor
     *
     * @param DataAccessInvoker $invoker
     * @param array<string,class-string<object&callable>> $accessors
     */
    public function __construct(
        private DataAccessInvoker $invoker,
        #[Inject('config.view.accessors')]
        private array $accessors = [],
    ) {
        //
    }

    /**
     * Create a new DataAccessors instance
     *
     * @return DataAccessors
     */
    public function create(): DataAccessors
    {
        $accessors = new DataAccessors($this->invoker);

        foreach ($this->accessors as $key => $accessor) {
            $accessors->add($key, $accessor);
        }

        return $accessors;
    }
}
