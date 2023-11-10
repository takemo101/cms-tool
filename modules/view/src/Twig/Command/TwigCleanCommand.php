<?php

namespace CmsTool\View\Twig\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Takemo101\Chubby\Console\Command\Command;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Twig\Environment;

final class TwigCleanCommand extends Command
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('twig:clean')
            ->setDescription('Clears the Twig cache');
    }

    /**
     * Execute command process.
     *
     * @param OutputInterface $output
     * @param Environment $twig
     * @return integer
     */
    public function handle(
        OutputInterface $output,
        Environment $twig,
        LocalFilesystem $filesystem
    ) {
        $cache = $twig->getCache();

        $filesystem->deleteDirectory($cache);

        if ($filesystem->exists($cache)) {
            $output->writeln('<error>Failed to clear Twig cache.</error>');

            return self::FAILURE;
        }

        $output->writeln('<info>Twig cache cleared.</info>');
        return self::SUCCESS;
    }
}
