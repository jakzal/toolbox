<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool;

class Tool
{
    private $name;
    private $summary;
    private $website;
    private $command;
    private $testCommand;

    public function __construct(string $name, string $summary, string $website, Command $command, Command $testCommand)
    {
        $this->name = $name;
        $this->summary = $summary;
        $this->website = $website;
        $this->command = $command;
        $this->testCommand = $testCommand;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function summary(): string
    {
        return $this->summary;
    }

    public function website(): string
    {
        return $this->website;
    }

    public function command(): Command
    {
        return $this->command;
    }

    public function testCommand(): Command
    {
        return $this->testCommand;
    }
}
