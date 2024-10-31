<?php

namespace Takemo101\CmsTool\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Takemo101\Chubby\Console\Command\Command;
use Takemo101\CmsTool\Infra\Cache\ApiMemoCache;

#[AsCommand(
    name: 'api-cache:clean',
    description: "Clears the data cache",
)]
class ApiCacheCleanCommand extends Command
{
    /**
     * Execute command process.
     *
     * @param OutputInterface $output
     * @param ApiMemoCache $memo
     * @return integer
     */
    public function handle(
        OutputInterface $output,
        ApiMemoCache $memo,
    ) {
        if ($memo->clear()) {
            $output->writeln('<info>Cache cleared successfully.</info>');

            return self::SUCCESS;
        }

        $output->writeln('<error>Failed to clear cache.</error>');

        return self::FAILURE;
    }
}
