<?php

declare(strict_types=1);

namespace Ion;

const SymbolIdUnknown = 1;

abstract class SymbolTable
{
    /**
     * @return SharedSymbolTable[]|null
     */
    abstract public function imports(): ?array;
    abstract public function find(string $s): ?SymbolToken;
    abstract public function findByName(string $s): ?int;
    abstract public function findById(int $id): ?string;
    abstract public function getMaxId(): int;
}

class ImportSource
{
    public string $table;
    public int $sid;

    public function equal(ImportSource $o): bool
    {
        return $this->table === $o->table && $this->sid === $o->sid;
    }
}