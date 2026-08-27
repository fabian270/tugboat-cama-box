<?php
function getProducts($pdo) {
    $stmt = $pdo->query('SELECT * FROM products ORDER BY created_at DESC');
    $rows = $stmt->fetchAll();
    $products = [];

    foreach ($rows as $p) {
        $item = [
            'id' => $p['id'],
            'name' => $p['name'],
            'location' => $p['location'],
            'price' => (float)$p['price'],
            'url' => $p['url'],
            'images' => json_decode($p['images'] ?? '[]', true),
            'drawers' => (int)$p['drawers'],
            'shoeRack' => (bool)$p['shoe_rack'],
            'innerStorage' => (bool)$p['inner_storage'],
            'shelf' => (bool)$p['shelf'],
            'closures' => json_decode($p['closures'] ?? '[]', true),
            'sizeType' => $p['size_type'],
            'dimensions' => $p['dimensions'],
            'assembly' => $p['assembly'],
            'manual' => (bool)$p['manual'],
            'assemblyPlace' => $p['assembly_place'],
            'isNew' => (bool)$p['is_new'],
            'productType' => $p['product_type'] ?? '',
            'altPage' => $p['alt_page'] ?? '',
            'decision' => $p['decision'] ?? '',
            'colors' => [],
            'dynamicFeatures' => [],
        ];

        $colors = $pdo->prepare('SELECT hex, name FROM product_colors WHERE product_id = ?');
        $colors->execute([$p['id']]);
        $item['colors'] = $colors->fetchAll();

        $feat = $pdo->prepare('SELECT characteristic_name, value FROM product_dynamic_features WHERE product_id = ?');
        $feat->execute([$p['id']]);
        foreach ($feat->fetchAll() as $f) {
            $item['dynamicFeatures'][$f['characteristic_name']] = $f['value'];
        }

        $products[] = $item;
    }

    return $products;
}

function saveProduct($pdo, $data) {
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
