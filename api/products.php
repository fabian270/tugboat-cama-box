<?php
require __DIR__ . '/config.php';
require __DIR__ . '/middleware.php';

if (file_exists(__DIR__ . '/products_functions.php')) {
    require __DIR__ . '/products_functions.php';
} else {
    require __DIR__ . '/products.php';
}

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

try {
    switch ($method) {
        case 'GET':
            echo json_encode(getProducts($pdo));
            break;
        case 'POST':
            requireAuth();
            if (!empty($input['name'])) {
                $id = saveProduct($pdo, $input);
                echo json_encode(['id' => $id, 'status' => 'ok']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'name required']);
            }
            break;
        case 'PUT':
            requireAuth();
            if (!empty($input['id'])) {
                saveProduct($pdo, $input);
                echo json_encode(['status' => 'ok']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'id required']);
            }
            break;
        case 'DELETE':
            requireAuth();
            $id = $_GET['id'] ?? '';
            if ($id) {
                deleteProduct($pdo, $id);
                echo json_encode(['status' => 'ok']);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'id required']);
            }
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
