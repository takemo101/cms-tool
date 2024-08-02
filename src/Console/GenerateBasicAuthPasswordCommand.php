<?php

namespace Takemo101\CmsTool\Console;

use CmsTool\Support\Hash\Hasher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Takemo101\Chubby\Console\Command\Command;
use Takemo101\Chubby\Filesystem\LocalFilesystem;
use Takemo101\Chubby\Support\ApplicationPath;

#[AsCommand(
    name: 'generate:basic-auth-pass',
    description: "Generate basic auth password",
)]
class GenerateBasicAuthPasswordCommand extends Command
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->addArgument(
            name: 'password',
            description: 'Plain password',
        );
    }

    /**
     * Execute command process.
     *
     * @param OutputInterface $output
     * @param InputInterface $input
     * @param Hasher $hasher
     * @param LocalFilesystem $filesystem
     * @param ApplicationPath $path
     * @return integer
     */
    public function handle(
        OutputInterface $output,
        InputInterface $input,
        Hasher $hasher,
        LocalFilesystem $filesystem,
        ApplicationPath $path,
    ) {
        /** @var string */
        $plainPassword = $input->getArgument('password');

        if (empty($plainPassword)) {
            $output->writeln('<error>password is required</error>');
            return self::FAILURE;
        }

        $hashedPassword = $hasher->hash($plainPassword);

        $this->putBasicAuthPassword(
            password: $hashedPassword,
            filesystem: $filesystem,
            path: $path,
        );

        $output->writeln("<info>password = {$hashedPassword}</info>");

        return self::SUCCESS;
    }

    /**
     * Write BASIC_AUTH_PASSWORD to .env file.
     *
     * @param string $password
     * @param LocalFilesystem $filesystem
     * @param ApplicationPath $path
     * @return void
     */
    public function putBasicAuthPassword(
        string $password,
        LocalFilesystem $filesystem,
        ApplicationPath $path,
    ): void {
        $dotEnvPath = $path->getBasePath('.env');

        if (!$filesystem->exists($dotEnvPath)) {
            return;
        }

        if ($dotEnv = $filesystem->read($dotEnvPath)) {

            // Generate a random string and create a replacement key.
            $random = bin2hex(random_bytes(8));
            $replaceKey = "### ----{$random}---- ###";

            // Replace $ with \$ to avoid error
            $escapedPassword = str_replace('$', '\$', $password);

            /** @var string|null */
            $replaced = preg_replace('/BASIC_AUTH_PASSWORD=.*$/m', $replaceKey, $dotEnv);

            $replaced = str_replace($replaceKey, "BASIC_AUTH_PASSWORD=\"{$escapedPassword}\"", $replaced);

            if ($replaced) {
                $filesystem->write($dotEnvPath, $replaced);
            }
        }
    }
}
