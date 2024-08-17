<?php

namespace Package\Formatters\Stylish;

use function Package\Formatters\toString;

function render(array $value, string $replacer = ' ', int $spacesCount = 4): string
{
    $iter = function (mixed $currentValue, int $depth) use (&$iter, $replacer, $spacesCount) {
        if (!is_array($currentValue) && !is_object($currentValue)) {
            return toString($currentValue);
        }
        $offset = 2;
        $indentSize = $depth * $spacesCount;
        $currentIndentArr = str_repeat($replacer, $indentSize - $offset);
        $currentIndentObj = str_repeat($replacer, $indentSize);
        $bracketIndent = str_repeat($replacer, $indentSize - $spacesCount);
        if (is_object($currentValue)) {
            $lines = array_map(function ($key, $value) use ($depth, $iter, $currentIndentObj) {
                return "{$currentIndentObj}{$key}: {$iter($value, $depth + 1)}";
            }, array_keys((array)$currentValue), (array)$currentValue);
        } else {
            $lines = array_map(
                function ($key, $val) use ($iter, $depth, $currentIndentArr) {
                    return "{$currentIndentArr}{$key}: {$iter($val, $depth + 1)}";
                },
                array_keys($currentValue),
                $currentValue
            );
        }
        $result = ['{', ...$lines, "{$bracketIndent}}"];
        return implode("\n", $result);
    };
    return "{$iter($value, 1)}\n";
}
