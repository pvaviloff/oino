<?php
namespace Oino\ValueObjects;

class VOColumn
{
    private $guid;

    private $isKey;

    private $columnName;

    private $type;

    private $typeSize;

    private $defaultValue;

    private $comment;

    private $foreignKeyToGuid;

    public function __construct(
        string $guid,
        bool $isKey,
        string $columnName,
        string $type,
        string $typeSize,
        string $defaultValue,
        string $comment,
        string $foreignKeyToGuid
    )
    {
        $this->guid = $guid;
        $this->isKey = $isKey;
        $this->columnName = $columnName;
        $this->type = $type;
        $this->typeSize = $typeSize;
        $this->defaultValue = $defaultValue;
        $this->comment = $comment;
        $this->foreignKeyToGuid = $foreignKeyToGuid;
    }

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function isKey(): bool
    {
        return $this->isKey;
    }

    public function getColumnName(): string
    {
        return $this->columnName;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isNullable(): bool
    {
        return empty($this->defaultValue);
    }

    public function haveForeignKey(): bool
    {
        return !empty($this->foreignKeyToGuid);
    }

    public function getTypeSize(): string
    {
        return $this->typeSize;
    }

    public function getDefaultValue(): string
    {
        return $this->defaultValue;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getForeignKeyToGuid(): string
    {
        return $this->foreignKeyToGuid;
    }
}