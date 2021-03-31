<?php
namespace Oino\ValueObjects;

class VOTable
{
    private $guid;

    private $tableName;

    public function __construct(string $guid, string $tableName)
    {
        $this->guid = $guid;
        $this->tableName = $tableName;
    }

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }
}