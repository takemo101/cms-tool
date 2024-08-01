<?php

namespace CmsTool\Support\AccessLog\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Takemo101\Chubby\Config\ConfigRepository;
use Takemo101\Chubby\Console\Command\Command;
use Takemo101\Chubby\Filesystem\LocalFilesystem;

/**
 * Command to delete log files
 */
#[AsCommand(
    name: 'access-log:clean',
    description: 'Delete access log files',
)]
class AccessLogCleanCommand extends Command
{
    /**
     * Execute command process.
     *
     * @param OutputInterface $output
     * @param ConfigRepository $config
     * @param LocalFilesystem $filesystem
     * @return integer
     */
    public function handle(
        OutputInterface $output,
        ConfigRepository $config,
        LocalFilesystem $filesystem,
    ) {
        /** @var string */
        $directory = $config->get('support.access_log.file.path', storage_path('access'));

        if (!$filesystem->exists($directory)) {
            $output->writeln('<info>Access log directory not found.</info>');
            return self::SUCCESS;
        }

        $files = $filesystem->glob($directory . '/*') ?? [];

        foreach ($files as $file) {
            $filesystem->delete($file);
        }

        $output->writeln('<info>Access log files deleted.</info>');
        return self::SUCCESS;
    }
}
