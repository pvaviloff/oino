<?php
namespace Oino\ValueObjects;

class VOExistTable
{
    private $tableDatabase;

    private $fullTableName;

    private $tableName;

    public function __construct(string $tableDatabase, string $fullTableName, string $tableName)
    {
        $this->tableDatabase = $tableDatabase;
        $this->fullTableName = $fullTableName;
        $this->tableName = $tableName;
    }

    public function getTableDatabase(): string
    {
        return $this->tableDatabase;
    }

    public function getFullTableName(): string
    {
        return $this->fullTableName;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }
}