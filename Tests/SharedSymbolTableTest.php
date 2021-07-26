<?php

declare(strict_types=1);

use Ion\SharedSymbolTable;
use PHPUnit\Framework\TestCase;

class SharedSymbolTableTest extends TestCase
{
    public function testName()
    {
        $st = new SharedSymbolTable('test', 2, [
            'abc',
            'def',
            'def',
            'ghi',
        ]);

        $this->assertSame('test', $st->getName());
        $this->assertSame(2, $st->getVersion());
        $this->assertSame(4, $st->getMaxId());
    }
}
