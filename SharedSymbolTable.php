<?php

namespace Ion;

class SharedSymbolTable extends SymbolTable
{
    private string $name;
    private int $version;
    private array $symbols;
    /**
     * @var array
     */
    private array $index;
    private int $maxId;

    /**
     * SharedSymbolTable constructor.
     * @param string $name
     * @param int $version
     * @param string[] $symbols
     */
    public function __construct(string $name, int $version, array $symbols)
    {
        $this->name = $name;
        $this->version = $version;
        $this->symbols = $symbols;
        $this->index = $this->buildIndex($symbols, 1);
        $this->maxId = count($symbols);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getSymbols(): array
    {
        return $this->symbols;
    }

    public function getIndex(): array
    {
        return $this->index;
    }

    public function getMaxId(): int
    {
        return $this->maxId;
    }

    private function buildIndex($symbols, int $offset): array
    {
        $index = [];
        foreach ($symbols as $i => $sym) {
            if ($sym !== "") {
                if (!isset($index[$sym])) {
                    $index[$sym] = $offset + $i;
                }
            }
        }
        return $index;
    }

    public function find(string $symbol): ?SymbolToken
    {
        $id = $this->findByName($symbol);
        if ($id === null) {
            return null;
        }
        $text = $this->findById($id);
        if ($text === null) {
            return null;
        }
        return new SymbolToken($text, $id, nil);
    }

    public function findByName(string $symbol): ?int
    {
        return $this->index[$symbol] ?? null;
    }

    public function findById(int $id): ?string
    {
        return $this->symbols[$id - 1] ?? null;
    }
}