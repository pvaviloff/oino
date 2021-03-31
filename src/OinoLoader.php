<?php
namespace Oino;

use \Oino\Helpers\CliHelper;
use \Oino\Commands\CommandHandler;
use \Oino\Constants\CommandConst;
use \Oino\Exceptions\PathProjectIncorrectException;
use \Oino\Exceptions\PackageNotInstalledException;
use \Oino\Exceptions\TabNotFoundException;
use \Oino\Exceptions\ExportFileNotFoundException;
use \Oino\Exceptions\InvalidArgumentsException;
use \Oino\Exceptions\UnknownCommandException;
use \Oino\Exceptions\ParseFileException;

class OinoLoader
{
    public static function load(Args $args): int
    {
        return (new static)->main($args);
    }

    public function main(Args $args): int
    {
        try {
            $command = $args->getValue(CommandConst::COMMAND_SHORT_OPTION);
            $commandHandler = new CommandHandler($command);
            $commandHandler->handle($args);
        } catch (
            PathProjectIncorrectException |
            TabNotFoundException |
            ExportFileNotFoundException |
            InvalidArgumentsException |
            ParseFileException |
            UnknownCommandException $e
        ) {
            CliHelper::write($e->getMessage(), CliHelper::ERROR);

            return 1;
        } catch (PackageNotInstalledException $e) {
            CliHelper::write('Please install in your laravel project package https://github.com/laracasts/Laravel-5-Generators-Extended', CliHelper::WARNING);
            CliHelper::write('Installation: composer require --dev laracasts/generators', CliHelper::INFO);

            return 1;
        } catch (\Throwable $t) {
            throw new \RuntimeException(
                $t->getMessage(),
                (int) $t->getCode(),
                $t
            );
        }

        return 0;
    }
}