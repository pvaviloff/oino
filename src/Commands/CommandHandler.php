<?php
namespace Oino\Commands;

use \Oino\Exceptions\UnknownCommandException;
use \Oino\Exceptions\InvalidArgumentsException;
use Oino\Constants\CommandConst;
use Oino\Validation\ImportValidation;
use \Oino\Validation\ExportValidation;
use \Oino\Validation\IValidation;
use \Oino\Args;

class CommandHandler
{
    private $validation;

    private $command;

    public function __construct(string $command)
    {
        $this->command = $command;
        if ($command == CommandConst::IMPORT) {
            $this->validation = new ImportValidation();
            $this->command = new ImportCommand();
        } elseif ($command == CommandConst::EXPORT) {
            $this->validation = new ExportValidation();
            $this->command = new ExportCommand();
        } else {
            throw new UnknownCommandException();
        }
    }

    public function handle(Args $args)
    {
        if (! $this->command instanceof ICommand) {
            throw new \Exception("Command must implements ICommand interface");
        }

        if ($this->validation instanceof IValidation && !$this->validation->validate($args)) {
            throw new InvalidArgumentsException();
        }

        $this->command->handle($args);
    }
}