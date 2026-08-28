<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

$dbType = getenv('DB_TYPE') ?: 'mysql';

if ($dbType === 'sqlite') {
    $dbPath = getenv('DB_PATH') ?: __DIR__ . '/../data/camabox.db';
    $dir = dirname($dbPath);
    if (!is_dir($dir)) { mkdir($dir, 0777, true); }
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA journal_mode=WAL');
    $pdo->exec('PRAGMA foreign_keys=ON');

    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id TEXT PRIMARY KEY, name TEXT NOT NULL, location TEXT DEFAULT '', price REAL DEFAULT 0,
        url TEXT DEFAULT '', images TEXT DEFAULT '[]', drawers INTEGER DEFAULT 0,
        shoe_rack INTEGER DEFAULT 0, inner_storage INTEGER DEFAULT 0, shelf INTEGER DEFAULT 0,
        closures TEXT DEFAULT '[]', size_type TEXT DEFAULT '', dimensions TEXT DEFAULT '',
        assembly TEXT DEFAULT '', manual INTEGER DEFAULT 0, assembly_place TEXT DEFAULT '',
        is_new INTEGER DEFAULT 1, product_type TEXT DEFAULT '', alt_page TEXT DEFAULT '',
        decision TEXT DEFAULT '', created_at TEXT DEFAULT (datetime('now')),
        updated_at TEXT DEFAULT (datetime('now'))
    )");
    $pdo->exec("CREATE TABLE IF NOT EXISTS product_types (
        id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL UNIQUE,
        created_at TEXT DEFAULT (datetime('now'))
    )");
    $cols = $pdo->query("PRAGMA table_info(products)")->fetchAll();
    $cols = array_column($cols, 'name');
    if (!in_array('product_type', $cols)) {
        $pdo->exec("ALTER TABLE products ADD COLUMN product_type TEXT DEFAULT ''");
    }
    if (!in_array('alt_page', $cols)) {
        $pdo->exec("ALTER TABLE products ADD COLUMN alt_page TEXT DEFAULT ''");
    }
    if (!in_array('decision', $cols)) {
        $pdo->exec("ALTER TABLE products ADD COLUMN decision TEXT DEFAULT ''");
    }
    $pdo->exec("CREATE TABLE IF NOT EXISTS product_colors (
        id INTEGER PRIMARY KEY AUTOINCREMENT, product_id TEXT NOT NULL,
        hex TEXT NOT NULL, name TEXT NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )");
    $pdo->exec("CREATE TABLE IF NOT EXISTS custom_characteristics (
        id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL UNIQUE,
        type TEXT NOT NULL DEFAULT 'text', options TEXT DEFAULT NULL
    )");
    $pdo->exec("CREATE TABLE IF NOT EXISTS product_dynamic_features (
        id INTEGER PRIMARY KEY AUTOINCREMENT, product_id TEXT NOT NULL,
        characteristic_name TEXT NOT NULL, value TEXT DEFAULT '',
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
        UNIQUE(product_id, characteristic_name)
    )");
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT NOT NULL UNIQUE,
        password_hash TEXT NOT NULL, role TEXT NOT NULL DEFAULT 'editor' CHECK(role IN ('admin','editor')),
        created_at TEXT DEFAULT (datetime('now'))
    )");
    $pdo->exec("INSERT OR IGNORE INTO users (username, password_hash, role) VALUES ('admin', '\$2y\$10\$LmSqci180Ie25D1ELzmVxOtcbW3WAE2By3EJnRHBMB77Hq0U0HXWG', 'admin')");
} else {
    $required = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'];
    $missing = array_filter($required, fn($k) => empty(getenv($k)));
    if ($missing) {
        http_response_code(500);
        echo json_encode(['error' => 'Missing env vars: ' . implode(', ', $missing)]);
        exit;
    }
    $retries = 30;
    $pdo = null;
    while ($retries > 0) {
        try {
            $pdo = new PDO(
                sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', getenv('DB_HOST'), getenv('DB_NAME')),
                getenv('DB_USER'), getenv('DB_PASS'),
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
            break;
        } catch (PDOException $e) {
            $retries--;
            if ($retries === 0) { http_response_code(500); echo json_encode(['error' => 'Database connection failed']); exit; }
            usleep(500000);
        }
    }
}
