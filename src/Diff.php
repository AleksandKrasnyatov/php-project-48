<?php

namespace Package\Diff;

use function Package\Parsers\parse;

function genDiff(string $pathToFile1, string $pathToFile2): string
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

    $differences = compareArrays($content1, $content2);
    return render($differences);
}

function compareArrays(array $arr1, array $arr2): array
{
    $result = [];
    $allUniqueKeys = array_keys(array_merge($arr1, $arr2));
    sort($allUniqueKeys);
    foreach ($allUniqueKeys as $key) {
        if (!array_key_exists($key, $arr1)) {
            $result["+ {$key}"] = $arr2[$key];
        } elseif (!array_key_exists($key, $arr2)) {
            $result["- {$key}"] = $arr1[$key];
        } elseif ($arr1[$key] === $arr2[$key]) {
            $result["  {$key}"] = $arr1[$key];
        } else {
            $result["- {$key}"] = $arr1[$key];
            $result["+ {$key}"] = $arr2[$key];
        }
    }
    return $result;
}

function render(array $items): string
{
    $resultArray = [];
    foreach ($items as $key => $item) {
        if (!is_string($item)) {
            $item = var_export($item, true);
        }
        $resultArray[] = "  {$key}: {$item}";
    }
    $resultStr = implode("\n", $resultArray);
    return "{" . "\n" . $resultStr . "\n" . "}" . "\n";
}
