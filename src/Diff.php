<?php

namespace Differ\Differ;

use function Package\Formatters\render;
use function Package\Parsers\parse;

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

    $plain = $format === "plain";

    $differences = compare($content1, $content2, $plain);
    return render($differences, $format);
}

function compare(object $data1, object $data2, bool $plain = false): array
{
    $allUniqueKeys = array_merge(array_keys((array)$data1), array_keys((array)$data2));

    return array_reduce(bubbleSort($allUniqueKeys), function ($result, $key) use ($data1, $data2, $plain) {
        if (!property_exists($data1, $key)) {
            return array_merge($result, ["+ {$key}" => $data2->$key]);
        } elseif (!property_exists($data2, $key)) {
            return array_merge($result, ["- {$key}" => $data1->$key]);
        } elseif (is_object($data1->$key) && is_object($data2->$key)) {
            return array_merge($result, ["  {$key}" => compare($data1->$key, $data2->$key, $plain)]);
        } elseif ($data1->$key === $data2->$key) {
            return array_merge($result, ["  {$key}" => $data1->$key]);
        } else {
            if ($plain) {
                return array_merge($result, ["-+{$key}" => [$data1->$key, $data2->$key]]);
            }
            return array_merge($result, [
                "- {$key}" => $data1->$key,
                "+ {$key}" => $data2->$key,
            ]);
        }
    }, []);
}

function bubbleSort(array $items): array
{
    for ($limit = count($items) - 1; $limit > 0; $limit -= 1) {
        for ($i = 0; $i < $limit; $i += 1) {
            if ($items[$i] > $items[$i + 1]) {
                $temporary = $items[$i];
                $items[$i] = $items[$i + 1];
                $items[$i + 1] = $temporary;
            }
        }
    }
    return $items;
}
