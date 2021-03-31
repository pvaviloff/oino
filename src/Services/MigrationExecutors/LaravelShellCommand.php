<?php
namespace Oino\Services\MigrationExecutors;

use \Oino\Args;
use \Oino\Constants\CommandConst;
use \Oino\Helpers\CliHelper;
use \Oino\ValueObjects\VOColumn;
use \Oino\Aggregates\ACreateTable;
use \Oino\Constants\MigrationConst;
use \Oino\Exceptions\PathProjectIncorrectException;
use \Oino\Exceptions\PackageNotInstalledException;
use \Oino\Aggregates\AUpdateTable;

class LaravelShellCommand implements MigrationExecutor
{
    private $scenario;

    private $args;

    public function __construct(int $scenario, Args $args)
    {
        $this->scenario = $scenario;
        $this->args = $args;
    }

    private function cd(string $path): string
    {
        if (!is_dir($path)) {
            throw new PathProjectIncorrectException();
        }

        return "cd $path";
    }

    private function execCommand(string $command): int
    {
        if ($this->args->commandExist(CommandConst::PROJECT_PATH_OPTION)) {
            $cd = $this->cd($this->args->getValue(CommandConst::PROJECT_PATH_OPTION));
            exec("$cd && $command", $outputs, $returnCode);
        } else {
            exec($command, $outputs, $returnCode);
        }

        return $returnCode;
    }

    private function isPackageInstall(): bool
    {
        $returnCode = @$this->execCommand("composer show laracasts/generators");

        if ($returnCode) {
            throw new PackageNotInstalledException();
        }

        return (bool) !$returnCode;
    }

    private function prepareTableSchema(array $columns): string
    {
        $tableSchema = [];
        foreach ($columns as $column) {
            if (! $column instanceof VOColumn) {
                throw new \Exception('Column must be instance of VOColumn');
            }
            $columnSchema = "{$column->getColumnName()}:{$column->getType()}";
            if ($column->isKey()) {
                $columnSchema .= ":primary";
            } elseif ($column->isNullable() && !$column->haveForeignKey()) {
                $columnSchema .= ":nullable";
            }
            if(!empty($column->getDefaultValue())) {
                $columnSchema .= ":default('{$column->getDefaultValue()}')";
            }
            if ($column->haveForeignKey()) {
                $columnSchema .= ":foreign";
            }
            if ($this->scenario == MigrationConst::UPDATE_TABLE) {
                $columnSchema .= ":change";
            }

            $tableSchema[] = $columnSchema;
        }

        return implode(", ", $tableSchema);
    }

    private function makeMigration(string $migrationName, string $tableSchema)
    {
        $returnCode = 0;
        $command = "php artisan make:migration:schema {$migrationName} --schema=\"{$tableSchema}\"";
        $this->execCommand($command);

        if ($returnCode) {
            CliHelper::write("Migration {$migrationName} can`t create", CliHelper::ERROR);
            exit(1);
        }

        return (bool) !$returnCode;
    }

    public function execute(array $tables): void
    {
        $this->isPackageInstall();

        if ($this->scenario === MigrationConst::CREATE_TABLES) {
            foreach ($tables as $table) {
                if (! $table instanceof ACreateTable) {
                    throw new \Exception('Table must be instance of CreateTable for create');
                }
                $tableSchema = $this->prepareTableSchema($table->getColumns());
                $migrationName = "create_{$table->getTable()->getTableName()}_table";
                if ($this->makeMigration($migrationName, $tableSchema)) {
                    CliHelper::write("Migration created: {$migrationName}", CliHelper::SUCCESS);
                }
            }
        }

        if ($this->scenario === MigrationConst::UPDATE_TABLE) {
            foreach ($tables as $table) {
                if (! $table instanceof AUpdateTable) {
                    throw new \Exception('Table must be instance of CreateTable for create');
                }
                $tableSchema = $this->prepareTableSchema($table->getColumns());
                $migrationName = "update_{$table->getTable()->getTableName()}_table";
                if ($this->makeMigration($migrationName, $tableSchema)) {
                    CliHelper::write("Migration created: {$migrationName}", CliHelper::SUCCESS);
                }
            }
        }
    }
}