<?php
require __DIR__ . '/config.php';
require __DIR__ . '/middleware.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

switch ($method) {
    case 'GET':
        $stmt = $pdo->query('SELECT * FROM product_types ORDER BY id');
        echo json_encode($stmt->fetchAll());
        break;
    case 'POST':
        requireAdmin();
        $name = trim($input['name'] ?? '');
        if (!$name) { http_response_code(400); echo json_encode(['error' => 'name required']); break; }
        $stmt = $pdo->prepare('INSERT OR IGNORE INTO product_types (name) VALUES (?)');
        $stmt->execute([$name]);
        if ($stmt->rowCount() === 0) { http_response_code(400); echo json_encode(['error' => 'Ya existe']); break; }
        echo json_encode(['status' => 'ok', 'id' => $pdo->lastInsertId()]);
        break;
    case 'PUT':
        requireAdmin();
        $id = $input['id'] ?? '';
        $name = trim($input['name'] ?? '');
        if (!$id || !$name) { http_response_code(400); echo json_encode(['error' => 'id and name required']); break; }
        $stmt = $pdo->prepare('UPDATE product_types SET name = ? WHERE id = ?');
        $stmt->execute([$name, $id]);
        $pdo->prepare('UPDATE products SET product_type = ? WHERE product_type = (SELECT name FROM product_types WHERE id = ?)')->execute([$name, $id]);
        echo json_encode(['status' => 'ok']);
        break;
    case 'DELETE':
        requireAdmin();
        $id = $_GET['id'] ?? '';
        if (!$id) { http_response_code(400); echo json_encode(['error' => 'id required']); break; }
        $type = $pdo->prepare('SELECT name FROM product_types WHERE id = ?');
        $type->execute([$id]);
        $typeName = $type->fetchColumn();
        if ($typeName !== false) {
            $pdo->prepare('UPDATE products SET product_type = \'\' WHERE product_type = ?')->execute([$typeName]);
        }
        $pdo->prepare('DELETE FROM product_types WHERE id = ?')->execute([$id]);
        echo json_encode(['status' => 'ok']);
        break;
}
