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


class GitLocalCheckCommand extends Command
{
    public const DEFAULT_ACTION = "status";
    public const REASON_NOT_ON_MASTER = 1;
    public const REASON_FILES_MODIFIED = 2;
    public const REASON_FILES_ADDED = 3;
    public const REASON_FILES_DELETED = 4;
    public const REASON_FILES_UNTRACKED = 5;

    protected static $defaultName = 'faby:git-checker';

    private string $path;

    private string $action;

    private array $projectWithLocalChanges;
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
            ->setDescription('Git Checker: check your local git projects statuses')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command help you to check your git projects')
            ->addArgument('path', InputArgument::REQUIRED, 'Path where your git projects are.')
            ->addOption('action', null, InputOption::VALUE_OPTIONAL, 'Check type: status report, local, untracked-files.', static::DEFAULT_ACTION);

        parent::configure();

        $this->commandStartTimestamp = time();
    }

    private function initOptionsAndArguments(InputInterface $input): void
    {
        $this->path = (string)$input->getArgument("path");
        $this->action = (string)$input->getOption("action");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->initOptionsAndArguments($input);
        $this->initOutput($output);
        // Demo mode: preset
//        if ($this->showPresets) {
//            $this->displayAvailablePresets($output);
//
//            return Command::SUCCESS;
//        }
//        // Demo mode: colors
//        if ($this->showColors) {
//            $this->displayAvailableColors($output);
//
//            return Command::SUCCESS;
//        }
//        // Display message
//        $this->displayMessage($output);

        $this->executeStatusReport();


        $this->outputDisplayer->display(
            sprintf("Command ended in %ss", time() - $this->commandStartTimestamp)
        );

        return Command::SUCCESS;
    }


    private function executeStatusReport(): void
    {
        foreach (GitDirectoryParser::parseFoldersToGetGitRepositories($this->path) as $folder) {
            $this->checkModifiedFiles($folder);
            $this->checkUntrackedFiles($folder);
            $this->checkNewFiles($folder);
            $this->checkRemovedFiles($folder);
            $this->checkProjectOnCustomBranches($folder);
        }

        $this->displayReport();
    }


    private function addProjectToNonConformList(string $folder, int $reason): void
    {
        $this->projectWithLocalChanges[$folder][] = $reason;
    }

    private function checkProjectOnCustomBranches(string $folder): void
    {
        if (!GitShell::isMasterBranch($folder)) {
            $this->addProjectToNonConformList($folder, self::REASON_NOT_ON_MASTER);
        }
    }

    private function checkModifiedFiles(string $folder): void
    {
        if (GitShell::hasModifiedFiles($folder)) {
            $this->addProjectToNonConformList($folder, self::REASON_FILES_MODIFIED);
        }
    }

    private function checkUntrackedFiles($folder): void
    {
        if (GitShell::hasUntrackedFiles($folder)) {
            $this->addProjectToNonConformList($folder, self::REASON_FILES_UNTRACKED);
        }
    }

    private function checkNewFiles($folder): void
    {
        if (GitShell::hasAddedFiles($folder)) {
            $this->addProjectToNonConformList($folder, self::REASON_FILES_ADDED);
        }
    }

    private function checkRemovedFiles($folder): void
    {
        if (GitShell::hasDeletedFiles($folder)) {
            $this->addProjectToNonConformList($folder, self::REASON_FILES_DELETED);
        }
    }

    private function displayReport(): void
    {
        foreach ($this->projectWithLocalChanges as $project => $reasons) {
            $this->outputDisplayer->display(sprintf("Non-conform project: %s", $project));
            foreach ($reasons as $reason) {
                switch($reason) {
                    case static::REASON_NOT_ON_MASTER:
                        $this->outputDisplayer->display("---- Branch not on Master");
                        break;
                    case static::REASON_FILES_ADDED:
                        $this->outputDisplayer->display("---- Uncommited added files");
                        break;
                    case static::REASON_FILES_DELETED:
                        $this->outputDisplayer->display("---- Uncommited deleted files");
                        break;
                    case static::REASON_FILES_MODIFIED:
                        $this->outputDisplayer->display("---- Uncommited modified files");
                        break;
                    case static::REASON_FILES_UNTRACKED:
                        $this->outputDisplayer->display("---- Uncommited untracked files");
                        break;
                }
            }
        }
    }

    private function initOutput(OutputInterface $output)
    {
        $this->outputDisplayer = new MessageOutput($output);
    }
}
