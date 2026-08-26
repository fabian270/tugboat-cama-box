<?php
require __DIR__ . '/config.php';
require __DIR__ . '/middleware.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

requireAdmin();

try {
    switch ($method) {
        case 'GET':
            $stmt = $pdo->query('SELECT id, username, role, created_at FROM users ORDER BY created_at DESC');
            echo json_encode($stmt->fetchAll());
            break;

        case 'POST':
            $username = trim($input['username'] ?? '');
            $password = $input['password'] ?? '';
            $role = $input['role'] ?? 'editor';

            if (!$username || !$password) {
                http_response_code(400);
                echo json_encode(['error' => 'Usuario y contraseña requeridos']);
                break;
            }

            if (!in_array($role, ['admin', 'editor'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Rol inválido']);
                break;
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)');
            $stmt->execute([$username, $hash, $role]);

            echo json_encode(['status' => 'ok', 'id' => $pdo->lastInsertId()]);
            break;

        case 'DELETE':
            $id = $_GET['id'] ?? '';
            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'id required']);
                break;
            }

            $stmt = $pdo->prepare('SELECT id FROM users WHERE id = ?');
            $stmt->execute([$id]);
            if (!$stmt->fetch()) {
                http_response_code(404);
                echo json_encode(['error' => 'Usuario no encontrado']);
                break;
            }

            $sessionUser = $_SESSION['user'];
            if ((int)$id === (int)$sessionUser['id']) {
                http_response_code(400);
                echo json_encode(['error' => 'No podés eliminar tu propio usuario']);
                break;
            }

            $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
            echo json_encode(['status' => 'ok']);
            break;

        case 'PUT':
            $id = $input['id'] ?? '';
            $role = $input['role'] ?? '';
            $password = $input['password'] ?? '';

            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'id required']);
                break;
            }

            if ($role && !in_array($role, ['admin', 'editor'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Rol inválido']);
                break;
            }

            if ($password) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?')->execute([$hash, $id]);
            }

            if ($role) {
                $pdo->prepare('UPDATE users SET role = ? WHERE id = ?')->execute([$role, $id]);
            }

            echo json_encode(['status' => 'ok']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
