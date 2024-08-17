<?php

namespace Package\Formatters;

use function Package\Formatters\Stylish\render as stylishRender;
use function Package\Formatters\Plain\render as plainRender;
use function Package\Formatters\Json\render as jsonRender;

function render(array $items, string $format): string
{
    return match ($format) {
        'plain' => plainRender($items),
        'json' => jsonRender($items),
        default => stylishRender($items),
    };
}

function toString(mixed $value): string
{
    if (is_null($value)) {
        return trim('null', "'");
    }
    return trim(var_export($value, true), "'");
}
