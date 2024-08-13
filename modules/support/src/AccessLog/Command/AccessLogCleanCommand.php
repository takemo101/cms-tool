<?php

namespace CmsTool\Support\AccessLog\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Takemo101\Chubby\Config\ConfigRepository;
use Takemo101\Chubby\Console\Command\Command;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Support\ApplicationPath;

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
     * @param ApplicationPath $path
     * @return integer
     */
    public function handle(
        OutputInterface $output,
        ConfigRepository $config,
        LocalFilesystem $filesystem,
        ApplicationPath $path,
    ) {
        /** @var string */
        $directory = $config->get('support.access_log.file.path', $path->getStoragePath('access'));

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
