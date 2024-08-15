<?php

namespace Package\Formatters\Stylish;

use function Package\Formatters\toString;

function render(array $value, string $replacer = ' ', int $spacesCount = 4): string
{
    $iter = function ($currentValue, $depth) use (&$iter, $replacer, $spacesCount) {
        if (!is_array($currentValue) && !is_object($currentValue)) {
            return toString($currentValue);
        }
        $offset = 2;
        $indentSize = $depth * $spacesCount;
        $currentIndentArr = str_repeat($replacer, $indentSize - $offset);
        $currentIndentObj = str_repeat($replacer, $indentSize);
        $bracketIndent = str_repeat($replacer, $indentSize - $spacesCount);
        $lines = [];
        if (is_object($currentValue)) {
            foreach ($currentValue as $key => $value) {
                $lines[] = "{$currentIndentObj}{$key}: {$iter($value, $depth + 1)}";
            }
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
