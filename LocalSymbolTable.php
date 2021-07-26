<?php

declare(strict_types=1);

namespace Ion;

class LocalSymbolTable
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
     * @param SharedSymbolTable[] $imports
     */
    private function processImports(array $imports)
    {
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