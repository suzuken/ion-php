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

        $this->testFindByName($st, 'def', 2);
        $this->testFindByName($st, 'ghi', 4);

        $this->testFindByID($st, 0, null);
        $this->testFindByID($st, 2, 'def');
        $this->testFindByID($st, 4, 'ghi');
        $this->testFindByID($st, 100, null);
    }

    private function testFindByName(SharedSymbolTable $table, string $name, int $index)
    {
        $this->assertSame($index, $table->findByName($name));
    }

    private function testFindByID(SharedSymbolTable $table, int $index, ?string $name)
    {
        $this->assertSame($name, $table->findByID($index));
    }
}
