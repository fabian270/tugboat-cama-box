<?php
require __DIR__ . '/config.php';

function getProducts($pdo) {
    $stmt = $pdo->query('SELECT * FROM products ORDER BY created_at DESC');
    $products = $stmt->fetchAll();

    foreach ($products as &$p) {
        $p['images'] = json_decode($p['images'] ?? '[]', true);
        $p['closures'] = json_decode($p['closures'] ?? '[]', true);
        $p['drawers'] = (int)$p['drawers'];
        $p['price'] = (float)$p['price'];
        $p['shoe_rack'] = (bool)$p['shoe_rack'];
        $p['inner_storage'] = (bool)$p['inner_storage'];
        $p['shelf'] = (bool)$p['shelf'];
        $p['manual'] = (bool)$p['manual'];
        $p['is_new'] = (bool)$p['is_new'];

        $colors = $pdo->prepare('SELECT hex, name FROM product_colors WHERE product_id = ?');
        $colors->execute([$p['id']]);
        $p['colors'] = $colors->fetchAll();

        $feat = $pdo->prepare('SELECT characteristic_name, value FROM product_dynamic_features WHERE product_id = ?');
        $feat->execute([$p['id']]);
        $p['dynamic_features'] = [];
        foreach ($feat->fetchAll() as $f) {
            $p['dynamic_features'][$f['characteristic_name']] = $f['value'];
        }
    }

    return $products;
}

function saveProduct($pdo, $data) {
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

    return $id;
}

function deleteProduct($pdo, $id) {
    $pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
}

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

switch ($method) {
    case 'GET':
        echo json_encode(getProducts($pdo));
        break;
    case 'POST':
        if (!empty($input['name'])) {
            $id = saveProduct($pdo, $input);
            echo json_encode(['id' => $id, 'status' => 'ok']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'name required']);
        }
        break;
    case 'PUT':
        if (!empty($input['id'])) {
            saveProduct($pdo, $input);
            echo json_encode(['status' => 'ok']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'id required']);
        }
        break;
    case 'DELETE':
        $id = $_GET['id'] ?? '';
        if ($id) {
            deleteProduct($pdo, $id);
            echo json_encode(['status' => 'ok']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'id required']);
        }
        break;
}
