CREATE DATABASE IF NOT EXISTS camabox CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE camabox;

CREATE TABLE IF NOT EXISTS products (
    id VARCHAR(60) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255) DEFAULT '',
    price DECIMAL(12,2) DEFAULT 0,
    url VARCHAR(512) DEFAULT '',
    images JSON,
    drawers INT DEFAULT 0,
    shoe_rack TINYINT(1) DEFAULT 0,
    inner_storage TINYINT(1) DEFAULT 0,
    shelf TINYINT(1) DEFAULT 0,
    closures JSON,
    size_type VARCHAR(50) DEFAULT '',
    dimensions VARCHAR(100) DEFAULT '',
    assembly VARCHAR(30) DEFAULT '',
    manual TINYINT(1) DEFAULT 0,
    assembly_place VARCHAR(255) DEFAULT '',
    is_new TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS product_colors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(60) NOT NULL,
    hex VARCHAR(7) NOT NULL,
    name VARCHAR(100) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS custom_characteristics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    type VARCHAR(30) NOT NULL DEFAULT 'text',
    options TEXT DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS product_dynamic_features (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id VARCHAR(60) NOT NULL,
    characteristic_name VARCHAR(255) NOT NULL,
    value TEXT DEFAULT '',
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY uk_prod_char (product_id, characteristic_name)
) ENGINE=InnoDB;
