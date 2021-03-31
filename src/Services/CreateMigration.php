<?php
namespace Oino\Services;

use \Oino\Services\MigrationExecutors\LaravelShellCommand;
use \Oino\Settings;
use \Oino\Args;

class CreateMigration
{
    private $driver;

    private $tables;

    private $args;

    public function __construct(array $tables, Args $args)
    {
        $config = Settings::load()->get('migration');
        if (empty($config)) {
            throw new \Exception("Database config is empty");
        }
        $this->args = $args;
        $this->tables = $tables;
        $this->driver = $config;
    }

    public function execute(int $scenario)
    {
        if ($this->driver == 'laravel') {
            $laravelShellCommand = new LaravelShellCommand($scenario, $this->args);
            $laravelShellCommand->execute($this->tables);
            return;
        }

        throw new \Exception('shell-driver in settings file not defined');
    }
}