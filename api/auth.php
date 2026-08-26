<?php
require __DIR__ . '/config.php';
session_start();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

switch ($method) {
    case 'GET':
        if (isset($_SESSION['user'])) {
            echo json_encode(['logged' => true, 'user' => $_SESSION['user']]);
        } else {
            echo json_encode(['logged' => false]);
        }
        break;

    case 'POST':
        $action = $input['action'] ?? 'login';

        if ($action === 'login') {
            $username = $input['username'] ?? '';
            $password = $input['password'] ?? '';

            if (!$username || !$password) {
                http_response_code(400);
                echo json_encode(['error' => 'Usuario y contraseña requeridos']);
                break;
            }

            $stmt = $pdo->prepare('SELECT id, username, password_hash, role FROM users WHERE username = ?');
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                unset($user['password_hash']);
                $_SESSION['user'] = $user;
                echo json_encode(['status' => 'ok', 'user' => $user]);
            } else {
                http_response_code(401);
                echo json_encode(['error' => 'Credenciales incorrectas']);
            }
        } elseif ($action === 'logout') {
            session_destroy();
            echo json_encode(['status' => 'ok']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Acción inválida']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
