<?php

declare(strict_types=1);

namespace Ion;

abstract class SymbolTable
{
    private const SymbolIdUnknown = -1;

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
}

class SymbolToken
{
    public ?string $text;
    public int $localSID;
    public ?ImportSource $importSource;

    public function __construct(?string $text, int $localSID, ?ImportSource $importSource)
    {
        $this->text = $text;
        $this->localSID = $localSID;
        $this->importSource = $importSource;
    }
}
