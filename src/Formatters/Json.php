<?php

namespace Package\Formatters\Json;

function render(array $value): string
{
    return json_encode(handleKeys($value), JSON_PRETTY_PRINT);
}

function handleKeys(array $data): array
{
    $result = [];
    foreach ($data as $key => $item) {
        $trimmedKey = trim($key);
        if (is_array($item)) {
            $result[$trimmedKey] = handleKeys($item);
        } else {
            $result[$trimmedKey] = $item;
        }
    }
    return $result;
}
