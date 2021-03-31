<?php
namespace Oino\Aggregates;

use \Oino\ValueObjects\VOTable;
use \Oino\ValueObjects\VOColumn;
use \Oino\Helpers\InstanceHelper;

class ACreateTable
{
    private $table;

    /** @var VOColumn[]  */
    private $columns;

    public function __construct(VOTable $table, array $columns)
    {
        InstanceHelper::isArrayInstanceOf($columns, VOColumn::class);

        $this->table = $table;
        $this->columns = $columns;
    }

    public function isColumnsEmpty(): bool
    {
        return empty($this->columns);
    }

    public function getTable(): VOTable
    {
        return $this->table;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
}