<?php

namespace Package\Formatters\Json;

function render($value): string
{
    $value = handleKeys($value);
    return json_encode($value, JSON_PRETTY_PRINT);
}

function handleKeys($data)
{
    $result = [];
    foreach ($data as $key => $item) {
        $key = trim($key);
        if (is_array($item)) {
            $result[$key] = handleKeys($item);
        } else {
            $result[$key] = $item;
        }
    }
    return $result;
}
