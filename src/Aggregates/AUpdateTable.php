<?php
namespace Oino\Aggregates;

use Oino\ValueObjects\VOColumn;
use Oino\ValueObjects\VOTable;
use Oino\ValueObjects\VOExistTable;
use \Oino\Helpers\StringHelper;
use \Oino\Helpers\InstanceHelper;
use \Oino\ValueObjects\VOExistColumn;

class AUpdateTable
{
    private $table;

    private $existTable;

    /** @var VOColumn[] */
    private $columns;

    public function __construct(VOTable $table, array $columns, VOExistTable $existTable)
    {
        InstanceHelper::isArrayInstanceOf($columns, VOColumn::class);

        $this->table = $table;
        $this->columns = $columns;
        $this->existTable = $existTable;
    }

    public function isColumnsEmpty(): bool
    {
        return empty($this->columns);
    }

    public function isColumnChanged(VOExistColumn $existColumn): bool
    {
        foreach ($this->columns as $column) {
            if(StringHelper::equals($column->getColumnName(), $existColumn->getName())) {
                return (
                    $column->isKey() != $existColumn->isPrimary() ||
                    $column->getType() != $existColumn->getType() ||
                    $column->getComment() != $existColumn->getComment() ||
                    $column->getDefaultValue() != $existColumn->getDefault() ||
                    $column->getTypeSize() != $existColumn->getLength()
                );
            }
        }

        return false;
    }

    public function removeColumnByName(string $columnName): bool
    {
        $isRemoved = false;
        foreach ($this->columns as $key => $column) {
            if (StringHelper::equals($column->getColumnName(), $columnName)) {
                unset($this->columns[$key]);
                $isRemoved = true;
            }
        }

        return $isRemoved;
    }

    public function getTable(): VOTable
    {
        return $this->table;
    }

    public function getExistTable(): VOExistTable
    {
        return $this->existTable;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
}