<?php
require __DIR__ . '/config.php';
require __DIR__ . '/middleware.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

switch ($method) {
    case 'GET':
        $stmt = $pdo->query('SELECT * FROM custom_characteristics ORDER BY id');
        echo json_encode($stmt->fetchAll());
        break;
    case 'POST':
        requireAdmin();
        $name = $input['name'] ?? '';
        $type = $input['type'] ?? 'text';
        $options = $input['options'] ?? null;
        if (!$name) { http_response_code(400); echo json_encode(['error' => 'name required']); break; }

        $stmt = $pdo->prepare('INSERT IGNORE INTO custom_characteristics (name,type,options) VALUES (?,?,?)');
        $stmt->execute([$name, $type, $options]);

        $pdo->prepare('UPDATE product_dynamic_features SET value = value WHERE characteristic_name = ?')->execute([$name]);

        echo json_encode(['status' => 'ok']);
        break;
    case 'DELETE':
        requireAdmin();
        $name = $_GET['name'] ?? '';
        if (!$name) { http_response_code(400); echo json_encode(['error' => 'name required']); break; }
        $pdo->prepare('DELETE FROM custom_characteristics WHERE name = ?')->execute([$name]);
        $pdo->prepare('DELETE FROM product_dynamic_features WHERE characteristic_name = ?')->execute([$name]);
        echo json_encode(['status' => 'ok']);
        break;
}
