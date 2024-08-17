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
    $sortingRes = array_multisort($allUniqueKeys);

    return array_reduce($allUniqueKeys, function ($result, $key) use ($data1, $data2, $plain) {
        if (!property_exists($data1, $key)) {
            $result["+ {$key}"] = $data2->$key;
            return $result;
        } elseif (!property_exists($data2, $key)) {
            $result["- {$key}"] = $data1->$key;
            return $result;
        } elseif (is_object($data1->$key) && is_object($data2->$key)) {
            $result["  {$key}"] = compare($data1->$key, $data2->$key, $plain);
            return $result;
        } elseif ($data1->$key === $data2->$key) {
            $result["  {$key}"] = $data1->$key;
            return $result;
        } else {
            if ($plain) {
                $result["-+{$key}"] = [$data1->$key, $data2->$key];
                return $result;
            }
            $result["- {$key}"] = $data1->$key;
            $result["+ {$key}"] = $data2->$key;
            return $result;
        }
    }, []);
}
