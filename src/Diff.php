<?php

namespace Differ\Differ;

use function Package\Parsers\parse;
use function Package\Formatters\render;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $file1 = realpath($pathToFile1);
    $file2 = realpath($pathToFile2);

    if ($file1 === false || $file2 === false) {
        return "Error! Can't find the file(-s)!";
    }

    if (!file_exists($file1) || !file_exists($file2)) {
        return "Error! File(-s) doesn't exists!";
    }

    $content1 = parse($file1);
    $content2 = parse($file2);

    $plain = false;
    if ($format === "plain") {
        $plain = true;
    }

    $differences = compare($content1, $content2, $plain);
    return render($differences, $format);
}

function compare($data1, $data2, $plain = false): array
{
    $result = [];
    $usedKeys = [];
    $keys1 = getObjectKeys($data1);
    $keys2 = getObjectKeys($data2);
    $allUniqueKeys = array_merge($keys1, $keys2);
    sort($allUniqueKeys);
    foreach ($allUniqueKeys as $key) {
        if (!property_exists($data1, $key)) {
            $result["+ {$key}"] = $data2->$key;
        } elseif (!property_exists($data2, $key)) {
            $result["- {$key}"] = $data1->$key;
        } elseif (is_object($data1->$key) && is_object($data2->$key)) {
            $result["  {$key}"] = compare($data1->$key, $data2->$key, $plain);
        } elseif ($data1->$key === $data2->$key) {
            $result["  {$key}"] = $data1->$key;
        } else {
            if ($plain) {
                $result["-+{$key}"] = [$data1->$key, $data2->$key];
            } else {
                $result["- {$key}"] = $data1->$key;
                $result["+ {$key}"] = $data2->$key;
            }
        }
    }
    return $result;
}

function getObjectKeys($data): array
{
    $keys = [];
    foreach ($data as $key => $value) {
        $keys[] = $key;
    }
    return $keys;
}
