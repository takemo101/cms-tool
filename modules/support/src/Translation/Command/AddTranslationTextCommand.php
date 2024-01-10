<?php

namespace CmsTool\Support\Translation\Command;

use CmsTool\Support\Translation\TranslationAccessor;
use CmsTool\Support\Translation\Translator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Takemo101\Chubby\Console\Command\Command;

#[AsCommand(
    name: 'trans:add',
    description: 'Add translation text',
)]
class AddTranslationTextCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->addArgument(
            name: 'locale',
            mode: InputArgument::OPTIONAL,
            description: 'Locale to be translated',
        );
    }

    /**
     * Execute command process.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return integer
     */
    public function handle(
        InputInterface $input,
        OutputInterface $output,
        Translator $translator,
        TranslationAccessor $accessor,
    ) {
        /** @var string */
        $locale = $input->getArgument('locale') ?? $translator->getLocale();

        /** @var QuestionHelper */
        $helper = $this->getHelper('question');

        $domain = $this->questionForDomain($input, $output, $helper);

        if (!$accessor->exists($domain, $locale)) {

            $output->writeln('<error>Translation file does not exist.</error>');

            return self::FAILURE;
        }

        $key = $this->questionForKey($input, $output, $helper);
        $text = $this->questionForText($input, $output, $helper);

        $data = $accessor->load($domain, $locale);

        $data[$key] = $text;

        $accessor->save($domain, $locale, $data);

        $output->writeln('<info>Translation text added.</info>');

        return self::SUCCESS;
    }

    /**
     * Ask for translation domain.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param QuestionHelper $helper
     * @return string
     */
    private function questionForDomain(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $helper,
    ): string {
        $question = new Question("Please enter the translation domain \n", false);

        do {
            /** @var string|false */
            $domain = $helper->ask($input, $output, $question);
        } while (!$domain);

        return $domain;
    }

    /**
     * Ask for translation key.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param QuestionHelper $helper
     * @return string
     */
    private function questionForKey(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $helper,
    ): string {
        $question = new Question("Please enter the translation key \n", false);

        do {
            /** @var string|false */
            $key = $helper->ask($input, $output, $question);
        } while (!$key);

        return $key;
    }

    /**
     * Ask for translation text.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param QuestionHelper $helper
     * @return string
     */
    private function questionForText(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $helper,
    ): string {
        $question = new Question("Please enter the translation text \n", false);

        do {
            /** @var string|false */
            $text = $helper->ask($input, $output, $question);
        } while (!$text);

        return $text;
    }
}
