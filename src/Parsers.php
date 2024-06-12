<?php

namespace Package\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $fileName)
{
    $content = file_get_contents($fileName);
    if (str_contains($fileName, 'yml') || str_contains($fileName, 'yaml')) {
        return Yaml::parse($content);
    } if (str_contains($fileName, 'json')) {
        return json_decode($content, true);
    }
}
