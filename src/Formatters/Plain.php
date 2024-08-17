<?php

namespace Package\Formatters\Plain;

use function Package\Formatters\toString;

function render(array $value): string
{
    $iter = function ($currentValue, $keys, $action = '') use (&$iter) {
        $property = implode(".", $keys);
        if ($action === "added") {
            $value = handleValue($currentValue);
            return "Property '{$property}' was added with value: {$value}";
        } elseif ($action === "removed") {
            return "Property '{$property}' was removed";
        } elseif ($action === "diff") {
            $value = handleValue($currentValue, true);
            return "Property '{$property}' was updated. From {$value[0]} to $value[1]";
        }
        $lines = array_map(function ($key, $val) use ($keys, $iter) {
            $prefix = mb_substr($key, 0, 2);
            $curKeys = $keys;
            $curKeys[] = mb_substr($key, 2);
            if ($prefix == '  ' && is_array($val)) {
                return $iter($val, $curKeys, '');
            } elseif ($prefix == '+ ') {
                return $iter($val, $curKeys, "added");
            } elseif ($prefix == '- ') {
                return $iter($val, $curKeys, "removed");
            } elseif (str_contains($key, '-+')) {
                return $iter([$val[0], $val[1]], $curKeys, "diff");
            }
            return null;
        }, array_keys($currentValue), array_values($currentValue));

        return implode("\n", array_filter($lines));
    };
    return "{$iter($value, [], '')}\n";
}

function handleValue(mixed $value, bool $each = false): string|array
{
    if (is_array($value) || is_object($value)) {
        if (is_array($value) && $each) {
            return array_map(fn($item) => handleValue($item), $value);
        }
        return "[complex value]";
    } elseif (!is_string($value)) {
        return toString($value);
    }
    return "'{$value}'";
}
