<?php
namespace Oino\Services;

use \Oino\Settings;
use \Oino\Services\DBParser\IDBParser;
use \Oino\Services\DBParser\SchemaDBParser;

class DBParser
{
    private $settings;

    private $executeType;

    public function __construct()
    {
        $appSettings = Settings::load();

        if ($appSettings->isEmpty('db')) {
            throw new \Exception("Database config is empty");
        }

        if ($appSettings->isEmpty('db-execute')) {
            throw new \Exception("Execution driver config is empty");
        }

        $this->settings = $appSettings->get('db');
        $this->executeType = $appSettings->get('db-execute');
    }

    private function getParser(): IDBParser
    {
        if ($this->executeType === 'schema') {
            return new SchemaDBParser($this->settings);
        }

        throw new \Exception("Execution driver not supported");
    }

    public function getTables(): array
    {
        return $this->getParser()->getTables();
    }

    public function getColumnsByTable(string $database, string $tableName): array
    {
        return $this->getParser()->getColumnsByTable($database, $tableName);
    }

    public function getForeignKeys(string $database, string $tableName): array
    {
        return $this->getParser()->getForeignKeys($database, $tableName);
    }
}