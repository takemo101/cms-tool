<?php

namespace Takemo101\CmsTool\Console;

use CmsTool\Support\Dotenv\DotenvContentRepository;
use CmsTool\Support\Hash\Hasher;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Takemo101\Chubby\Console\Command\Command;

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
     * @param DotenvContentRepository $repository
     * @return integer
     */
    public function handle(
        OutputInterface $output,
        InputInterface $input,
        Hasher $hasher,
        DotenvContentRepository $repository,
    ) {
        /** @var string */
        $plainPassword = $input->getArgument('password');

        if (empty($plainPassword)) {
            $output->writeln('<error>password is required</error>');
            return self::FAILURE;
        }

        $hashedPassword = $hasher->hash($plainPassword);

        $this->saveBasicAuthPassword(
            password: $hashedPassword,
            repository: $repository,
        );

        $output->writeln("<info>password = {$hashedPassword}</info>");

        return self::SUCCESS;
    }

    /**
     * Write BASIC_AUTH_PASSWORD to .env file.
     *
     * @param string $password
     * @param DotenvContentRepository $repository
     * @return void
     */
    public function saveBasicAuthPassword(
        string $password,
        DotenvContentRepository $repository,
    ): void {
        $dotenv = $repository->find();

        if ($dotenv === false) {
            return;
        }

        $replaced = $dotenv->replace(
            key: 'BASIC_AUTH_PASSWORD',
            value: $password,
        );

        $repository->save($replaced);
    }
}
