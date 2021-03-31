<?php
namespace Oino\Services\DBParser;

use Doctrine\DBAL\DriverManager;
use Oino\ValueObjects\VOExistTable;
use \Oino\ValueObjects\VOExistColumn;
use \Doctrine\DBAL\Schema\AbstractSchemaManager;
use \Oino\ValueObjects\VOExistForeignKey;

class SchemaDBParser implements IDBParser
{
    private $settings;

    private $excludePostgresTables = ['template0', 'template1'];

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    private function getSchemaManager(string $database = null): AbstractSchemaManager
    {
        $settings = $this->settings;
        if ($database !== null) {
            $settings['dbname'] = $database;
        }
        $connection = DriverManager::getConnection($settings);

        return $connection->getSchemaManager();
    }

    public function getDatabases(): array
    {
        $excludeTables = [];
        if ($this->settings['driver'] == 'pdo_pgsql') {
            $excludeTables = $this->excludePostgresTables;
        }

        return array_diff($this->getSchemaManager()->listDatabases(), $excludeTables);
    }

    public function getTablesByDatabase(string $database): array
    {
        $schemaManager = $this->getSchemaManager($database);
        $tableObjects = [];
        foreach ($schemaManager->listTables() as $table) {
            $tableObjects[] = new VOExistTable(
                $database,
                "{$database}.{$table->getName()}",
                $table->getName()
            );
        }

        return $tableObjects;
    }

    public function getTables(): array
    {
        $databases = $this->getDatabases();
        $tables = [];
        foreach ($databases as $database) {
            $tables = array_merge($tables, $this->getTablesByDatabase($database));
        }

        return $tables;
    }

    public function getColumnsByTable(string $database, string $tableName): array
    {
        $schemaManager = $this->getSchemaManager($database);
        $columns = [];
        $indexes = $schemaManager->listTableIndexes($tableName);
        foreach ($schemaManager->listTableColumns($tableName) as $column) {
            $isUnique = false;
            $isPrimary = false;
            foreach ($indexes as $index) {
                if (in_array($column->getName(), $index->getColumns())) {
                    $isUnique = $index->isUnique();
                    $isPrimary = $index->isPrimary();
                }
            }

            $columns[] = new VOExistColumn(
                $column->getName(),
                $column->getType()->getName(),
                $column->getAutoincrement(),
                $column->getColumnDefinition() ?? '',
                $column->getComment() ?? '',
                $column->getDefault() ?? '',
                $column->getFixed(),
                $column->getLength() ?? 0,
                $column->getNotnull(),
                $column->getPrecision(),
                $column->getScale(),
                $column->getUnsigned(),
                $isPrimary,
                $isUnique
            );
        }

        return $columns;
    }

    public function getForeignKeys(string $database, string $tableName): array
    {
        $foreignKeys = $this->getSchemaManager($database)->listTableForeignKeys($tableName);
        $foreignKeyObjects = [];
        foreach ($foreignKeys as $foreignKey) {
            $foreignKeyObjects[] = new VOExistForeignKey(
                $tableName,
                $foreignKey->getColumns(),
                $foreignKey->getForeignTableName(),
                $foreignKey->getForeignColumns()
            );
        }

        return $foreignKeyObjects;
    }
}