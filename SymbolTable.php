<?php

declare(strict_types=1);

namespace Ion;

interface SymbolTable
{
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
