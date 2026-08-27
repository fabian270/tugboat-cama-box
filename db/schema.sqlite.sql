CREATE TABLE IF NOT EXISTS products (
    id TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    location TEXT DEFAULT '',
    price REAL DEFAULT 0,
    url TEXT DEFAULT '',
    images TEXT DEFAULT '[]',
    drawers INTEGER DEFAULT 0,
    shoe_rack INTEGER DEFAULT 0,
    inner_storage INTEGER DEFAULT 0,
    shelf INTEGER DEFAULT 0,
    closures TEXT DEFAULT '[]',
    size_type TEXT DEFAULT '',
    dimensions TEXT DEFAULT '',
    assembly TEXT DEFAULT '',
    manual INTEGER DEFAULT 0,
    assembly_place TEXT DEFAULT '',
    is_new INTEGER DEFAULT 1,
    product_type TEXT DEFAULT '',
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS product_types (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    created_at TEXT DEFAULT (datetime('now'))
);

CREATE TABLE IF NOT EXISTS product_colors (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id TEXT NOT NULL,
    hex TEXT NOT NULL,
    name TEXT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS custom_characteristics (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    type TEXT NOT NULL DEFAULT 'text',
    options TEXT DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS product_dynamic_features (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id TEXT NOT NULL,
    characteristic_name TEXT NOT NULL,
    value TEXT DEFAULT '',
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE(product_id, characteristic_name)
);

CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'editor' CHECK(role IN ('admin','editor')),
    created_at TEXT DEFAULT (datetime('now'))
);

INSERT OR IGNORE INTO users (username, password_hash, role) VALUES ('admin', '$2y$10$LmSqci180Ie25D1ELzmVxOtcbW3WAE2By3EJnRHBMB77Hq0U0HXWG', 'admin');
