<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AppPro - Comparador de Productos</title>
    <style>
        :root {
            --primary: #1a1a2e;
            --primary-light: #16213e;
            --accent: #e94560;
            --accent-hover: #c73a52;
            --bg: #f0f2f5;
            --card-bg: #ffffff;
            --text: #333333;
            --text-light: #666666;
            --border: #e0e0e0;
            --success: #27ae60;
            --warning: #f39c12;
            --info: #3498db;
            --shadow: 0 2px 12px rgba(0,0,0,0.08);
            --shadow-hover: 0 6px 24px rgba(0,0,0,0.15);
            --radius: 12px;
            --radius-sm: 8px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }

        header {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }

        .header-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo { font-size: 1.8rem; font-weight: 800; letter-spacing: -0.5px; }
        .logo span { color: var(--accent); }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .search-bar {
            padding: 10px 18px;
            border: none;
            border-radius: 25px;
            width: 280px;
            font-size: 0.95rem;
            background: rgba(255,255,255,0.15);
            color: white;
            outline: none;
            transition: all 0.3s;
        }

        .search-bar::placeholder { color: rgba(255,255,255,0.6); }
        .search-bar:focus { background: rgba(255,255,255,0.25); width: 340px; }

        .btn {
            padding: 10px 22px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-accent { background: var(--accent); color: white; }
        .btn-accent:hover { background: var(--accent-hover); transform: translateY(-1px); box-shadow: 0 4px 15px rgba(233,69,96,0.4); }

        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-light); }

        .btn-outline {
            background: transparent;
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
        }
        .btn-outline:hover { background: rgba(255,255,255,0.1); }

        .btn-sm { padding: 6px 14px; font-size: 0.85rem; }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 32px;
        }

        .stats-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 20px 24px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }

        .stat-icon.products { background: #e8f5e9; }
        .stat-icon.prices { background: #e3f2fd; }
        .stat-icon.colors { background: #fce4ec; }

        .stat-value { font-size: 1.6rem; font-weight: 700; color: var(--primary); }
        .stat-label { font-size: 0.85rem; color: var(--text-light); }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .toolbar-left { display: flex; gap: 8px; flex-wrap: wrap; }

        .view-toggle {
            display: flex;
            background: var(--card-bg);
            border-radius: var(--radius-sm);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .view-toggle button {
            padding: 8px 16px; border: none; background: transparent;
            cursor: pointer; font-size: 0.9rem; transition: all 0.3s;
        }

        .view-toggle button.active { background: var(--primary); color: white; }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
        }

        .product-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s;
            position: relative;
        }

        .product-card:hover { box-shadow: var(--shadow-hover); transform: translateY(-4px); }

        .product-card .badge {
            position: absolute; top: 12px; left: 12px;
            background: var(--info); color: white;
            padding: 4px 12px; border-radius: 20px;
            font-size: 0.8rem; font-weight: 600; z-index: 2;
        }

        .product-image-container {
            position: relative; height: 220px; background: #f8f9fa; overflow: hidden;
        }

        .product-image-container img {
            width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;
        }

        .product-card:hover .product-image-container img { transform: scale(1.05); }

        .image-nav {
            position: absolute; bottom: 8px; left: 50%; transform: translateX(-50%);
            display: flex; gap: 6px;
        }

        .image-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: rgba(255,255,255,0.5); border: none; cursor: pointer; transition: all 0.3s;
        }

        .image-dot.active { background: white; width: 20px; border-radius: 4px; }

        .product-info { padding: 20px; }

        .product-name { font-size: 1.15rem; font-weight: 700; margin-bottom: 4px; color: var(--primary); }
        .product-location { font-size: 0.85rem; color: var(--text-light); margin-bottom: 12px; }

        .product-price {
            font-size: 1.5rem; font-weight: 800; color: var(--accent); margin-bottom: 12px;
        }

        .product-specs { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px; }

        .spec-tag {
            background: #f0f2f5; padding: 4px 10px; border-radius: 20px;
            font-size: 0.78rem; color: var(--text-light);
        }

        .product-footer {
            padding: 12px 20px; border-top: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
        }

        .product-footer .color-dots { display: flex; gap: 4px; }

        .color-dot {
            width: 18px; height: 18px; border-radius: 50%;
            border: 2px solid white; box-shadow: 0 0 0 1px var(--border);
        }

        .compare-check {
            display: flex; align-items: center; gap: 6px;
            font-size: 0.85rem; color: var(--text-light); cursor: pointer;
        }

        .compare-check input { accent-color: var(--accent); width: 16px; height: 16px; }

        .table-container {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow-x: auto;
            display: none;
        }

        .comparison-table {
            width: 100%; border-collapse: collapse; min-width: 900px;
        }

        .comparison-table th {
            background: var(--primary); color: white; padding: 14px 16px;
            text-align: left; font-weight: 600; font-size: 0.9rem;
            position: sticky; top: 0; white-space: nowrap;
        }

        .comparison-table td {
            padding: 12px 16px; border-bottom: 1px solid var(--border);
            font-size: 0.9rem; vertical-align: middle;
        }

        .comparison-table tr:hover td { background: #f8f9ff; }

        .comparison-table .product-thumb {
            width: 60px; height: 60px; border-radius: var(--radius-sm); object-fit: cover;
        }

        .table-cell-colors { display: flex; gap: 4px; flex-wrap: wrap; }

        .table-color-dot {
            width: 20px; height: 20px; border-radius: 50%;
            border: 2px solid white; box-shadow: 0 0 0 1px var(--border);
        }

        .check-yes { color: var(--success); font-weight: bold; }
        .check-no { color: #ccc; }

        .compare-panel {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: var(--primary); color: white;
            padding: 16px 32px; display: none;
            align-items: center; justify-content: space-between;
            z-index: 150; box-shadow: 0 -4px 20px rgba(0,0,0,0.3);
        }

        .compare-panel.active { display: flex; }
        .compare-panel .selected-count { font-weight: 600; font-size: 1rem; }

        .modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);
            z-index: 200; display: none;
            align-items: center; justify-content: center; padding: 20px;
        }

        .modal-overlay.active { display: flex; }

        .modal {
            background: var(--card-bg); border-radius: var(--radius);
            width: 100%; max-width: 1000px; max-height: 90vh; overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .modal-header {
            padding: 24px 28px; border-bottom: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; background: var(--card-bg); z-index: 1;
        }

        .modal-header h2 { font-size: 1.3rem; color: var(--primary); }

        .modal-close {
            width: 36px; height: 36px; border-radius: 50%; border: none;
            background: var(--bg); cursor: pointer; font-size: 1.2rem;
            display: flex; align-items: center; justify-content: center; transition: all 0.3s;
        }

        .modal-close:hover { background: var(--accent); color: white; }

        .detail-section { margin-bottom: 20px; }
        .detail-section h3 { font-size: 0.9rem; font-weight: 700; color: var(--accent); margin-bottom: 8px; padding-bottom: 4px; border-bottom: 1px solid var(--border); }
        .detail-row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #f5f5f5; font-size: 0.9rem; }
        .detail-row .label { color: var(--text-light); }
        .detail-row .value { font-weight: 600; color: var(--text); text-align: right; max-width: 60%; }
        .detail-images { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 8px; }
        .detail-images img { width: 100px; height: 80px; border-radius: var(--radius-sm); object-fit: cover; border: 2px solid var(--border); }
        .detail-colors { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 4px; }
        .detail-colors .color-chip { display: flex; align-items: center; gap: 6px; padding: 4px 10px; background: var(--bg); border-radius: 20px; font-size: 0.85rem; }
        .detail-colors .color-chip span.dot { width: 16px; height: 16px; border-radius: 50%; border: 1px solid var(--border); display: inline-block; }
        .detail-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 4px; }
        .detail-tags .spec-tag { background: #f0f2f5; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; color: var(--text-light); }
        .btn-detail { background: var(--info); color: white; border: none; padding: 5px 12px; border-radius: 20px; cursor: pointer; font-size: 0.8rem; font-weight: 600; transition: all 0.2s; }
        .btn-detail:hover { background: #2980b9; }

        .empty-state { text-align: center; padding: 80px 20px; }
        .empty-state .empty-icon { font-size: 4rem; margin-bottom: 16px; }
        .empty-state h3 { font-size: 1.4rem; color: var(--primary); margin-bottom: 8px; }
        .empty-state p { color: var(--text-light); margin-bottom: 24px; }

        .toast-container {
            position: fixed; top: 80px; right: 20px; z-index: 300;
            display: flex; flex-direction: column; gap: 8px;
        }

        .toast {
            background: var(--primary); color: white;
            padding: 12px 20px; border-radius: var(--radius-sm);
            box-shadow: var(--shadow-hover); animation: slideIn 0.3s ease;
            display: flex; align-items: center; gap: 10px; font-size: 0.9rem;
        }

        .toast.success { border-left: 4px solid var(--success); }
        .toast.error { border-left: 4px solid var(--accent); }
        .toast.info { border-left: 4px solid var(--info); }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @media (max-width: 768px) {
            .header-inner { padding: 12px 16px; flex-wrap: wrap; gap: 12px; }
            .search-bar { width: 100%; order: 3; }
            .search-bar:focus { width: 100%; }
            .main-container { padding: 16px; }
            .products-grid { grid-template-columns: 1fr; }
            .modal { max-width: 100%; margin: 10px; }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-inner">
            <div class="logo">App<span>Pro</span></div>
            <div class="header-actions">
                <input type="text" class="search-bar" id="searchInput" placeholder="Buscar productos...">
                
                <a href="admin.php" class="btn btn-accent" style="text-decoration:none">⚙ Administrar</a>
            </div>
        </div>
    </header>

    <div class="main-container">
        <div class="stats-bar" id="statsBar">
            <div class="stat-card">
                <div class="stat-icon products">🛏</div>
                <div>
                    <div class="stat-value" id="statProducts">0</div>
                    <div class="stat-label">Productos</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon prices">💰</div>
                <div>
                    <div class="stat-value" id="statAvgPrice">$0</div>
                    <div class="stat-label">Precio Promedio</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon colors">🎨</div>
                <div>
                    <div class="stat-value" id="statColors">0</div>
                    <div class="stat-label">Colores Totales</div>
                </div>
            </div>
        </div>

        <div class="toolbar">
            <div class="toolbar-left">
                <div class="view-toggle">
                    <button class="active" id="viewGrid" onclick="setView('grid')">▦ Cuadricula</button>
                    <button id="viewTable" onclick="setView('table')">☰ Tabla</button>
                </div>
            </div>
        </div>

        <div id="gridView" class="products-grid"></div>
        <div id="tableView" class="table-container"></div>
    </div>

    <div class="compare-panel" id="comparePanel">
        <span class="selected-count"><span id="compareCount">0</span> productos seleccionados</span>
        <div>
            <button class="btn btn-accent" onclick="showComparison()">Comparar Ahora</button>
            <button class="btn btn-sm" style="background:rgba(255,255,255,0.2);color:white;margin-left:8px" onclick="clearCompare()">Limpiar</button>
        </div>
    </div>

    <!-- DETAIL MODAL -->
    <div class="modal-overlay" id="detailModal">
        <div class="modal" style="max-width:640px">
            <div class="modal-header">
                <h2 id="detailTitle">Detalle del Producto</h2>
                <button class="modal-close" onclick="closeDetailModal()">✕</button>
            </div>
            <div class="modal-body" id="detailBody"></div>
        </div>
    </div>

    <div class="modal-overlay" id="compareModal">
        <div class="modal" style="max-width:1000px">
            <div class="modal-header">
                <h2>Tabla Comparativa</h2>
                <button class="modal-close" onclick="closeCompareModal()">✕</button>
            </div>
            <div class="modal-body" style="padding:0;overflow-x:auto">
                <div id="comparisonContent"></div>
            </div>
        </div>
    </div>

    <div class="toast-container" id="toastContainer"></div>

    <script>
        const API_BASE = '/api';
        let products = [];
        let customCharacteristics = [];
        let compareList = [];
        let currentView = 'grid';
        let currentImageIndices = {};

        function init() {
            loadData();
            setupSearch();
        }

        async function apiCall(endpoint, method, body) {
            const opts = { method: method || 'GET', headers: {} };
            if (body) { opts.headers['Content-Type'] = 'application/json'; opts.body = JSON.stringify(body); }
            try {
                const res = await fetch(`${API_BASE}/${endpoint}`, opts);
                return await res.json();
            } catch(e) { return null; }
        }

        async function loadData() {
            const [prods, chars] = await Promise.all([
                apiCall('products.php'),
                apiCall('characteristics.php')
            ]);
            if (prods !== null) products = prods;
            if (chars !== null) customCharacteristics = chars;
            render();
        }

        function render() {
            renderGrid();
            renderTable();
            updateStats();
            updateComparePanel();
        }

        function getFilteredProducts() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            return products.filter(p => {
                return p.name.toLowerCase().includes(query) ||
                       (p.location || '').toLowerCase().includes(query) ||
                       (p.sizeType || '').toLowerCase().includes(query);
            });
        }

        function renderGrid() {
            const filtered = getFilteredProducts();
            const grid = document.getElementById('gridView');

            if (filtered.length === 0) {
                grid.innerHTML = `
                    <div class="empty-state" style="grid-column:1/-1">
                        <div class="empty-icon">🛏</div>
                        <h3>No hay productos</h3>
                        <p>Aguarda a que un administrador cargue productos.</p>
                    </div>`;
                return;
            }

            const assemblyLabels = { assembled: 'Armado', unassembled: 'Sin Armar', easy: 'Facil', hard: 'Dificil' };

            grid.innerHTML = filtered.map(p => {
                const imgIdx = currentImageIndices[p.id] || 0;
                const mainImg = p.images && p.images.length > 0 ? p.images[imgIdx] : '';

                return `
                <div class="product-card">
                    ${p.productType ? `<span class="badge">${p.productType}</span>` : ''}
                    <div class="product-image-container">
                        ${mainImg
                            ? `<img src="${mainImg}" alt="${p.name}" onerror="this.parentElement.innerHTML='<div style=\\'display:flex;align-items:center;justify-content:center;height:100%;font-size:3rem;color:#ccc\\'>🛏</div>'">`
                            : '<div style="display:flex;align-items:center;justify-content:center;height:100%;font-size:3rem;color:#ccc">🛏</div>'
                        }
                        ${p.images && p.images.length > 1 ? `
                            <div class="image-nav">
                                ${p.images.map((_, i) => `
                                    <button class="image-dot ${i === imgIdx ? 'active' : ''}"
                                        onclick="event.stopPropagation();switchImage('${p.id}',${i})"></button>
                                `).join('')}
                            </div>
                        ` : ''}
                    </div>
                    <div class="product-info">
                        <div class="product-name">${p.name}</div>
                        <div class="product-location">${p.location || 'Sin ubicacion'}</div>
                        ${p.url ? `<div class="product-location"><a href="${p.url}" target="_blank" style="color:var(--info);text-decoration:none;font-size:0.85rem">Ver sitio web</a></div>` : ''}
                        ${p.altPage ? `<div class="product-location"><a href="${p.altPage}" target="_blank" style="color:var(--warning);text-decoration:none;font-size:0.85rem">Pagina alternativa</a></div>` : ''}
                        ${p.decision ? `<div class="product-location" style="color:var(--accent);font-weight:600">Decision: ${p.decision}</div>` : ''}
                        <div class="product-price">$${Number(p.price || 0).toLocaleString('es-AR')}</div>
                        <div class="product-specs">
                            ${p.productType ? `<span class="spec-tag">${p.productType}</span>` : ''}
                            ${p.sizeType ? `<span class="spec-tag">${p.sizeType}</span>` : ''}
                            ${p.drawers > 0 ? `<span class="spec-tag">${p.drawers} cajon${p.drawers > 1 ? 'es' : ''}</span>` : ''}
                            ${p.shoeRack ? '<span class="spec-tag">Zapatero</span>' : ''}
                            ${p.innerStorage ? '<span class="spec-tag">Guardado interior</span>' : ''}
                            ${p.shelf ? '<span class="spec-tag">Estante</span>' : ''}
                            ${p.closures && p.closures.length > 0 ? `<span class="spec-tag">${p.closures.join(', ')}</span>` : ''}
                            ${p.assembly ? `<span class="spec-tag">${assemblyLabels[p.assembly] || p.assembly}</span>` : ''}
                            ${p.manual ? '<span class="spec-tag">Instructivo</span>' : ''}
                        </div>
                        <a href="producto.php?id=${p.id}" style="display:inline-block;margin-top:12px;padding:8px 20px;background:var(--accent);color:white;border-radius:25px;text-decoration:none;font-size:0.85rem;font-weight:600;transition:all 0.3s">Ver producto</a>
                    </div>
                    <div class="product-footer">
                        <div class="color-dots">
                            ${(p.colors || []).map(c => `
                                <span class="color-dot" style="background:${c.hex}" title="${c.name}"></span>
                            `).join('')}
                        </div>
                        <label class="compare-check">
                            <input type="checkbox" ${compareList.includes(p.id) ? 'checked' : ''}
                                onchange="toggleCompare('${p.id}')"> Comparar
                        </label>
                    </div>
                </div>`;
            }).join('');
        }

        function renderDynamicSpecs(p) {
            if (!p.dynamicFeatures || Object.keys(p.dynamicFeatures).length === 0) return '';
            return `<div class="product-specs" style="margin-top:4px">
                ${Object.entries(p.dynamicFeatures).map(([key, val]) => {
                    if (val === '' || val === null || val === undefined) return '';
                    if (val === true || val === 'true') return `<span class="spec-tag">${key}</span>`;
                    if (val === false || val === 'false') return '';
                    return `<span class="spec-tag">${key}: ${val}</span>`;
                }).join('')}
            </div>`;
        }

        function renderTable() {
            const filtered = getFilteredProducts();
            const container = document.getElementById('tableView');
            if (filtered.length === 0) { container.innerHTML = ''; return; }

            const assemblyLabels = { assembled: 'Armado', unassembled: 'Sin Armar', easy: 'Facil', hard: 'Dificil' };

            let headers = ['Foto', 'Nombre', 'Precio', 'Lugar', 'Tipo', 'Pag. Alt.', 'Decision', 'Tamaño', 'Medidas', 'Colores', 'Cajones', 'Zapatero', 'Guard. Interior', 'Estante', 'Cierre', 'Armado', 'Instructivo', 'Lugar Armado'];

            let rows = filtered.map(p => {
                let cells = [
                    `<img class="product-thumb" src="${(p.images && p.images[0]) || ''}" onerror="this.style.display='none'" alt="">`,
                    `<strong>${p.name}</strong>`,
                    `<span style="color:var(--accent);font-weight:700">$${Number(p.price||0).toLocaleString('es-AR')}</span>`,
                    p.location || '-',
                    p.productType || '-',
                    p.altPage ? `<a href="${p.altPage}" target="_blank" style="color:var(--warning)" title="${p.altPage}">Pagina</a>` : '-',
                    p.decision || '-',
                    p.sizeType || '-',
                    p.dimensions || '-',
                    `<div class="table-cell-colors">${(p.colors||[]).map(c => `<span class="table-color-dot" style="background:${c.hex}" title="${c.name}"></span>`).join('')}</div>`,
                    p.drawers || 0,
                    p.shoeRack ? '<span class="check-yes">✓</span>' : '<span class="check-no">—</span>',
                    p.innerStorage ? '<span class="check-yes">✓</span>' : '<span class="check-no">—</span>',
                    p.shelf ? '<span class="check-yes">✓</span>' : '<span class="check-no">—</span>',
                    (p.closures || []).join(', ') || '-',
                    assemblyLabels[p.assembly] || '-',
                    p.manual ? '<span class="check-yes">✓</span>' : '<span class="check-no">—</span>',
                    p.assemblyPlace || '-'
                ];

                return `<tr>${cells.map(c => `<td>${c}</td>`).join('')}</tr>`;
            });

            container.innerHTML = `
                <table class="comparison-table">
                    <thead><tr>${headers.map(h => `<th>${h}</th>`).join('')}</tr></thead>
                    <tbody>${rows.join('')}</tbody>
                </table>`;
        }

        function updateStats() {
            document.getElementById('statProducts').textContent = products.length;
            const avg = products.length > 0
                ? Math.round(products.reduce((s, p) => s + Number(p.price || 0), 0) / products.length)
                : 0;
            document.getElementById('statAvgPrice').textContent = `$${avg.toLocaleString('es-AR')}`;
            const allColors = new Set();
            products.forEach(p => (p.colors || []).forEach(c => allColors.add(c.name)));
            document.getElementById('statColors').textContent = allColors.size;
        }

        function setView(view) {
            currentView = view;
            document.getElementById('viewGrid').classList.toggle('active', view === 'grid');
            document.getElementById('viewTable').classList.toggle('active', view === 'table');
            document.getElementById('gridView').style.display = view === 'grid' ? 'grid' : 'none';
            document.getElementById('tableView').style.display = view === 'table' ? 'block' : 'none';
        }

        function toggleCompare(id) {
            if (compareList.includes(id)) {
                compareList = compareList.filter(c => c !== id);
            } else {
                compareList.push(id);
            }
            updateComparePanel();
            renderGrid();
        }

        function updateComparePanel() {
            const panel = document.getElementById('comparePanel');
            document.getElementById('compareCount').textContent = compareList.length;
            panel.classList.toggle('active', compareList.length >= 2);
        }

        function clearCompare() {
            compareList = [];
            updateComparePanel();
            renderGrid();
        }

        function showComparison() {
            const selected = products.filter(p => compareList.includes(p.id));
            if (selected.length < 2) { return; }

            const assemblyLabels = { assembled: 'Armado', unassembled: 'Sin Armar', easy: 'Facil', hard: 'Dificil' };
            const allChars = customCharacteristics.map(c => c.name);

            let html = `<div style="padding:20px">
                <table class="comparison-table">
                    <thead><tr>
                        <th>Caracteristica</th>
                        ${selected.map(p => `<th>${p.name}</th>`).join('')}
                    </tr></thead>
                    <tbody>
                        <tr><td><strong>Foto</strong></td>${selected.map(p => `<td>${p.images && p.images[0] ? `<img src="${p.images[0]}" style="width:100px;height:80px;object-fit:cover;border-radius:8px">` : '🛏'}</td>`).join('')}</tr>
                        <tr><td><strong>Precio</strong></td>${selected.map(p => `<td style="color:var(--accent);font-weight:700;font-size:1.1rem">$${Number(p.price||0).toLocaleString('es-AR')}</td>`).join('')}</tr>
                        <tr><td><strong>Ubicacion</strong></td>${selected.map(p => `<td>${p.location || '-'}</td>`).join('')}</tr>
                        <tr><td><strong>Tipo</strong></td>${selected.map(p => `<td>${p.productType || '-'}</td>`).join('')}</tr>
                        <tr><td><strong>Pag. Alternativa</strong></td>${selected.map(p => `<td>${p.altPage ? `<a href="${p.altPage}" target="_blank" style="color:var(--warning)">Abrir</a>` : '-'}</td>`).join('')}</tr>
                        <tr><td><strong>Decision</strong></td>${selected.map(p => `<td>${p.decision || '-'}</td>`).join('')}</tr>
                        <tr><td><strong>Tamaño</strong></td>${selected.map(p => `<td>${p.sizeType || '-'}</td>`).join('')}</tr>
                        <tr><td><strong>Medidas</strong></td>${selected.map(p => `<td>${p.dimensions || '-'}</td>`).join('')}</tr>
                        <tr><td><strong>Colores</strong></td>${selected.map(p => `<td><div class="table-cell-colors">${(p.colors||[]).map(c => `<span class="table-color-dot" style="background:${c.hex}" title="${c.name}"></span>`).join('')}</div></td>`).join('')}</tr>
                        <tr><td><strong>Cajones</strong></td>${selected.map(p => `<td>${p.drawers || 0}</td>`).join('')}</tr>
                        <tr><td><strong>Zapatero</strong></td>${selected.map(p => `<td>${p.shoeRack ? '<span class="check-yes">✓</span>' : '<span class="check-no">—</span>'}</td>`).join('')}</tr>
                        <tr><td><strong>Guardado Interior</strong></td>${selected.map(p => `<td>${p.innerStorage ? '<span class="check-yes">✓</span>' : '<span class="check-no">—</span>'}</td>`).join('')}</tr>
                        <tr><td><strong>Estante</strong></td>${selected.map(p => `<td>${p.shelf ? '<span class="check-yes">✓</span>' : '<span class="check-no">—</span>'}</td>`).join('')}</tr>
                        <tr><td><strong>Cierre</strong></td>${selected.map(p => `<td>${(p.closures||[]).join(', ') || '-'}</td>`).join('')}</tr>
                        <tr><td><strong>Armado</strong></td>${selected.map(p => `<td>${assemblyLabels[p.assembly] || '-'}</td>`).join('')}</tr>
                        <tr><td><strong>Instructivo</strong></td>${selected.map(p => `<td>${p.manual ? '<span class="check-yes">✓</span>' : '<span class="check-no">—</span>'}</td>`).join('')}</tr>
                        <tr><td><strong>Lugar Armado</strong></td>${selected.map(p => `<td>${p.assemblyPlace || '-'}</td>`).join('')}</tr>
                        ${allChars.map(char => `<tr><td><strong>${char}</strong></td>${selected.map(p => {
                            const v = p.dynamicFeatures && p.dynamicFeatures[char];
                            if (v === true || v === 'true') return '<td><span class="check-yes">✓</span></td>';
                            if (v === false || v === 'false') return '<td><span class="check-no">—</span></td>';
                            return `<td>${v || '-'}</td>`;
                        }).join('')}</tr>`).join('')}
                    </tbody>
                </table>
            </div>`;

            document.getElementById('comparisonContent').innerHTML = html;
            document.getElementById('compareModal').classList.add('active');
        }

        function closeCompareModal() { document.getElementById('compareModal').classList.remove('active'); }

        function showDetail(id) {
            const p = products.find(x => x.id === id);
            if (!p) return;

            const assemblyLabels = { assembled: 'Armado', unassembled: 'Sin Armar', easy: 'Facil', hard: 'Dificil' };
            document.getElementById('detailTitle').textContent = p.name;

            let html = '';

            if (p.images && p.images.length > 0) {
                html += `<div class="detail-section"><h3>Fotos</h3><div class="detail-images">${p.images.map(img => `<img src="${img}" alt="">`).join('')}</div></div>`;
            }

            html += `<div class="detail-section"><h3>Informacion General</h3>
                <div class="detail-row"><span class="label">Nombre</span><span class="value">${p.name}</span></div>
                <div class="detail-row"><span class="label">Ubicacion</span><span class="value">${p.location || '-'}</span></div>
                <div class="detail-row"><span class="label">Tipo</span><span class="value">${p.productType || '-'}</span></div>
                <div class="detail-row"><span class="label">Precio</span><span class="value" style="color:var(--accent)">$${Number(p.price||0).toLocaleString('es-AR')}</span></div>
                ${p.url ? `<div class="detail-row"><span class="label">Sitio Web</span><span class="value"><a href="${p.url}" target="_blank" style="color:var(--info)">Abrir enlace</a></span></div>` : ''}
                ${p.altPage ? `<div class="detail-row"><span class="label">Pag. Alternativa</span><span class="value"><a href="${p.altPage}" target="_blank" style="color:var(--warning)">Abrir enlace</a></span></div>` : ''}
                ${p.decision ? `<div class="detail-row"><span class="label">Decision</span><span class="value">${p.decision}</span></div>` : ''}
            </div>`;

            if (p.colors && p.colors.length > 0) {
                html += `<div class="detail-section"><h3>Colores</h3><div class="detail-colors">${p.colors.map(c => `<span class="color-chip"><span class="dot" style="background:${c.hex}"></span>${c.name}</span>`).join('')}</div></div>`;
            }

            const storageTags = [];
            if (p.drawers > 0) storageTags.push(`${p.drawers} cajon${p.drawers > 1 ? 'es' : ''}`);
            if (p.shoeRack) storageTags.push('Zapatero');
            if (p.innerStorage) storageTags.push('Guardado interior');
            if (p.shelf) storageTags.push('Estante');
            if (storageTags.length > 0) {
                html += `<div class="detail-section"><h3>Espacios de Guardado</h3><div class="detail-tags">${storageTags.map(t => `<span class="spec-tag">${t}</span>`).join('')}</div></div>`;
            }

            if (p.closures && p.closures.length > 0) {
                html += `<div class="detail-section"><h3>Tipo de Cierre</h3><div class="detail-tags">${p.closures.map(c => `<span class="spec-tag">${c}</span>`).join('')}</div></div>`;
            }

            html += `<div class="detail-section"><h3>Tamano y Medidas</h3>
                <div class="detail-row"><span class="label">Tipo de Cama</span><span class="value">${p.sizeType || '-'}</span></div>
                <div class="detail-row"><span class="label">Medidas</span><span class="value">${p.dimensions || '-'}</span></div>
            </div>`;

            html += `<div class="detail-section"><h3>Armado</h3>
                <div class="detail-row"><span class="label">Estado</span><span class="value">${assemblyLabels[p.assembly] || '-'}</span></div>
                <div class="detail-row"><span class="label">Instructivo</span><span class="value">${p.manual ? 'Si' : 'No'}</span></div>
                <div class="detail-row"><span class="label">Lugar de Armado</span><span class="value">${p.assemblyPlace || '-'}</span></div>
            </div>`;

            if (p.dynamicFeatures && Object.keys(p.dynamicFeatures).length > 0) {
                const charEntries = Object.entries(p.dynamicFeatures).filter(([k, v]) => v !== '' && v !== null && v !== undefined);
                if (charEntries.length > 0) {
                    html += `<div class="detail-section"><h3>Caracteristicas Adicionales</h3>
                        ${charEntries.map(([k, v]) => {
                            const display = (v === true || v === 'true') ? 'Si' : (v === false || v === 'false') ? 'No' : v;
                            return `<div class="detail-row"><span class="label">${k}</span><span class="value">${display}</span></div>`;
                        }).join('')}
                    </div>`;
                }
            }

            document.getElementById('detailBody').innerHTML = html;
            document.getElementById('detailModal').classList.add('active');
        }

        function closeDetailModal() { document.getElementById('detailModal').classList.remove('active'); }

        function switchImage(productId, idx) {
            currentImageIndices[productId] = idx;
            renderGrid();
        }

        async function exportData() {
            const data = await apiCall('export.php');
            if (!data) return;
            const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `apppro_backup_${new Date().toISOString().slice(0,10)}.json`;
            a.click();
            URL.revokeObjectURL(url);
        }

        function setupSearch() {
            document.getElementById('searchInput').addEventListener('input', () => {
                renderGrid();
                renderTable();
            });
        }

        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            const icons = { success: '✓', error: '✕', info: 'ℹ' };
            toast.innerHTML = `<span>${icons[type] || 'ℹ'}</span> ${message}`;
            container.appendChild(toast);
            setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeDetailModal();
                closeCompareModal();
            }
        });
        document.getElementById('detailModal').addEventListener('click', e => {
            if (e.target === e.currentTarget) closeDetailModal();
        });
        document.getElementById('compareModal').addEventListener('click', e => {
            if (e.target === e.currentTarget) closeCompareModal();
        });

        init();
    </script>
</body>
</html>
