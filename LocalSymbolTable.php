<?php

declare(strict_types=1);

namespace Ion;

class LocalSymbolTable extends SymbolTable
{
    /**
     * @var SharedSymbolTable[]
     */
    private array $imports;
    /**
     * @var int[]
     */
    private array $offsets;
    private int $maxImportId;

    /**
     * @var string[]
     */
    private array $symbols;
    /**
     * @var int[]
     */
    private array $index;

    /**
     * LocalSymbolTable constructor.
     * @param SharedSymbolTable[] $imports
     * @param string[] $symbols
     */
    public function __construct(array $imports, array $symbols)
    {
        [$imps, $offsets, $maxId] = $this->processImports($imports);

        $index = $this->buildIndex($symbols, $maxId + 1);

        $this->imports = $imps;
        $this->symbols = $symbols;
        $this->offsets = $offsets;
        $this->maxImportId = $maxId;
        $this->index = $index;
    }

    /**
     * @return SharedSymbolTable[]
     */
    public function getImports(): array
    {
        return $this->imports;
    }

    public function getOffsets(): array
    {
        return $this->offsets;
    }

    public function getMaxId(): int
    {
        return $this->maxImportId + count($this->symbols);
    }

    public function getMaxImportId(): int
    {
        return $this->maxImportId;
    }

    public function getSymbols(): array
    {
        return $this->symbols;
    }

    public function getIndex(): array
    {
        return $this->index;
    }

    public function find(string $s): ?SymbolToken
    {
        foreach ($this->imports as $imp) {
            $token = $imp->find($s);
            if ($token !== null) {
                return $token;
            }
        }

        if (isset($this->index[$s])) {
            return new SymbolToken($s, self::SymbolIdUnknown, null);
        }
        return null;
    }

    public function findByName(string $s): ?int
    {
        foreach ($this->imports as $i => $imp) {
            $id = $imp->findByName($s);
            if ($id !== null) {
                return $this->offsets[$i] + $id;
            }
        }

        return $this->index[$s] ?? null;
    }

    public function findById(int $id): ?string
    {
        if ($id <= 0) {
            return null;
        }
        if ($id <= $this->maxImportId) {
            return $this->findByIdInImports($id);
        }

        // local to this symbol table.
        $idx = $id - $this->maxImportId - 1;
        if ($idx < count($this->symbols)) {
            return $this->symbols[$idx];
        }
        return '';
    }

    private function findByIdInImports(int $id): ?string
    {
        $off = 0;
        for ($i = 1; $i < count($this->imports); $i++) {
            if ($id <= $this->offsets[$i]) {
                break;
            }
            $off = $this->offsets[$i];
        }
        return $this->imports[$i-1]->findById($id - $off);
    }

    public function imports(): ?array
    {
        // TODO: Implement imports() method.
    }

    /**
     * @param SharedSymbolTable[] $imports
     */
    private function processImports(array $imports): array
    {
        /** @var SharedSymbolTable[] $imps */
        $imps = [];
        if (count($imports) > 0 && $imports[0]->getName() === '$ion') {
            $imps = $imports;
        } else {
            $imps[0] = SharedSymbolTable::v1();
            array_push($imps, ...$imports);
        }
        $maxId = 0;
        $offsets = [];
        foreach ($imps as $i => $imp) {
            $offsets[$i] = $maxId;
            $maxId += $imp->getMaxId();
        }

        return [$imps, $offsets, $maxId];
    }

    private function buildIndex(array $symbols, int $offset): array
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

}