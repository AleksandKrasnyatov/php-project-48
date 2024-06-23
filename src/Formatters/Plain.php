<?php

namespace Package\Formatters\Plain;

use function Package\Formatters\toString;

function render($value): string
{
    $iter = function ($currentValue, $keys, $action = '') use (&$iter) {
        $property = implode(".", $keys);
        if ($action === "added") {
            $currentValue = handleValue($currentValue);
            return "Property '{$property}' was added with value: {$currentValue}";
        } elseif ($action === "removed") {
            return "Property '{$property}' was removed";
        } elseif ($action === "diff") {
            $currentValue = handleValue($currentValue, true);
            return "Property '{$property}' was updated. From {$currentValue[0]} to $currentValue[1]";
        }
        $lines = [];
        foreach ($currentValue as $key => $val) {
            $prefix = mb_substr($key, 0, 2);
            $curKeys = $keys;
            $curKeys[] = mb_substr($key, 2);
            if ($prefix == '  ' && is_array($val)) {
                $lines[] = $iter($val, $curKeys, '');
            } elseif ($prefix == '+ ') {
                $lines[] = $iter($val, $curKeys, "added");
            } elseif ($prefix == '- ') {
                $lines[] = $iter($val, $curKeys, "removed");
            } elseif (str_contains($key, '-+')) {
                $lines[] = $iter([$val[0], $val[1]], $curKeys, "diff");
            }
        }
        return implode("\n", $lines);
    };
    return "{$iter($value, [], '')}\n";
}

function handleValue($value, $each = false)
{
    if (is_array($value) || is_object($value)) {
        if (is_array($value) && $each) {
            return array_map(fn ($item) => handleValue($item), $value);
        }
        return "[complex value]";
    } elseif (!is_string($value)) {
        return toString($value);
    }
    return $value = "'{$value}'";
}
