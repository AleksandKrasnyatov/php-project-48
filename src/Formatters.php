<?php

namespace Package\Formatters;

use function Package\Formatters\Stylish\render as stylishRender;
use function Package\Formatters\Plain\render as plainRender;

function render(array $items, string $format): string
{
    switch ($format) {
        case 'plain':
            return plainRender($items);
        case 'stylish':
            return stylishRender($items);
        default:
            return stylishRender($items);
    }
}

function toString($value)
{
    if (is_null($value)) {
        return trim('null', "'");
    }
    return trim(var_export($value, true), "'");
}
