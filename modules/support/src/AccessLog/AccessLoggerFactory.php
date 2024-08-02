<?php

namespace CmsTool\Support\AccessLog;

interface AccessLoggerFactory
{
    /**
     * Create an AccessLogger instance
     *
     * @return AccessLogger
     */
    public function create(): AccessLogger;
}
