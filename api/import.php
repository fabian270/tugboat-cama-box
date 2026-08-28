<?php
require __DIR__ . '/config.php';
require __DIR__ . '/middleware.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

function importSaveProduct($pdo, $data) {
    $id = $data['id'] ?? bin2hex(random_bytes(8));
    $isSqlite = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite';

    $vals = [
        $id, $data['name'] ?? '', $data['location'] ?? '', $data['price'] ?? 0, $data['url'] ?? '',
        json_encode($data['images'] ?? []), (int)($data['drawers'] ?? 0),
        (int)($data['shoeRack'] ?? 0), (int)($data['innerStorage'] ?? 0), (int)($data['shelf'] ?? 0),
        json_encode($data['closures'] ?? []), $data['sizeType'] ?? '', $data['dimensions'] ?? '',
        $data['assembly'] ?? '', (int)($data['manual'] ?? 0), $data['assemblyPlace'] ?? '',
        (int)($data['isNew'] ?? 1), $data['productType'] ?? '', $data['altPage'] ?? '',
        $data['decision'] ?? ''
    ];

    if ($isSqlite) {
        $existing = $pdo->prepare('SELECT id FROM products WHERE id = ?');
        $existing->execute([$id]);
        if ($existing->fetch()) {
            $stmt = $pdo->prepare('UPDATE products SET name=?,location=?,price=?,url=?,images=?,drawers=?,shoe_rack=?,inner_storage=?,shelf=?,closures=?,size_type=?,dimensions=?,assembly=?,manual=?,assembly_place=?,is_new=?,product_type=?,alt_page=?,decision=? WHERE id=?');
            $upt = array_slice($vals, 1);
            $upt[] = $id;
            $stmt->execute($upt);
        } else {
            $stmt = $pdo->prepare('INSERT INTO products (id,name,location,price,url,images,drawers,shoe_rack,inner_storage,shelf,closures,size_type,dimensions,assembly,manual,assembly_place,is_new,product_type,alt_page,decision) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute($vals);
        }
    } else {
        $pdo->prepare('INSERT INTO products (id,name,location,price,url,images,drawers,shoe_rack,inner_storage,shelf,closures,size_type,dimensions,assembly,manual,assembly_place,is_new,product_type,alt_page,decision)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
            ON DUPLICATE KEY UPDATE name=VALUES(name),location=VALUES(location),price=VALUES(price),url=VALUES(url),images=VALUES(images),
            drawers=VALUES(drawers),shoe_rack=VALUES(shoe_rack),inner_storage=VALUES(inner_storage),shelf=VALUES(shelf),closures=VALUES(closures),
            size_type=VALUES(size_type),dimensions=VALUES(dimensions),assembly=VALUES(assembly),manual=VALUES(manual),assembly_place=VALUES(assembly_place),is_new=VALUES(is_new),product_type=VALUES(product_type),alt_page=VALUES(alt_page),decision=VALUES(decision)')->execute($vals);
    }

    $pdo->prepare('DELETE FROM product_colors WHERE product_id = ?')->execute([$id]);
    $ins = $pdo->prepare('INSERT INTO product_colors (product_id,hex,name) VALUES (?,?,?)');
    foreach ($data['colors'] ?? [] as $c) {
        $ins->execute([$id, $c['hex'] ?? '#000000', $c['name'] ?? '']);
    }

    $pdo->prepare('DELETE FROM product_dynamic_features WHERE product_id = ?')->execute([$id]);
    $ins2 = $pdo->prepare('INSERT INTO product_dynamic_features (product_id,characteristic_name,value) VALUES (?,?,?)');
    foreach ($data['dynamicFeatures'] ?? [] as $k => $v) {
        $ins2->execute([$id, $k, $v ?? '']);
    }
}

try {
    if ($method === 'POST') {
        requireAuth();
        $products = $input['products'] ?? [];
        $chars = $input['customCharacteristics'] ?? [];
        $types = $input['productTypes'] ?? [];
        $isSqlite = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite';

        if ($isSqlite) {
            $pdo->exec('DELETE FROM product_dynamic_features');
            $pdo->exec('DELETE FROM product_colors');
            $pdo->exec('DELETE FROM products');
            $pdo->exec('DELETE FROM custom_characteristics');
            $pdo->exec('DELETE FROM product_types');
        } else {
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
            $pdo->exec('TRUNCATE TABLE product_dynamic_features');
            $pdo->exec('TRUNCATE TABLE product_colors');
            $pdo->exec('TRUNCATE TABLE products');
            $pdo->exec('TRUNCATE TABLE custom_characteristics');
            $pdo->exec('TRUNCATE TABLE product_types');
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
        }

        $insChar = $pdo->prepare('INSERT INTO custom_characteristics (name,type,options) VALUES (?,?,?)');
        foreach ($chars as $c) {
            $insChar->execute([$c['name'], $c['type'], $c['options'] ?? null]);
        }

        $insType = $pdo->prepare('INSERT OR IGNORE INTO product_types (name) VALUES (?)');
        foreach ($types as $t) {
            if (!empty($t['name'])) $insType->execute([$t['name']]);
        }

        foreach ($products as $p) {
            importSaveProduct($pdo, $p);
        }

        echo json_encode(['status' => 'ok', 'count' => count($products)]);
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'POST only']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Import failed: ' . $e->getMessage()]);
}
