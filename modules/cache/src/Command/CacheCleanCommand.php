<?php

namespace CmsTool\Cache\Command;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Takemo101\Chubby\Console\Command\Command;

class CacheCleanCommand extends Command
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('cache:clean')
            ->setDescription('Clears the data cache');
    }

    /**
     * Execute command process.
     *
     * @param OutputInterface $output
     * @param CacheItemPoolInterface $cache
     * @return integer
     */
    public function handle(
        OutputInterface $output,
        CacheItemPoolInterface $cache,
    ) {
        if ($cache->clear()) {
            $output->writeln('<info>Cache cleared successfully.</info>');

            return self::SUCCESS;
        }

        $output->writeln('<error>Failed to clear cache.</error>');

        return self::FAILURE;
    }
}
