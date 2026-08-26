<?php
require __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

function importSaveProduct($pdo, $data) {
    $id = $data['id'] ?? bin2hex(random_bytes(8));
    $stmt = $pdo->prepare('INSERT INTO products (id,name,location,price,url,images,drawers,shoe_rack,inner_storage,shelf,closures,size_type,dimensions,assembly,manual,assembly_place,is_new)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ON DUPLICATE KEY UPDATE name=VALUES(name),location=VALUES(location),price=VALUES(price),url=VALUES(url),images=VALUES(images),
        drawers=VALUES(drawers),shoe_rack=VALUES(shoe_rack),inner_storage=VALUES(inner_storage),shelf=VALUES(shelf),closures=VALUES(closures),
        size_type=VALUES(size_type),dimensions=VALUES(dimensions),assembly=VALUES(assembly),manual=VALUES(manual),assembly_place=VALUES(assembly_place),is_new=VALUES(is_new)');

    $stmt->execute([
        $id, $data['name'], $data['location'] ?? '', $data['price'] ?? 0, $data['url'] ?? '',
        json_encode($data['images'] ?? []), $data['drawers'] ?? 0,
        $data['shoeRack'] ?? 0, $data['innerStorage'] ?? 0, $data['shelf'] ?? 0,
        json_encode($data['closures'] ?? []), $data['sizeType'] ?? '', $data['dimensions'] ?? '',
        $data['assembly'] ?? '', $data['manual'] ?? 0, $data['assemblyPlace'] ?? '',
        $data['isNew'] ?? 1
    ]);

    $pdo->prepare('DELETE FROM product_colors WHERE product_id = ?')->execute([$id]);
    $ins = $pdo->prepare('INSERT INTO product_colors (product_id,hex,name) VALUES (?,?,?)');
    foreach ($data['colors'] ?? [] as $c) {
        $ins->execute([$id, $c['hex'], $c['name']]);
    }

    $pdo->prepare('DELETE FROM product_dynamic_features WHERE product_id = ?')->execute([$id]);
    $ins2 = $pdo->prepare('INSERT INTO product_dynamic_features (product_id,characteristic_name,value) VALUES (?,?,?)');
    foreach ($data['dynamicFeatures'] ?? [] as $k => $v) {
        $ins2->execute([$id, $k, $v ?? '']);
    }
}

if ($method === 'POST') {
    $products = $input['products'] ?? [];
    $chars = $input['customCharacteristics'] ?? [];

    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    $pdo->exec('TRUNCATE TABLE product_dynamic_features');
    $pdo->exec('TRUNCATE TABLE product_colors');
    $pdo->exec('TRUNCATE TABLE products');
    $pdo->exec('TRUNCATE TABLE custom_characteristics');
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

    $insChar = $pdo->prepare('INSERT INTO custom_characteristics (name,type,options) VALUES (?,?,?)');
    foreach ($chars as $c) {
        $insChar->execute([$c['name'], $c['type'], $c['options'] ?? null]);
    }

    foreach ($products as $p) {
        importSaveProduct($pdo, $p);
    }

    echo json_encode(['status' => 'ok', 'count' => count($products)]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'POST only']);
}
