<?php
require __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['status' => 'ok', 'message' => 'API is running']);
