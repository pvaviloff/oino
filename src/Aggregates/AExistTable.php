<?php
namespace Oino\Aggregates;

use \Oino\ValueObjects\VOExistTable;
use \Oino\ValueObjects\VOExistColumn;
use \Oino\Helpers\InstanceHelper;

class AExistTable
{
    private $table;

    /** @var VOExistColumn[] */
    private $columns;

    public function __construct(VOExistTable $table, array $columns)
    {
        InstanceHelper::isArrayInstanceOf($columns, VOExistColumn::class);

        $this->table = $table;
        $this->columns = $columns;
    }

    public function getTable(): VOExistTable
    {
        return $this->table;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
}