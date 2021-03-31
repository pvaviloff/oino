<?php
namespace Oino\Services;

use \Oino\Aggregates\ACreateTable;
use \Oino\Aggregates\AUpdateTable;
use \Oino\Helpers\StringHelper;
use \Oino\ValueObjects\VOExistTable;
use \Oino\ValueObjects\VOTable;
use \Oino\ValueObjects\VOColumn;

class TablesHandler
{
    private $tables;

    private $tablesFromDatabase;

    private $tablesForCreate = [];

    private $tablesForUpdate = [];

    public function __construct(array $tables, array $tablesFromDatabase)
    {
        $this->tables = $tables;
        $this->tablesFromDatabase = $tablesFromDatabase;
    }

    public function handle(): void
    {
        foreach ($this->tables as $table) {
            if (empty($table->tableName)) {
                continue;
            }
            $existTable = null;
            foreach ($this->tablesFromDatabase as $tableFromDatabase) {
                if (!$tableFromDatabase instanceof VOExistTable) {
                    throw new \Exception("Table from database must be instance of VOExistTable");
                }

                if (StringHelper::equals($table->tableName, $tableFromDatabase->getTableName())) {
                    $existTable = $tableFromDatabase;
                }
            }

            $tableObject = new VOTable($table->guid, StringHelper::clean($table->tableName));
            $columnObjects = [];
            foreach ($table->rows as $column) {
                if (empty($column->columnName)) {
                    continue;
                }
                $columnObjects[] = new VOColumn(
                    $column->guid,
                    $column->isKey,
                    StringHelper::clean($column->columnName),
                    $column->type,
                    $column->typeSize,
                    $column->defaultValue,
                    $column->comment,
                    $column->foreignKeyToGuid
                );
            }

            if (empty($columnObjects)) {
                continue;
            }

            if ($existTable instanceof VOExistTable) {
                $this->tablesForUpdate[] = new AUpdateTable($tableObject, $columnObjects, $existTable);
            } else {
                $this->tablesForCreate[] = new ACreateTable($tableObject, $columnObjects);
            }
        }
    }

    public function getTablesForCreate(): array
    {
        return $this->tablesForCreate;
    }

    public function getTablesForUpdate(): array
    {
        return $this->tablesForUpdate;
    }
}