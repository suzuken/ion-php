<?php

declare(strict_types=1);

use Ion\Ion;
use PHPUnit\Framework\TestCase;

/**
 * Class IntegrationTest
 *
 * Tests by ion-tests/
 */
class IntegrationTest extends TestCase
{
    private string $dir = __DIR__ . '/../ion-tests/iontestdata/';
    /**
     * @var string[]
     */
    private array $badCases;
    private array $goodCases;
    private array $goodEquivsCases;
    private array $goodNonEquivsCases;

    protected function setUp(): void
    {
        $this->badCases = $this->files($this->dir . 'bad');
        $this->goodCases = $this->files($this->dir . 'good');
        $this->goodEquivsCases = $this->files($this->dir . 'good/equivs');
        $this->goodNonEquivsCases = $this->files($this->dir . 'good/non-equivs');
    }

    private function endWith(string $haystack, string $needle): bool
    {
        $length = strlen($needle);
        if ($length === 0) {
            return true;
        }
        return substr($haystack,  -$length) === $needle;
    }

    private function files(string $dir, array &$results = []): array
    {
        $files = scandir($dir);
        foreach ($files as $k => $v) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $v);
            if (!is_dir($path)) {
                if ($this->endWith($path, 'ion')
                    || ($this->endWith($path, '10n')))
                {
                    $results []= $path;
                }
            } else if ($v != '.' && $v != '..') {
                $this->files($path, $results);
            }
        }
        return $results;
    }

    public function testBad()
    {
        $ion = new Ion();
        foreach($this->badCases as $filename) {
            $tmp = file_get_contents($filename);
            $this->expectException(\Ion\ParserException::class);
            $ion->load($tmp);
        }
    }

    public function testGood()
    {
        $ion = new Ion();
        foreach($this->goodCases as $filename) {
            $tmp = file_get_contents($filename);
            $ion->load($tmp);
        }
    }
}
