<?php
namespace Oino\Aggregates;

use \Oino\ValueObjects\VOExistForeignKey;
use \Oino\Helpers\InstanceHelper;

class ADatabase
{
    private $name;

    /** @var AExistTable[]  */
    private $tables;

    /** @var VOExistForeignKey[]  */
    private $foreignKeys;

    public function __construct(string $name, array $tables, array $foreignKeys)
    {
        InstanceHelper::isArrayInstanceOf($tables, AExistTable::class);
        InstanceHelper::isArrayInstanceOf($foreignKeys, VOExistForeignKey::class);

        $this->name = $name;
        $this->tables = $tables;
        $this->foreignKeys = $foreignKeys;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTables(): array
    {
        return $this->tables;
    }

    public function getForeignKeys(): array
    {
        return $this->foreignKeys;
    }

    public function isForeignKeyExist(string $fromTable, string $fromColumn, string $toTable, string $toColumn): bool
    {
        foreach ($this->foreignKeys as $foreignKey) {
            if (
                $foreignKey->getFromTableName() == $fromTable &&
                in_array($fromColumn, $foreignKey->getFromColumns()) &&
                $foreignKey->getToTableName() == $toTable &&
                in_array($toColumn, $foreignKey->getToColumns())
            ) {
                return true;
            }
        }

        return false;
    }
}