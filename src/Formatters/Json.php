<?php

namespace Package\Formatters\Json;

function render(array $value): string
{

    return json_encode(handleKeys($value), JSON_PRETTY_PRINT);
}

function handleKeys(mixed $data): mixed
{
    if (is_array($data)) {
        $keys = array_map(fn($key) => trim($key), array_keys($data));
        $values = array_map(fn($value) => handleKeys($value), $data);
        return array_combine($keys, $values);
    }
    return $data;
}
