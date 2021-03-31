<?php
namespace Oino\Services;

use \Oino\Services\DBFormatter\JsonExecutor;
use \Oino\Aggregates\AExistTable;
use \Oino\Aggregates\ADatabase;
use \Oino\ValueObjects\VOExistTable;

class DBFormatter
{
    private $databaseAggregates = [];

    private $dbParser;

    public function __construct()
    {
        $this->dbParser = new DBParser();

        $databases = [];
        foreach ($this->dbParser->getTables() as $table) {
            $databases[$table->getTableDatabase()][] = $table;
        }
        foreach ($databases as $databaseName => $tables) {
            $tableAggregate = [];
            $foreignKeys = [];
            foreach ($tables as $table) {
                if (! $table instanceof VOExistTable) {
                    throw new \Exception("Table must be instance VOExistTable");
                }
                $foreignKeys = array_merge($this->dbParser->getForeignKeys(
                    $table->getTableDatabase(),
                    $table->getTableName()
                ), $foreignKeys);

                $tableAggregate[] = new AExistTable(
                    $table,
                    $this->dbParser->getColumnsByTable(
                        $table->getTableDatabase(),
                        $table->getTableName()
                    )
                );
            }
            $this->databaseAggregates[] = new ADatabase($databaseName, $tableAggregate, $foreignKeys);
        }
    }

    public function toJson(): string
    {
        $jsonExecutor = new JsonExecutor($this->databaseAggregates);

        return $jsonExecutor->get();
    }
}