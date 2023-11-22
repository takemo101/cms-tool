<?php

namespace CmsTool\Support\Encrypt\Command;

use CmsTool\Support\Encrypt\EncryptCipher;
use Symfony\Component\Console\Output\OutputInterface;
use Takemo101\Chubby\Console\Command\Command;

class GenerateEncryptKeyCommand extends Command
{
    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('generate:key')
            ->setDescription('Generate encrypt key');
    }

    /**
     * Execute command process.
     *
     * @param OutputInterface $output
     * @param EncryptCipher $cipher
     * @return integer
     */
    public function handle(
        OutputInterface $output,
        EncryptCipher $cipher,
    ) {
        $key = $cipher->generateKey();

        $base64key = base64_encode($key);

        $output->writeln("<info>key = {$base64key}</info>");

        return self::SUCCESS;
    }
}
