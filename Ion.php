<?php

declare(strict_types=1);

namespace Ion;

class Ion
{
    public function load(string $data)
    {
        throw new ParserException();
    }

    public function dump(): string
    {
        return '';
    }
}