<?php

namespace Faby\GitChecker\Output;


use Symfony\Component\Console\Output\OutputInterface;

class MessageOutput
{
    protected OutputInterface $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function display(string $message, array $options = []): void
    {
        $this->output->writeln($message);
    }
}
