<?php

namespace Takemo101\CmsTool\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Takemo101\Chubby\Console\Command\Command;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\CmsTool\Infra\Storage\LocalPublicStoragePath;

class StorageLinkCommand extends Command
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('storage:link')
            ->setDescription("Create a symbolic link from 'storage.public.link_path' to 'storage.public.storage_path'")
            ->addOption(
                name: 'clear',
                mode: InputOption::VALUE_NONE,
                description: 'Delete the existing link and create a new one.',
            );
    }

    /**
     * Execute command process.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param LocalPublicStoragePath $path
     * @param LocalFilesystem $filesystem
     * @return integer
     */
    public function handle(
        InputInterface $input,
        OutputInterface $output,
        LocalPublicStoragePath $path,
        LocalFilesystem $filesystem,
    ) {
        if ($input->getOption('clear')) {
            return $this->deleteSymlink($output, $path, $filesystem);
        }

        return $this->createSymlink($output, $path, $filesystem);
    }

    /**
     * Delete the existing link.
     *
     * @param OutputInterface $output
     * @param LocalPublicStoragePath $path
     * @param LocalFilesystem $filesystem
     * @return integer
     */
    private function deleteSymlink(
        OutputInterface $output,
        LocalPublicStoragePath $path,
        LocalFilesystem $filesystem,
    ): int {
        if (!$filesystem->exists($path->getLinkPath())) {
            $output->writeln("<error>The \"{$path->getLinkPath()}\" link does not exist.</error>");

            return self::FAILURE;
        }

        $filesystem->delete($path->getLinkPath());

        $output->writeln("<info>The \"{$path->getLinkPath()}\" link has been deleted.</info>");

        return self::SUCCESS;
    }

    /**
     * Create a symbolic link from 'storage.public.link_path' to 'storage.public.storage_path'
     *
     * @param OutputInterface $output
     * @param LocalPublicStoragePath $path
     * @param LocalFilesystem $filesystem
     * @return integer
     */
    private function createSymlink(
        OutputInterface $output,
        LocalPublicStoragePath $path,
        LocalFilesystem $filesystem,
    ): int {
        if ($filesystem->exists($path->getLinkPath())) {
            $output->writeln("<error>The \"{$path->getLinkPath()}\" link already exists.</error>");

            return self::FAILURE;
        }

        $filesystem->symlink($path->getStoragePath(), $path->getLinkPath());

        $output->writeln("<info>The \"{$path->getLinkPath()}\" link has been connected to \"{$path->getStoragePath()}\".</info>");

        return self::SUCCESS;
    }
}
