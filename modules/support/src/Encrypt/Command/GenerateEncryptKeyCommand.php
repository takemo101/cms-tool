<?php

namespace CmsTool\Support\Encrypt\Command;

use CmsTool\Support\Encrypt\EncryptCipher;
use Symfony\Component\Console\Output\OutputInterface;
use Takemo101\Chubby\Console\Command\Command;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Support\ApplicationPath;

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
     * @param LocalFilesystem $filesystem
     * @param ApplicationPath $path
     * @return integer
     */
    public function handle(
        OutputInterface $output,
        EncryptCipher $cipher,
        LocalFilesystem $filesystem,
        ApplicationPath $path,
    ) {
        $key = $cipher->generateKey();

        $base64key = base64_encode($key);

        $this->putAppKey($base64key, $filesystem, $path);

        $output->writeln("<info>key = {$base64key}</info>");

        return self::SUCCESS;
    }

    /**
     * Write App_key to Dotenv
     *
     * @param string $key
     * @param LocalFilesystem $filesystem
     * @param ApplicationPath $path
     * @return void
     */
    public function putAppKey(
        string $key,
        LocalFilesystem $filesystem,
        ApplicationPath $path,
    ): void {
        $dotEnvPath = $path->getBasePath('.env');

        if (!$filesystem->exists($dotEnvPath)) {
            return;
        }

        if ($dotEnv = $filesystem->read($dotEnvPath)) {
            /** @var string|null */
            $replaced = preg_replace('/APP_KEY=.*$/m', "APP_KEY={$key}", $dotEnv);

            if ($replaced) {
                $filesystem->write($dotEnvPath, $replaced);
            }
        }
    }
}
