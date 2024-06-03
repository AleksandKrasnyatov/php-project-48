<?php

namespace Package\Diff;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $path1 = realpath($pathToFile1);
    $path2 = realpath($pathToFile2);

    if ($path1 === false || $path2 === false) {
        return "Error! Can't find the file(-s)!";
    }

    if (!file_exists($path1) || !file_exists($path2)) {
        return "Error! File(-s) doesn't exists!";
    }

    $json1 = file_get_contents($path1);
    $json2 = file_get_contents($path2);

    $content1 = json_decode($json1, true);
    $content2 = json_decode($json2, true);

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
