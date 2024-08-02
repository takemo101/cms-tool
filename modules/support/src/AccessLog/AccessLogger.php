<?php

namespace CmsTool\Support\AccessLog;

interface AccessLogger
{
    /**
     * Write access log
     *
     * @param AccessLogEntry $entry
     * @return void
     */
    public function write(AccessLogEntry $entry): void;
}
