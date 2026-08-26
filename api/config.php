<?php
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_NAME', getenv('DB_NAME') ?: 'camabox');
define('DB_USER', getenv('DB_USER') ?: 'camabox');
define('DB_PASS', getenv('DB_PASS') ?: 'camabox_secret');

$retries = 30;
$pdo = null;
while ($retries > 0) {
    try {
        $pdo = new PDO(
            sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', DB_HOST, DB_NAME),
            DB_USER, DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
        break;
    } catch (PDOException $e) {
        $retries--;
        if ($retries === 0) { http_response_code(500); echo json_encode(['error' => 'Database connection failed']); exit; }
        usleep(500000);
    }
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
