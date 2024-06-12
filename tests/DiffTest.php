<?php

namespace Package\Phpunit\Diff;

use PHPUnit\Framework\TestCase;

use function Package\Diff\genDiff;

class DiffTest extends TestCase
{
    public function testJson(): void
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expected1step.txt");
        $first = __DIR__ . "/fixtures/file1.json";
        $second = __DIR__ . "/fixtures/file2.json";
        $this->assertEquals(genDiff($first, $second), $expected);
    }

    public function testYaml(): void
    {
        $expected = file_get_contents(__DIR__ . "/fixtures/expected1step.txt");
        $first = __DIR__ . "/fixtures/file1.yml";
        $second = __DIR__ . "/fixtures/file2.yml";
        $this->assertEquals(genDiff($first, $second), $expected);
    }
}
