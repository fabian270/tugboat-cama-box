<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto - AppPro</title>
    <style>
        :root {
            --primary: #1a1a2e; --primary-light: #16213e;
            --accent: #e94560; --accent-hover: #c73a52;
            --bg: #f0f2f5; --card-bg: #ffffff;
            --text: #333333; --text-light: #666666; --border: #e0e0e0;
            --success: #27ae60; --info: #3498db;
            --shadow: 0 2px 12px rgba(0,0,0,0.08);
            --radius: 12px; --radius-sm: 8px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--bg); color: var(--text); line-height: 1.6; }

        header { background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 20px rgba(0,0,0,0.3); }
        .header-inner { max-width: 1400px; margin: 0 auto; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.8rem; font-weight: 800; }
        .logo span { color: var(--accent); }
        .btn { padding: 10px 22px; border: none; border-radius: 25px; cursor: pointer; font-size: 0.95rem; font-weight: 600; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; }
        .btn-outline { background: transparent; border: 2px solid rgba(255,255,255,0.3); color: white; }
        .btn-outline:hover { background: rgba(255,255,255,0.1); }
        .btn-accent { background: var(--accent); color: white; }
        .btn-accent:hover { background: var(--accent-hover); }

        .main-container { max-width: 900px; margin: 0 auto; padding: 32px; }

        .product-hero {
            background: var(--card-bg); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); margin-bottom: 24px;
        }

        .hero-gallery {
            position: relative; height: 400px; background: #f8f9fa; overflow: hidden;
        }

        .hero-gallery img { width: 100%; height: 100%; object-fit: cover; }
        .hero-gallery .placeholder { display: flex; align-items: center; justify-content: center; height: 100%; font-size: 5rem; color: #ccc; }

        .gallery-nav {
            position: absolute; bottom: 12px; left: 50%; transform: translateX(-50%);
            display: flex; gap: 8px;
        }

        .gallery-dot {
            width: 10px; height: 10px; border-radius: 50%; background: rgba(255,255,255,0.5);
            border: none; cursor: pointer; transition: all 0.3s;
        }
        .gallery-dot.active { background: white; width: 24px; border-radius: 5px; }

        .hero-info { padding: 32px; }
        .hero-info .badge { display: inline-block; background: var(--accent); color: white; padding: 4px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; margin-bottom: 12px; }
        .hero-info h1 { font-size: 1.8rem; color: var(--primary); margin-bottom: 8px; }
        .hero-info .location { color: var(--text-light); margin-bottom: 4px; }
        .hero-info .url a { color: var(--info); text-decoration: none; font-size: 0.9rem; }
        .hero-info .price { font-size: 2.2rem; font-weight: 800; color: var(--accent); margin: 16px 0; }

        .detail-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 24px;
        }

        .detail-card {
            background: var(--card-bg); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow);
        }

        .detail-card h3 {
            font-size: 1rem; font-weight: 700; color: var(--accent); margin-bottom: 16px;
            padding-bottom: 8px; border-bottom: 2px solid var(--border); display: inline-block;
        }

        .detail-row {
            display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f5f5f5; font-size: 0.95rem;
        }

        .detail-row .label { color: var(--text-light); }
        .detail-row .value { font-weight: 600; color: var(--text); text-align: right; max-width: 60%; }

        .detail-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }
        .detail-tags .tag { background: #f0f2f5; padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; color: var(--text-light); }

        .color-list { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 8px; }
        .color-chip { display: flex; align-items: center; gap: 8px; padding: 6px 14px; background: var(--bg); border-radius: 20px; font-size: 0.85rem; }
        .color-chip .dot { width: 18px; height: 18px; border-radius: 50%; border: 1px solid var(--border); display: inline-block; }

        .full-width { grid-column: 1 / -1; }

        .not-found { text-align: center; padding: 80px 20px; }
        .not-found h2 { font-size: 1.5rem; color: var(--primary); margin-bottom: 8px; }
        .not-found p { color: var(--text-light); margin-bottom: 24px; }

        @media (max-width: 768px) {
            .header-inner { padding: 12px 16px; }
            .main-container { padding: 16px; }
            .hero-gallery { height: 260px; }
            .detail-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-inner">
            <a href="index.php" class="logo" style="text-decoration:none;color:white">App<span>Pro</span></a>
            <a href="index.php" class="btn btn-outline">← Volver al Comparador</a>
        </div>
    </header>

    <div class="main-container" id="content">
        <div style="text-align:center;padding:60px;color:var(--text-light)">Cargando...</div>
    </div>

    <script>
        const API_BASE = '/api';
        let product = null;
        let customCharacteristics = [];
        let currentImgIdx = 0;

        async function init() {
            const params = new URLSearchParams(window.location.search);
            const id = params.get('id');
            if (!id) { showNotFound(); return; }

            const [prods, chars] = await Promise.all([
                fetch(`${API_BASE}/products.php`).then(r => r.json()).catch(() => null),
                fetch(`${API_BASE}/characteristics.php`).then(r => r.json()).catch(() => null)
            ]);

            if (chars) customCharacteristics = chars;
            if (prods) product = prods.find(p => p.id === id);

            if (!product) { showNotFound(); return; }
            document.title = `${product.name} - AppPro`;
            render();
        }

        function showNotFound() {
            document.getElementById('content').innerHTML = `
                <div class="not-found">
                    <h2>Producto no encontrado</h2>
                    <p>El producto que buscas no existe o fue eliminado.</p>
                    <a href="index.php" class="btn btn-accent" style="text-decoration:none">Volver al Comparador</a>
                </div>`;
        }

        function render() {
            const p = product;
            const assemblyLabels = { assembled: 'Armado', unassembled: 'Sin Armar', easy: 'Facil', hard: 'Dificil' };
            const hasImages = p.images && p.images.length > 0;

            let html = `<div class="product-hero">
                <div class="hero-gallery">
                    ${hasImages
                        ? `<img id="heroImg" src="${p.images[0]}" alt="${p.name}" onerror="this.outerHTML='<div class=\\'placeholder\\'>🛏</div>'">`
                        : '<div class="placeholder">🛏'
                    }
                    ${hasImages && p.images.length > 1 ? `
                        <div class="gallery-nav">
                            ${p.images.map((_, i) => `<button class="gallery-dot ${i === 0 ? 'active' : ''}" onclick="switchImg(${i})"></button>`).join('')}
                        </div>
                    ` : ''}
                </div>
                <div class="hero-info">
                    ${p.isNew ? '<span class="badge">Nuevo</span>' : ''}
                    <h1>${p.name}</h1>
                    <div class="location">${p.location || 'Sin ubicacion'}</div>
                    ${p.url ? `<div class="url"><a href="${p.url}" target="_blank">Ver sitio web</a></div>` : ''}
                    <div class="price">$${Number(p.price || 0).toLocaleString('es-AR')}</div>
                    ${p.colors && p.colors.length > 0 ? `
                        <div class="color-list">
                            ${p.colors.map(c => `<span class="color-chip"><span class="dot" style="background:${c.hex}"></span>${c.name}</span>`).join('')}
                        </div>
                    ` : ''}
                </div>
            </div>

            <div class="detail-grid">
                <div class="detail-card">
                    <h3>Tamano y Medidas</h3>
                    <div class="detail-row"><span class="label">Tipo de Cama</span><span class="value">${p.sizeType || '-'}</span></div>
                    <div class="detail-row"><span class="label">Medidas</span><span class="value">${p.dimensions || '-'}</span></div>
                </div>

                <div class="detail-card">
                    <h3>Armado</h3>
                    <div class="detail-row"><span class="label">Estado</span><span class="value">${assemblyLabels[p.assembly] || '-'}</span></div>
                    <div class="detail-row"><span class="label">Instructivo</span><span class="value">${p.manual ? 'Si' : 'No'}</span></div>
                    <div class="detail-row"><span class="label">Lugar de Armado</span><span class="value">${p.assemblyPlace || '-'}</span></div>
                </div>`;

            const storageTags = [];
            if (p.drawers > 0) storageTags.push(`${p.drawers} cajon${p.drawers > 1 ? 'es' : ''}`);
            if (p.shoeRack) storageTags.push('Zapatero');
            if (p.innerStorage) storageTags.push('Guardado interior');
            if (p.shelf) storageTags.push('Estante');

            if (storageTags.length > 0) {
                html += `<div class="detail-card">
                    <h3>Espacios de Guardado</h3>
                    <div class="detail-tags">${storageTags.map(t => `<span class="tag">${t}</span>`).join('')}</div>
                </div>`;
            }

            if (p.closures && p.closures.length > 0) {
                html += `<div class="detail-card">
                    <h3>Tipo de Cierre</h3>
                    <div class="detail-tags">${p.closures.map(c => `<span class="tag">${c}</span>`).join('')}</div>
                </div>`;
            }

            if (p.dynamicFeatures && Object.keys(p.dynamicFeatures).length > 0) {
                const entries = Object.entries(p.dynamicFeatures).filter(([k, v]) => v !== '' && v !== null && v !== undefined);
                if (entries.length > 0) {
                    html += `<div class="detail-card full-width">
                        <h3>Caracteristicas Adicionales</h3>
                        ${entries.map(([k, v]) => {
                            const display = (v === true || v === 'true') ? 'Si' : (v === false || v === 'false') ? 'No' : v;
                            return `<div class="detail-row"><span class="label">${k}</span><span class="value">${display}</span></div>`;
                        }).join('')}
                    </div>`;
                }
            }

            html += '</div>';
            document.getElementById('content').innerHTML = html;
        }

        function switchImg(idx) {
            currentImgIdx = idx;
            const img = document.getElementById('heroImg');
            if (img && product.images[idx]) {
                img.src = product.images[idx];
                document.querySelectorAll('.gallery-dot').forEach((d, i) => d.classList.toggle('active', i === idx));
            }
        }

        init();
    </script>
</body>
</html>
