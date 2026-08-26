<?php
session_start();

function requireAuth() {
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['error' => 'No autenticado']);
        exit;
    }
    return $_SESSION['user'];
}

function requireAdmin() {
    $user = requireAuth();
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Se requieren permisos de administrador']);
        exit;
    }
    return $user;
}
