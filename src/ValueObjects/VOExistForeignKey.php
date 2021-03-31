<?php
namespace Oino\ValueObjects;

class VOExistForeignKey
{
    private $fromTableName;

    private $fromColumns;

    private $toTableName;

    private $toColumns;

    public function __construct(string $fromTableName, array $fromColumns, string $toTableName, array $toColumns)
    {
        $this->fromTableName = $fromTableName;
        $this->fromColumns = $fromColumns;
        $this->toTableName = $toTableName;
        $this->toColumns = $toColumns;
    }

    public function getFromTableName(): string
    {
        return $this->fromTableName;
    }

    public function getFromColumns(): array
    {
        return $this->fromColumns;
    }

    public function getToTableName(): string
    {
        return $this->toTableName;
    }

    public function getToColumns(): array
    {
        return $this->toColumns;
    }
}