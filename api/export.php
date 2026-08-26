<?php
require __DIR__ . '/config.php';
require __DIR__ . '/products.php';

header('Content-Disposition: attachment; filename="camabox_backup_' . date('Y-m-d') . '.json"');
echo json_encode([
    'products' => getProducts($pdo),
    'customCharacteristics' => $pdo->query('SELECT name,type,options FROM custom_characteristics ORDER BY id')->fetchAll()
], JSON_PRETTY_PRINT);
