<?php

namespace CmsTool\Support\AccessLog;

use DI\Attribute\Inject;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

/**
 * AccessLoggerFactory that performs log output to a file.
 */
class FileAccessLoggerFactory implements AccessLoggerFactory
{
    public const LoggerName = 'access';

    public const LineFormat = "%message% %context%\n";

    /**
     * constructor
     *
     * @param string $path
     * @param string $filename
     * @param integer $permission
     */
    public function __construct(
        #[Inject('config.support.access_log.file.path')]
        private readonly string $path,
        #[Inject('config.support.access_log.file.filename')]
        private readonly string $filename,
        #[Inject('config.support.access_log.file.permission')]
        private readonly int $permission = 0777,
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function create(): AccessLogger
    {
        $logger = new Logger(self::LoggerName);

        $logger->pushHandler(
            $this->createHandler(),
        );

        return new PsrAccessLoggerProxy($logger);
    }

    /**
     * Create a RotatingFileHandler instance
     *
     * @return RotatingFileHandler
     */
    public function createHandler(): RotatingFileHandler
    {
        $handler = new RotatingFileHandler(
            filename: sprintf(
                '%s/%s',
                $this->path,
                $this->filename,
            ),
            maxFiles: 0,
            filePermission: $this->permission,
        );

        $handler->setFormatter(
            new LineFormatter(self::LineFormat),
        );

        return $handler;
    }
}
