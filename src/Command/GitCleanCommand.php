<?php

namespace Faby\GitChecker\Command;

use Faby\GitChecker\FileParser\GitDirectoryParser;
use Faby\GitChecker\Git\GitShell;
use Faby\GitChecker\Output\MessageOutput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;


class GitCleanCommand extends Command
{
    protected static $defaultName = 'faby:git-clean';
    private float $commandStartTimestamp;
    private MessageOutput $outputDisplayer;

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Git clean: remove untracked and ignored files and folders from your local git repository.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command help you to clean your git projects');

        parent::configure();

        $this->commandStartTimestamp = time();
    }

    private function initOutput(OutputInterface $output): void
    {
        $this->outputDisplayer = new MessageOutput($output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $command = $this->getApplication()->find('faby:greet');

        $arguments = [
            'name'    => 'Fabien',
            '--yell'  => true,
        ];

        $greetInput = new ArrayInput($arguments);
        $returnCode = $command->run($greetInput, $output);


        $this->initOutput($output);
        $this->cleanUntrackedFiles();
        $this->cleanIgnoredFiles();

        $this->outputDisplayer->display(
            sprintf("Command ended in %ss", time() - $this->commandStartTimestamp)
        );

        return Command::SUCCESS;
    }

    private function cleanUntrackedFiles(): void
    {
        $this->outputDisplayer->display('Clean untracked files');
        $this->outputDisplayer->display(
            GitShell::executeGitCleanUntrackedFiles()
        );
    }

    private function cleanIgnoredFiles()
    {
        $this->outputDisplayer->display('Clean ignored files');
        $this->outputDisplayer->display(
            GitShell::executeGitCleanIgnoredFiles()
        );
    }
}
