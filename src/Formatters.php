<?php

namespace Package\Formatters;

use function Package\Formatters\Stylish\render as stylishRender;
use function Package\Formatters\Plain\render as plainRender;
use function Package\Formatters\Json\render as jsonRender;

function render(array $items, string $format): string
{
    switch ($format) {
        case 'plain':
            return plainRender($items);
        case 'stylish':
            return stylishRender($items);
        case 'json':
            return jsonRender($items);
        default:
            return stylishRender($items);
    }
}

function toString(mixed $value)
{
    if (is_null($value)) {
        return trim('null', "'");
    }
    return trim(var_export($value, true), "'");
}
