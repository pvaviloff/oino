<?php
namespace Oino\ValueObjects;

class VOExistColumn
{
    private $name;

    private $type;

    private $isAutoincrement;

    private $definition;

    private $comment;

    private $default;

    private $isFixed;

    private $length;

    private $isNotNull;

    private $precision;

    private $scale;

    private $isUnsigned;

    private $isPrimary;

    private $isUnique;

    public function __construct(
        string $name,
        string $type,
        bool $isAutoincrement,
        string $definition,
        string $comment,
        string $default,
        bool $isFixed,
        int $length,
        bool $isNotNull,
        int $precision,
        int $scale,
        bool $isUnsigned,
        bool $isPrimary,
        bool $isUnique
    )
    {
        $this->name = $name;
        $this->type = $type;
        $this->isAutoincrement = $isAutoincrement;
        $this->definition = $definition;
        $this->comment = $comment;
        $this->default = $default;
        $this->isFixed = $isFixed;
        $this->length = $length;
        $this->isNotNull = $isNotNull;
        $this->precision = $precision;
        $this->scale = $scale;
        $this->isUnsigned = $isUnsigned;
        $this->isPrimary = $isPrimary;
        $this->isUnique = $isUnique;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isAutoincrement(): bool
    {
        return $this->isAutoincrement;
    }

    public function getDefinition(): string
    {
        return $this->definition;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getDefault(): string
    {
        return $this->default;
    }

    public function isFixed(): bool
    {
        return $this->isFixed;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function isNotNull(): bool
    {
        return $this->isNotNull;
    }

    public function getPrecision(): int
    {
        return $this->precision;
    }

    public function getScale(): int
    {
        return $this->scale;
    }

    public function isUnsigned(): bool
    {
        return $this->isUnsigned;
    }

    public function isPrimary(): bool
    {
        return $this->isPrimary;
    }

    public function isUnique(): bool
    {
        return $this->isUnique;
    }
}