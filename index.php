<?php include 'packages_manager.php';

$packages = array
(
    'A' => [
        'name' => 'A',
        'dependencies' => ['B', 'C'],
    ],
    'B' => [
        'name' => 'B',
        'dependencies' => [],
    ],
    'C' => [
        'name' => 'C',
        'dependencies' => ['B', 'D'],
    ],
    'D' => [
        'name' => 'D',
        'dependencies' => [],
    ]
);
validatePackageDefinition($packages);
if ($package_definition = getAllPackageDefinition($packages, 'A')) {
    echo 'Зависимость установки: ';
    foreach ($package_definition as $pack) {
        echo $pack;
    }
}
	