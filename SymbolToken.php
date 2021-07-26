<?php

namespace Ion;

class SymbolToken
{
    public ?string $text;
    public int $localSID;
    public ?ImportSource $source;

    public function __construct(?string $text, int $localSID, ?ImportSource $importSource)
    {
        $this->text = $text;
        $this->localSID = $localSID;
        $this->source = $importSource;
    }

    public static function createFromString(string $text): SymbolToken
    {
        return new SymbolToken($text, SymbolIdUnknown, null);
    }

    public function equal(SymbolToken $o): bool
    {
        if ($this->text === null && $o->text === null) {
            if ($this->source === null && $o->source === null) {
                return true;
            }
            if ($this->source !== null && $o->source !== null) {
                return $this->source->equal($o->source);
            }
            return false;
        }

        if ($this->text !== null && $o->text !== null)
        {
            return $this->text === $o->text;
        }
        return false;
    }
}