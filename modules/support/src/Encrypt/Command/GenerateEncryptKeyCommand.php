<?php

namespace CmsTool\Support\Encrypt\Command;

use CmsTool\Support\Dotenv\DotenvContentRepository;
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
     * @param DotenvContentRepository $repository
     * @return integer
     */
    public function handle(
        OutputInterface $output,
        EncryptCipher $cipher,
        DotenvContentRepository $repository,
    ) {
        $key = $cipher->generateKey();

        $base64key = base64_encode($key);

        $this->saveAppKey($base64key, $repository);

        $output->writeln("<info>key = {$base64key}</info>");

        return self::SUCCESS;
    }

    /**
     * Write App_key to Dotenv
     *
     * @param string $key
     * @param DotenvContentRepository $repository
     * @return void
     */
    public function saveAppKey(
        string $key,
        DotenvContentRepository $repository,
    ): void {

        $dotenv = $repository->find();

        if ($dotenv === null) {
            return;
        }

        $replaced = $dotenv->replace('APP_KEY', $key);

        $repository->save($replaced);
    }
}
