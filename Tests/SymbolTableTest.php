<?php

declare(strict_types=1);

use Ion\LocalSymbolTable;
use Ion\SharedSymbolTable;
use Ion\SymbolTable;
use PHPUnit\Framework\TestCase;

class SymbolTableTest extends TestCase
{
    public function testSharedSymbolTable()
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
        $this->testFindByName($st, 'null', null);

        $this->testFindByID($st, 0, null);
        $this->testFindByID($st, 2, 'def');
        $this->testFindByID($st, 4, 'ghi');
        $this->testFindByID($st, 100, null);
    }

    public function testLocalSymbolTable()
    {
        $lt = new LocalSymbolTable([], ['foo', 'bar']);
        $this->assertSame(11, $lt->getMaxId());

        $this->testFindByName($lt, '$ion', 1);
        $this->testFindByName($lt, 'foo', 10);
        $this->testFindByName($lt, 'bar', 11);
        $this->testFindByName($lt, 'bogus', null);

        $this->testFindByID($lt, 0, null);
        $this->testFindByID($lt, 1, '$ion');
        $this->testFindByID($lt, 10, 'foo');
        $this->testFindByID($lt, 11, 'bar');
        $this->testFindByID($lt, 12, ''); // TODO to be null?
    }

    private function testFindByName(SymbolTable $table, string $name, ?int $index)
    {
        $this->assertSame($index, $table->findByName($name));
    }

    private function testFindByID(SymbolTable $table, int $index, ?string $name)
    {
        $this->assertSame($name, $table->findByID($index));
    }
}
