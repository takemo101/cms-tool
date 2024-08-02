<?php

namespace CmsTool\Support\AccessLog;

use Takemo101\Chubby\Event\StoppableEvent;

/**
 * Event that is fired when access is logged.
 *
 * @immutable
 */
class AccessLogged extends StoppableEvent
{
    /**
     * constructor
     *
     * @param AccessLogEntry $entry
     */
    public function __construct(
        public readonly AccessLogEntry $entry,
    ) {
        //
    }
}
