<?php

function validatePackageDefinition(array $packages): void
{
    $flag = true;
    foreach (array_keys($packages) as $key) {
        if (thereIs($packages[$key], 'name', $key)) {
            if ($key != $packages[$key]['name']) {
                echo "В пакете $key ключ массива не совпадает с именем, указанным под ключем name \n";
                $flag = false;
            }
        } else {
            $flag = false;
        }

        if ($flag && !thereIs($packages[$key], 'dependencies', $key)) {
            $flag = false;
        }

        if ($flag && !dependenciesDescribed($packages, $packages[$key]['dependencies'])) {
            echo "В пакете $key в dependencies указаны не опис  анные зависимости \n";
            $flag = false;
        }
        if ($flag && !isWhileDependence($packages, $key, ' ')) {
            echo 'Ошибка!Рекурсивная установка';
            $flag = false;
        }
        if (!$flag) break;
    }
}

function isWhileDependence(array $packages, string $packageName, string $string_package): string
{
    if ($string_package && count($packages[$packageName]['dependencies']) != 0) {
        foreach ($packages[$packageName]['dependencies'] as $pack) {
            $string_package .= $packageName;
            $array_package = str_split($string_package);
            if (!in_array($pack, $array_package)) {
                $string_package = isWhileDependence($packages, $pack, $string_package);
            } else {
                $string_package = '';
                break;
            }
            $string_package = substr($string_package, 0, -1);
        }
    }
    return $string_package;
}

function dependenciesDescribed(array $packages, array $arr_dependencies): bool
{
    foreach ($arr_dependencies as $depend) {
        foreach (array_keys($packages) as $key) {
            if ($depend === $key) {
                continue 2;
            }
        }
        return false;
    }
    return true;
}

function thereIs(array $package_key, string $key, string $packageName): bool
{
    if (array_key_exists($key, $package_key)) {
        return true;
    } else {
        echo "В пакете $packageName не существует элемента с ключем $key \n";
        return false;
    }
}

function getAllStringPackageDefinition(array $packages, string $packageName): string
{
    $string_packages = $packageName;
    foreach ($packages[$packageName]['dependencies'] as $pack) {
        $string_packages = getAllStringPackageDefinition($packages, $pack) . $string_packages;
    }
    return $string_packages;

}

function getAllPackageDefinition(array $packages, string $packageName): array
{
    $array_packages = array();
    if (isWhileDependence($packages, $packageName, ' ')) {
        $array_string = str_split(getAllStringPackageDefinition($packages, $packageName));
        $array_packages = array_unique($array_string);
    }
    return $array_packages;
}