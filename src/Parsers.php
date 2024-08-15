<?php

namespace Package\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $fileName): mixed
{
    $content = file_get_contents($fileName);
    if (!is_string($content)) {
        return 'thats mistake here in reading file';
    }
    if (str_contains($fileName, 'yml') || str_contains($fileName, 'yaml')) {
        return Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
    } if (str_contains($fileName, 'json')) {
        return json_decode($content);
    }
    return 'thats must be mistake here in reading file';
}
