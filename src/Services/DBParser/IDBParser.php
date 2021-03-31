<?php
namespace Oino\Services\DBParser;

interface IDBParser
{
    public function getDatabases(): array;

    public function getTablesByDatabase(string $database): array;

    public function getTables(): array;

    public function getColumnsByTable(string $database, string $tableName): array;

    public function getForeignKeys(string $database, string $tableName): array;
}