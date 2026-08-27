<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - AppPro</title>
    <style>
        :root {
            --primary: #1a1a2e; --primary-light: #16213e;
            --accent: #e94560; --accent-hover: #c73a52;
            --bg: #f0f2f5; --card-bg: #ffffff;
            --text: #333333; --text-light: #666666; --border: #e0e0e0;
            --success: #27ae60; --warning: #f39c12; --info: #3498db;
            --shadow: 0 2px 12px rgba(0,0,0,0.08); --shadow-hover: 0 6px 24px rgba(0,0,0,0.15);
            --radius: 12px; --radius-sm: 8px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--bg); color: var(--text); line-height: 1.6; }

        header { background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 20px rgba(0,0,0,0.3); }
        .header-inner { max-width: 1400px; margin: 0 auto; padding: 16px 32px; display: flex; justify-content: space-between; align-items: center; }
        .logo { font-size: 1.8rem; font-weight: 800; }
        .logo span { color: var(--accent); }
        .header-actions { display: flex; gap: 12px; align-items: center; }
        .header-actions .user-info { color: rgba(255,255,255,0.8); font-size: 0.85rem; }

        .btn { padding: 10px 22px; border: none; border-radius: 25px; cursor: pointer; font-size: 0.95rem; font-weight: 600; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-accent { background: var(--accent); color: white; }
        .btn-accent:hover { background: var(--accent-hover); }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-light); }
        .btn-outline { background: transparent; border: 2px solid var(--border); color: var(--text); }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .btn-success { background: var(--success); color: white; }
        .btn-success:hover { background: #219a52; }
        .btn-danger { background: transparent; color: var(--accent); border: 1px solid var(--accent); }
        .btn-danger:hover { background: var(--accent); color: white; }
        .btn-sm { padding: 6px 14px; font-size: 0.85rem; }
        .btn-icon { width: 36px; height: 36px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.1rem; }
        .btn-ghost { background: transparent; color: white; border: none; padding: 8px 16px; }
        .btn-ghost:hover { background: rgba(255,255,255,0.1); }

        .main-container { max-width: 1400px; margin: 0 auto; padding: 32px; }

        /* LOGIN */
        .login-wrapper { display: flex; align-items: center; justify-content: center; min-height: 80vh; }
        .login-card { background: var(--card-bg); border-radius: var(--radius); padding: 40px; width: 100%; max-width: 420px; box-shadow: var(--shadow-hover); }
        .login-card h2 { text-align: center; color: var(--primary); margin-bottom: 24px; font-size: 1.5rem; }
        .login-card .form-group { margin-bottom: 16px; }
        .login-card label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-light); margin-bottom: 6px; }
        .login-card input { width: 100%; padding: 12px 16px; border: 2px solid var(--border); border-radius: var(--radius-sm); font-size: 1rem; outline: none; transition: border-color 0.3s; }
        .login-card input:focus { border-color: var(--accent); }
        .login-card .btn { width: 100%; justify-content: center; margin-top: 8px; padding: 14px; }
        .login-card .error-msg { color: var(--accent); text-align: center; margin-top: 12px; font-size: 0.9rem; display: none; }

        /* TABS */
        .tabs { display: flex; gap: 4px; margin-bottom: 24px; background: var(--card-bg); border-radius: var(--radius); padding: 6px; box-shadow: var(--shadow); }
        .tab-btn { padding: 10px 24px; border: none; background: transparent; cursor: pointer; font-size: 0.95rem; font-weight: 600; border-radius: var(--radius-sm); transition: all 0.3s; color: var(--text-light); }
        .tab-btn.active { background: var(--primary); color: white; }
        .tab-btn:hover:not(.active) { background: var(--bg); }
        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* STATS */
        .stats-bar { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: var(--card-bg); border-radius: var(--radius); padding: 20px 24px; box-shadow: var(--shadow); display: flex; align-items: center; gap: 16px; }
        .stat-icon { width: 48px; height: 48px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
        .stat-icon.products { background: #e8f5e9; }
        .stat-icon.prices { background: #e3f2fd; }
        .stat-icon.users { background: #f3e5f5; }
        .stat-value { font-size: 1.6rem; font-weight: 700; color: var(--primary); }
        .stat-label { font-size: 0.85rem; color: var(--text-light); }

        /* TOOLBAR */
        .toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .toolbar-left { display: flex; gap: 8px; flex-wrap: wrap; }

        /* PRODUCT LIST */
        .product-list { display: flex; flex-direction: column; gap: 12px; }
        .product-row { display: grid; grid-template-columns: 60px 1fr 120px 150px 100px 80px; gap: 16px; align-items: center; background: var(--card-bg); padding: 16px 20px; border-radius: var(--radius-sm); box-shadow: var(--shadow); }
        .product-row .thumb { width: 60px; height: 60px; border-radius: var(--radius-sm); object-fit: cover; background: var(--bg); }
        .product-row .name { font-weight: 700; color: var(--primary); }
        .product-row .location { font-size: 0.85rem; color: var(--text-light); }
        .product-row .price { color: var(--accent); font-weight: 700; font-size: 1.1rem; }
        .product-row .actions { display: flex; gap: 6px; justify-content: flex-end; }

        /* MODAL */
        .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 200; display: none; align-items: center; justify-content: center; padding: 20px; }
        .modal-overlay.active { display: flex; }
        .modal { background: var(--card-bg); border-radius: var(--radius); width: 100%; max-width: 720px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .modal-header { padding: 24px 28px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; background: var(--card-bg); z-index: 1; }
        .modal-header h2 { font-size: 1.3rem; color: var(--primary); }
        .modal-close { width: 36px; height: 36px; border-radius: 50%; border: none; background: var(--bg); cursor: pointer; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; transition: all 0.3s; }
        .modal-close:hover { background: var(--accent); color: white; }
        .modal-body { padding: 28px; }
        .modal-footer { padding: 20px 28px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 12px; position: sticky; bottom: 0; background: var(--card-bg); }

        /* FORM */
        .form-section { margin-bottom: 28px; }
        .form-section-title { font-size: 1rem; font-weight: 700; color: var(--primary); margin-bottom: 16px; padding-bottom: 8px; border-bottom: 2px solid var(--accent); display: inline-block; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group.full-width { grid-column: 1 / -1; }
        .form-group label { font-size: 0.85rem; font-weight: 600; color: var(--text-light); }
        .form-group input, .form-group select, .form-group textarea { padding: 10px 14px; border: 2px solid var(--border); border-radius: var(--radius-sm); font-size: 0.95rem; font-family: inherit; transition: all 0.3s; outline: none; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(233,69,96,0.1); }
        .form-group textarea { resize: vertical; min-height: 80px; }
        .checkbox-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .checkbox-item { display: flex; align-items: center; gap: 8px; padding: 8px 12px; background: var(--bg); border-radius: var(--radius-sm); cursor: pointer; transition: all 0.3s; }
        .checkbox-item:hover { background: #e8f0fe; }
        .checkbox-item input { accent-color: var(--accent); width: 16px; height: 16px; }

        .image-upload-area { border: 2px dashed var(--border); border-radius: var(--radius-sm); padding: 24px; text-align: center; cursor: pointer; transition: all 0.3s; background: var(--bg); }
        .image-upload-area:hover { border-color: var(--accent); background: #fff0f3; }
        .image-preview-grid { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px; }
        .image-preview-container { position: relative; display: inline-block; }
        .image-preview { width: 80px; height: 80px; border-radius: var(--radius-sm); object-fit: cover; border: 2px solid var(--border); }
        .image-preview-container .remove-img { position: absolute; top: -6px; right: -6px; width: 22px; height: 22px; border-radius: 50%; background: var(--accent); color: white; border: none; font-size: 0.7rem; cursor: pointer; display: flex; align-items: center; justify-content: center; }

        .color-input-group { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
        .color-input-group input[type="color"] { width: 40px; height: 40px; border: 2px solid var(--border); border-radius: var(--radius-sm); cursor: pointer; padding: 2px; }
        .color-tags { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 8px; }
        .color-tag { display: flex; align-items: center; gap: 6px; padding: 4px 10px; background: var(--bg); border-radius: 20px; font-size: 0.85rem; }
        .color-tag .remove-color { cursor: pointer; color: var(--accent); font-weight: bold; }

        .dynamic-features { display: flex; flex-direction: column; gap: 10px; }
        .dynamic-feature-row { display: flex; gap: 10px; align-items: center; }
        .dynamic-feature-row input, .dynamic-feature-row select { flex: 1; padding: 8px 12px; border: 2px solid var(--border); border-radius: var(--radius-sm); font-size: 0.9rem; outline: none; }
        .dynamic-feature-row input:focus, .dynamic-feature-row select:focus { border-color: var(--accent); }

        .char-list { display: flex; flex-direction: column; gap: 8px; margin-top: 12px; }
        .char-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: var(--bg); border-radius: var(--radius-sm); }
        .char-item .char-name { font-weight: 600; font-size: 0.9rem; }
        .char-item .char-type { font-size: 0.8rem; color: var(--text-light); }

        /* USER TABLE */
        .user-table { width: 100%; border-collapse: collapse; background: var(--card-bg); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); }
        .user-table th { background: var(--primary); color: white; padding: 14px 16px; text-align: left; font-weight: 600; font-size: 0.9rem; }
        .user-table td { padding: 12px 16px; border-bottom: 1px solid var(--border); font-size: 0.9rem; }
        .user-table tr:hover td { background: #f8f9ff; }
        .role-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .role-badge.admin { background: #fce4ec; color: var(--accent); }
        .role-badge.editor { background: #e3f2fd; color: var(--info); }

        .toast-container { position: fixed; top: 80px; right: 20px; z-index: 300; display: flex; flex-direction: column; gap: 8px; }
        .toast { background: var(--primary); color: white; padding: 12px 20px; border-radius: var(--radius-sm); box-shadow: var(--shadow-hover); animation: slideIn 0.3s ease; display: flex; align-items: center; gap: 10px; font-size: 0.9rem; }
        .toast.success { border-left: 4px solid var(--success); }
        .toast.error { border-left: 4px solid var(--accent); }
        .toast.info { border-left: 4px solid var(--info); }

        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        @media (max-width: 768px) {
            .header-inner { padding: 12px 16px; flex-wrap: wrap; gap: 12px; }
            .main-container { padding: 16px; }
            .product-row { grid-template-columns: 1fr; gap: 8px; }
            .form-grid { grid-template-columns: 1fr; }
            .modal { max-width: 100%; margin: 10px; }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-inner">
            <div class="logo">App<span>Pro</span> Admin</div>
            <div class="header-actions" id="headerActions"></div>
        </div>
    </header>

    <div class="main-container">
        <div id="loginView"></div>
        <div id="adminView" style="display:none"></div>
    </div>

    <div class="modal-overlay" id="productModal">
        <div class="modal">
            <div class="modal-header">
                <h2 id="modalTitle">Nuevo Producto</h2>
                <button class="modal-close" onclick="closeProductModal()">✕</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editProductId">
                <div class="form-section">
                    <div class="form-section-title">Informacion General</div>
                    <div class="form-grid">
                        <div class="form-group full-width"><label>Nombre del Producto</label><input type="text" id="prodName" placeholder="Ej: Cama Box Queen Luxury"></div>
                        <div class="form-group"><label>Lugar / Ubicacion</label><input type="text" id="prodLocation" placeholder="Ej: Sucursal Centro"></div>
                        <div class="form-group"><label>Precio ($)</label><input type="number" id="prodPrice" placeholder="0.00" step="0.01"></div>
                        <div class="form-group full-width"><label>URL del Sitio del Producto</label><input type="url" id="prodUrl" placeholder="https://ejemplo.com/producto"></div>
                    </div>
                </div>
                <div class="form-section">
                    <div class="form-section-title">Fotos del Producto</div>
                    <div class="image-upload-area" id="imageUploadArea" onclick="document.getElementById('imageInput').click()">
                        <p>Haz clic para agregar fotos</p>
                    </div>
                    <input type="file" id="imageInput" accept="image/*" multiple style="display:none" onchange="handleImageUpload(event)">
                    <div class="image-preview-grid" id="imagePreviewGrid"></div>
                </div>
                <div class="form-section">
                    <div class="form-section-title">Colores Disponibles</div>
                    <div class="color-input-group">
                        <input type="color" id="colorPicker" value="#333333">
                        <input type="text" id="colorName" placeholder="Nombre del color" style="padding:8px 12px;border:2px solid var(--border);border-radius:var(--radius-sm);font-size:0.9rem;flex:1">
                        <button class="btn btn-sm btn-primary" onclick="addColor()">+ Agregar</button>
                    </div>
                    <div class="color-tags" id="colorTags"></div>
                </div>
                <div class="form-section">
                    <div class="form-section-title">Espacios de Guardado</div>
                    <div class="form-grid">
                        <div class="form-group"><label>Cajones (cantidad)</label><input type="number" id="prodDrawers" placeholder="0" min="0"></div>
                        <div class="form-group"><label>Zapatero</label><select id="prodShoeRack"><option value="false">No</option><option value="true">Si</option></select></div>
                        <div class="form-group"><label>Guardado Interior</label><select id="prodInnerStorage"><option value="false">No</option><option value="true">Si</option></select></div>
                        <div class="form-group"><label>Estante</label><select id="prodShelf"><option value="false">No</option><option value="true">Si</option></select></div>
                    </div>
                </div>
                <div class="form-section">
                    <div class="form-section-title">Tipo de Cierre</div>
                    <div class="checkbox-grid">
                        <label class="checkbox-item"><input type="checkbox" id="closeTelescopic" value="telescopic"> Telescopico</label>
                        <label class="checkbox-item"><input type="checkbox" id="closeRails" value="rails"> Rieles</label>
                        <label class="checkbox-item"><input type="checkbox" id="closeHydraulic" value="hydraulic"> Hidraulico</label>
                        <label class="checkbox-item"><input type="checkbox" id="closeManual" value="manual"> Manual</label>
                        <label class="checkbox-item"><input type="checkbox" id="closeSpring" value="spring"> Resorte</label>
                        <label class="checkbox-item"><input type="checkbox" id="closeOther" value="other"> Otro</label>
                    </div>
                </div>
                <div class="form-section">
                    <div class="form-section-title">Tamano y Medidas</div>
                    <div class="form-grid">
                        <div class="form-group"><label>Tipo de Cama</label><select id="prodSizeType"><option value="">Seleccionar...</option><option value="individual">Individual</option><option value="queen">Queen</option><option value="king">King</option><option value="doble">Doble</option><option value="matrimonial">Matrimonial</option><option value="1.50">1.50m</option><option value="1.90">1.90m</option><option value="2.00">2.00m</option></select></div>
                        <div class="form-group"><label>Medidas (Ancho x Largo x Alto)</label><input type="text" id="prodDimensions" placeholder="Ej: 150 x 200 x 40 cm"></div>
                    </div>
                </div>
                <div class="form-section">
                    <div class="form-section-title">Armado</div>
                    <div class="form-grid">
                        <div class="form-group"><label>Estado del Armado</label><select id="prodAssembly"><option value="">Seleccionar...</option><option value="assembled">Viene Armado</option><option value="unassembled">No Viene Armado</option><option value="easy">Facil de Armar</option><option value="hard">Dificil de Armar</option></select></div>
                        <div class="form-group"><label>Instructivo</label><select id="prodManual"><option value="false">Sin Instructivo</option><option value="true">Con Instructivo</option></select></div>
                        <div class="form-group full-width"><label>Donde se arma?</label><input type="text" id="prodAssemblyPlace" placeholder="Ej: Se arma en el domicilio del cliente"></div>
                    </div>
                </div>
                <div class="form-section">
                    <div class="form-section-title">Caracteristicas Adicionales</div>
                    <div class="dynamic-features" id="dynamicFeatures"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeProductModal()">Cancelar</button>
                <button class="btn btn-accent" onclick="saveProduct()">Guardar Producto</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="charModal">
        <div class="modal" style="max-width:560px">
            <div class="modal-header">
                <h2>Gestionar Caracteristicas</h2>
                <button class="modal-close" onclick="closeCharModal()">✕</button>
            </div>
            <div class="modal-body">
                <p style="color:var(--text-light);margin-bottom:16px;font-size:0.9rem">Agrega caracteristicas personalizadas que se aplicaran a <strong>todos</strong> los productos.</p>
                <div class="form-section">
                    <div class="form-group"><label>Nombre</label><input type="text" id="newCharName" placeholder="Ej: Material del tapizado"></div>
                    <div class="form-group" style="margin-top:10px"><label>Tipo</label><select id="newCharType"><option value="text">Texto</option><option value="boolean">Si / No</option><option value="number">Numero</option><option value="select">Seleccion (opciones por coma)</option></select></div>
                    <div class="form-group" id="charOptionsGroup" style="margin-top:10px;display:none"><label>Opciones (separadas por coma)</label><input type="text" id="newCharOptions" placeholder="Ej: Lino, Algodon, Poliester"></div>
                    <button class="btn btn-sm btn-accent" style="margin-top:12px" onclick="addCharacteristic()">+ Agregar</button>
                </div>
                <div class="char-list" id="charList"></div>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="userModal">
        <div class="modal" style="max-width:480px">
            <div class="modal-header">
                <h2 id="userModalTitle">Nuevo Usuario</h2>
                <button class="modal-close" onclick="closeUserModal()">✕</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editUserId">
                <div class="form-group"><label>Usuario</label><input type="text" id="newUsername" placeholder="nombre de usuario"></div>
                <div class="form-group" style="margin-top:12px"><label>Contrasena</label><input type="password" id="newPassword" placeholder="contrasena"></div>
                <div class="form-group" style="margin-top:12px"><label>Rol</label><select id="newUserRole"><option value="editor">Editor</option><option value="admin">Admin</option></select></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeUserModal()">Cancelar</button>
                <button class="btn btn-accent" onclick="saveUser()">Guardar</button>
            </div>
        </div>
    </div>

    <div class="toast-container" id="toastContainer"></div>

    <script>
        const API_BASE = '/api';
        let currentUser = null;
        let products = [];
        let customCharacteristics = [];
        let users = [];
        let uploadedImages = [];
        let productColors = [];
        let dynamicFeatureRows = [];

        async function apiCall(endpoint, method, body) {
            const opts = { method: method || 'GET', headers: {} };
            if (body) { opts.headers['Content-Type'] = 'application/json'; opts.body = JSON.stringify(body); }
            try {
                const res = await fetch(`${API_BASE}/${endpoint}`, opts);
                const data = await res.json();
                if (!res.ok && data.error) { showToast(data.error, 'error'); return null; }
                return data;
            } catch(e) { showToast('Error de conexion con el servidor', 'error'); return null; }
        }

        async function checkAuth() {
            const data = await apiCall('auth.php');
            if (data && data.logged) {
                currentUser = data.user;
                renderAdminView();
            } else {
                renderLoginView();
            }
        }

        function renderLoginView() {
            document.getElementById('loginView').style.display = 'block';
            document.getElementById('adminView').style.display = 'none';
            document.getElementById('headerActions').innerHTML = `<a href="index.php" class="btn btn-ghost" style="text-decoration:none">← Volver al Comparador</a>`;
            document.getElementById('loginView').innerHTML = `
                <div class="login-wrapper">
                    <div class="login-card">
                        <h2>Iniciar Sesion</h2>
                        <div class="form-group"><label>Usuario</label><input type="text" id="loginUser" placeholder="usuario" onkeydown="if(event.key==='Enter')doLogin()"></div>
                        <div class="form-group"><label>Contrasena</label><input type="password" id="loginPass" placeholder="contrasena" onkeydown="if(event.key==='Enter')doLogin()"></div>
                        <button class="btn btn-accent" onclick="doLogin()">Ingresar</button>
                        <div class="error-msg" id="loginError"></div>
                    </div>
                </div>`;
        }

        async function doLogin() {
            const username = document.getElementById('loginUser').value.trim();
            const password = document.getElementById('loginPass').value;
            if (!username || !password) { document.getElementById('loginError').textContent = 'Completa todos los campos'; document.getElementById('loginError').style.display = 'block'; return; }
            const result = await apiCall('auth.php', 'POST', { action: 'login', username, password });
            if (result && result.status === 'ok') {
                currentUser = result.user;
                renderAdminView();
                showToast(`Bienvenido, ${currentUser.username}`, 'success');
            } else {
                document.getElementById('loginError').textContent = 'Credenciales incorrectas';
                document.getElementById('loginError').style.display = 'block';
            }
        }

        async function doLogout() {
            await apiCall('auth.php', 'POST', { action: 'logout' });
            currentUser = null;
            renderLoginView();
        }

        function renderAdminView() {
            document.getElementById('loginView').style.display = 'none';
            document.getElementById('adminView').style.display = 'block';
            const roleLabel = currentUser.role === 'admin' ? 'Admin' : 'Editor';
            document.getElementById('headerActions').innerHTML = `
                <span class="user-info">${currentUser.username} (${roleLabel})</span>
                <a href="index.php" class="btn btn-ghost" style="text-decoration:none">Comparador</a>
                <button class="btn btn-ghost" onclick="doLogout()">Cerrar Sesion</button>`;
            loadData();
        }

        async function loadData() {
            const [prods, chars] = await Promise.all([
                apiCall('products.php'),
                apiCall('characteristics.php')
            ]);
            if (prods !== null) products = prods;
            if (chars !== null) customCharacteristics = chars;
            if (currentUser.role === 'admin') {
                const u = await apiCall('users.php');
                if (u !== null) users = u;
            }
            renderAdminContent();
        }

        function renderAdminContent() {
            const isAdmin = currentUser.role === 'admin';
            const writeBtns = `<button class="btn btn-accent" onclick="openProductModal()">+ Nuevo Producto</button>
                <button class="btn btn-outline btn-sm" onclick="openCharModal()">Caracteristicas</button>
                <button class="btn btn-outline btn-sm" onclick="exportData()">↓ Exportar</button>
                <button class="btn btn-outline btn-sm" onclick="document.getElementById('importFile').click()">↑ Importar</button>
                <input type="file" id="importFile" accept=".json" style="display:none" onchange="importData(event)">`;

            document.getElementById('adminView').innerHTML = `
                <div class="stats-bar">
                    <div class="stat-card"><div class="stat-icon products">🛏</div><div><div class="stat-value">${products.length}</div><div class="stat-label">Productos</div></div></div>
                    <div class="stat-card"><div class="stat-icon prices">💰</div><div><div class="stat-value">$${products.length > 0 ? Math.round(products.reduce((s,p) => s + Number(p.price||0), 0) / products.length).toLocaleString('es-AR') : 0}</div><div class="stat-label">Precio Promedio</div></div></div>
                    ${isAdmin ? `<div class="stat-card"><div class="stat-icon users">👤</div><div><div class="stat-value">${users.length}</div><div class="stat-label">Usuarios</div></div></div>` : ''}
                </div>
                <div class="tabs">
                    <button class="tab-btn active" onclick="switchTab('products')">Productos</button>
                    ${isAdmin ? `<button class="tab-btn" onclick="switchTab('users')">Usuarios</button>` : ''}
                </div>
                <div class="toolbar"><div class="toolbar-left">${writeBtns}</div></div>
                <div class="tab-content active" id="tab-products">
                    <div class="product-list" id="productList"></div>
                </div>
                ${isAdmin ? `<div class="tab-content" id="tab-users"><div id="userListContent"></div></div>` : ''}
            `;
            renderProductList();
            if (isAdmin) renderUserList();
        }

        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            event.target.classList.add('active');
            document.getElementById('tab-' + tab).classList.add('active');
        }

        function renderProductList() {
            const el = document.getElementById('productList');
            if (products.length === 0) {
                el.innerHTML = '<div style="text-align:center;padding:40px;color:var(--text-light)">No hay productos</div>';
                return;
            }
            el.innerHTML = products.map(p => `
                <div class="product-row">
                    <img class="thumb" src="${(p.images && p.images[0]) || ''}" onerror="this.style.display='none'" alt="">
                    <div><div class="name">${p.name}</div><div class="location">${p.location || ''}</div></div>
                    <div class="price">$${Number(p.price||0).toLocaleString('es-AR')}</div>
                    <div style="font-size:0.85rem;color:var(--text-light)">${p.sizeType || ''} ${p.drawers > 0 ? p.drawers+' cajones' : ''}</div>
                    <div style="font-size:0.85rem">${(p.colors||[]).map(c => `<span style="display:inline-block;width:16px;height:16px;border-radius:50%;background:${c.hex};border:1px solid var(--border)"></span>`).join(' ')}</div>
                    <div class="actions">
                        <button class="btn btn-sm btn-outline" onclick="editProduct('${p.id}')">✎</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteProduct('${p.id}')">✕</button>
                    </div>
                </div>
            `).join('');
        }

        function renderUserList() {
            const el = document.getElementById('userListContent');
            el.innerHTML = `
                <button class="btn btn-accent btn-sm" style="margin-bottom:16px" onclick="openUserModal()">+ Nuevo Usuario</button>
                <table class="user-table">
                    <thead><tr><th>Usuario</th><th>Rol</th><th>Creado</th><th>Acciones</th></tr></thead>
                    <tbody>${users.map(u => `
                        <tr>
                            <td><strong>${u.username}</strong></td>
                            <td><span class="role-badge ${u.role}">${u.role === 'admin' ? 'Administrador' : 'Editor'}</span></td>
                            <td style="font-size:0.85rem;color:var(--text-light)">${new Date(u.created_at).toLocaleDateString('es-AR')}</td>
                            <td><button class="btn btn-sm btn-danger" onclick="deleteUser(${u.id})">✕</button></td>
                        </tr>
                    `).join('')}</tbody>
                </table>`;
        }

        // PRODUCT MODAL
        function openProductModal(productId) {
            document.getElementById('editProductId').value = productId || '';
            document.getElementById('modalTitle').textContent = productId ? 'Editar Producto' : 'Nuevo Producto';
            if (productId) {
                const p = products.find(x => x.id === productId);
                if (p) fillProductForm(p);
            } else { resetProductForm(); }
            document.getElementById('productModal').classList.add('active');
        }
        function closeProductModal() { document.getElementById('productModal').classList.remove('active'); }

        function fillProductForm(p) {
            document.getElementById('prodName').value = p.name || '';
            document.getElementById('prodLocation').value = p.location || '';
            document.getElementById('prodPrice').value = p.price || '';
            document.getElementById('prodUrl').value = p.url || '';
            document.getElementById('prodDrawers').value = p.drawers || 0;
            document.getElementById('prodShoeRack').value = p.shoeRack ? 'true' : 'false';
            document.getElementById('prodInnerStorage').value = p.innerStorage ? 'true' : 'false';
            document.getElementById('prodShelf').value = p.shelf ? 'true' : 'false';
            document.getElementById('prodSizeType').value = p.sizeType || '';
            document.getElementById('prodDimensions').value = p.dimensions || '';
            document.getElementById('prodAssembly').value = p.assembly || '';
            document.getElementById('prodManual').value = p.manual ? 'true' : 'false';
            document.getElementById('prodAssemblyPlace').value = p.assemblyPlace || '';
            document.getElementById('closeTelescopic').checked = (p.closures || []).includes('Telescopico');
            document.getElementById('closeRails').checked = (p.closures || []).includes('Rieles');
            document.getElementById('closeHydraulic').checked = (p.closures || []).includes('Hidraulico');
            document.getElementById('closeManual').checked = (p.closures || []).includes('Manual');
            document.getElementById('closeSpring').checked = (p.closures || []).includes('Resorte');
            document.getElementById('closeOther').checked = (p.closures || []).includes('Otro');
            uploadedImages = p.images ? [...p.images] : [];
            productColors = p.colors ? [...p.colors] : [];
            renderImagePreviews();
            renderColorTags();
            renderDynamicFeatures(p.dynamicFeatures || {});
        }

        function resetProductForm() {
            ['prodName','prodLocation','prodPrice','prodUrl','prodDimensions','prodAssemblyPlace'].forEach(id => document.getElementById(id).value = '');
            document.getElementById('prodDrawers').value = 0;
            ['prodShoeRack','prodInnerStorage','prodShelf'].forEach(id => document.getElementById(id).value = 'false');
            document.getElementById('prodSizeType').value = '';
            document.getElementById('prodAssembly').value = '';
            document.getElementById('prodManual').value = 'false';
            ['closeTelescopic','closeRails','closeHydraulic','closeManual','closeSpring','closeOther'].forEach(id => document.getElementById(id).checked = false);
            uploadedImages = [];
            productColors = [];
            renderImagePreviews();
            renderColorTags();
            renderDynamicFeatures({});
        }

        function handleImageUpload(event) {
            Array.from(event.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) { uploadedImages.push(e.target.result); renderImagePreviews(); };
                reader.readAsDataURL(file);
            });
        }

        function renderImagePreviews() {
            document.getElementById('imagePreviewGrid').innerHTML = uploadedImages.map((img, i) => `
                <div class="image-preview-container">
                    <img class="image-preview" src="${img}" alt="Preview">
                    <button class="remove-img" onclick="uploadedImages.splice(${i},1);renderImagePreviews()">✕</button>
                </div>
            `).join('');
        }

        function addColor() {
            const hex = document.getElementById('colorPicker').value;
            const name = document.getElementById('colorName').value.trim();
            if (!name) { showToast('Ingresa un nombre para el color', 'error'); return; }
            productColors.push({ hex, name });
            document.getElementById('colorName').value = '';
            renderColorTags();
        }

        function renderColorTags() {
            document.getElementById('colorTags').innerHTML = productColors.map((c, i) => `
                <span class="color-tag">
                    <span style="width:14px;height:14px;border-radius:50%;background:${c.hex};display:inline-block;border:1px solid var(--border)"></span>
                    ${c.name}
                    <span class="remove-color" onclick="productColors.splice(${i},1);renderColorTags()">✕</span>
                </span>
            `).join('');
        }

        function renderDynamicFeatures(existing) {
            const container = document.getElementById('dynamicFeatures');
            dynamicFeatureRows = [];
            container.innerHTML = '';
            customCharacteristics.forEach(c => {
                const val = existing[c.name] !== undefined ? existing[c.name] : '';
                addDynamicFeatureRow(c.name, c.type, val, c.options);
            });
            if (dynamicFeatureRows.length === 0) {
                container.innerHTML = '<p style="color:var(--text-light);font-size:0.85rem">No hay caracteristicas definidas.</p>';
            }
        }

        function addDynamicFeatureRow(name, type, value, options) {
            const container = document.getElementById('dynamicFeatures');
            const idx = dynamicFeatureRows.length;
            const char = customCharacteristics.find(c => c.name === name);
            if (!char && !name) return;
            const charData = char || { name, type: type || 'text' };

            let inputHtml = '';
            if (charData.type === 'boolean') {
                inputHtml = `<select id="dyn_${idx}"><option value="true" ${value===true||value==='true'?'selected':''}>Si</option><option value="false" ${value===false||value==='false'?'selected':''}>No</option></select>`;
            } else if (charData.type === 'number') {
                inputHtml = `<input type="number" id="dyn_${idx}" value="${value}" placeholder="0">`;
            } else if (charData.type === 'select') {
                const opts = (charData.options || '').split(',').map(o => o.trim()).filter(Boolean);
                inputHtml = `<select id="dyn_${idx}"><option value="">Seleccionar...</option>${opts.map(o => `<option value="${o}" ${value===o?'selected':''}>${o}</option>`).join('')}</select>`;
            } else {
                inputHtml = `<input type="text" id="dyn_${idx}" value="${value || ''}" placeholder="${charData.name}">`;
            }

            const row = document.createElement('div');
            row.className = 'dynamic-feature-row';
            row.innerHTML = `<label style="min-width:140px;font-size:0.85rem;font-weight:600;color:var(--text-light)">${charData.name}</label>${inputHtml}`;
            container.appendChild(row);
            dynamicFeatureRows.push({ key: charData.name, idx });
        }

        async function saveProduct() {
            const name = document.getElementById('prodName').value.trim();
            if (!name) { showToast('El nombre es obligatorio', 'error'); return; }

            const closures = [];
            if (document.getElementById('closeTelescopic').checked) closures.push('Telescopico');
            if (document.getElementById('closeRails').checked) closures.push('Rieles');
            if (document.getElementById('closeHydraulic').checked) closures.push('Hidraulico');
            if (document.getElementById('closeManual').checked) closures.push('Manual');
            if (document.getElementById('closeSpring').checked) closures.push('Resorte');
            if (document.getElementById('closeOther').checked) closures.push('Otro');

            const dynamicFeatures = {};
            dynamicFeatureRows.forEach(row => {
                const el = document.getElementById(`dyn_${row.idx}`);
                if (el) dynamicFeatures[row.key] = el.value;
            });

            const editId = document.getElementById('editProductId').value;
            const product = {
                id: editId || generateId(),
                name, location: document.getElementById('prodLocation').value.trim(),
                price: parseFloat(document.getElementById('prodPrice').value) || 0,
                url: document.getElementById('prodUrl').value.trim(),
                images: [...uploadedImages], colors: [...productColors],
                drawers: parseInt(document.getElementById('prodDrawers').value) || 0,
                shoeRack: document.getElementById('prodShoeRack').value === 'true',
                innerStorage: document.getElementById('prodInnerStorage').value === 'true',
                shelf: document.getElementById('prodShelf').value === 'true',
                closures, sizeType: document.getElementById('prodSizeType').value,
                dimensions: document.getElementById('prodDimensions').value.trim(),
                assembly: document.getElementById('prodAssembly').value,
                manual: document.getElementById('prodManual').value === 'true',
                assemblyPlace: document.getElementById('prodAssemblyPlace').value.trim(),
                dynamicFeatures,
                isNew: editId ? (products.find(p => p.id === editId)?.isNew ?? true) : true
            };

            const method = editId ? 'PUT' : 'POST';
            const result = await apiCall('products.php', method, product);
            if (result && result.status === 'ok') {
                await loadData();
                closeProductModal();
                showToast(editId ? 'Producto actualizado' : 'Producto agregado', 'success');
            }
        }

        function editProduct(id) { openProductModal(id); }

        async function deleteProduct(id) {
            if (!confirm('Eliminar este producto?')) return;
            const result = await apiCall('products.php?id=' + encodeURIComponent(id), 'DELETE');
            if (result && result.status === 'ok') { await loadData(); showToast('Producto eliminado', 'info'); }
        }

        function generateId() { return Date.now().toString(36) + Math.random().toString(36).substr(2, 5); }

        // CHARACTERISTICS
        function openCharModal() {
            document.getElementById('charModal').classList.add('active');
            document.getElementById('newCharName').value = '';
            document.getElementById('newCharType').value = 'text';
            document.getElementById('charOptionsGroup').style.display = 'none';
            document.getElementById('newCharOptions').value = '';
            renderCharList();
        }
        function closeCharModal() { document.getElementById('charModal').classList.remove('active'); }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('newCharType')?.addEventListener('change', function() {
                document.getElementById('charOptionsGroup').style.display = this.value === 'select' ? 'block' : 'none';
            });
        });

        async function addCharacteristic() {
            const name = document.getElementById('newCharName').value.trim();
            if (!name) { showToast('Ingresa un nombre', 'error'); return; }
            if (customCharacteristics.find(c => c.name === name)) { showToast('Ya existe', 'error'); return; }
            const type = document.getElementById('newCharType').value;
            const options = document.getElementById('newCharOptions').value;
            const result = await apiCall('characteristics.php', 'POST', { name, type, options });
            if (result && result.status === 'ok') { await loadData(); showToast(`"${name}" agregada`, 'success'); }
        }

        async function removeCharacteristic(name) {
            if (!confirm(`Eliminar "${name}" de todos los productos?`)) return;
            const result = await apiCall('characteristics.php?name=' + encodeURIComponent(name), 'DELETE');
            if (result && result.status === 'ok') { await loadData(); showToast('Eliminada', 'info'); }
        }

        function renderCharList() {
            const typeLabels = { text: 'Texto', boolean: 'Si/No', number: 'Numero', select: 'Seleccion' };
            document.getElementById('charList').innerHTML = customCharacteristics.length === 0
                ? '<p style="color:var(--text-light);font-size:0.85rem;text-align:center;padding:20px">No hay caracteristicas</p>'
                : customCharacteristics.map(c => `
                    <div class="char-item">
                        <div><span class="char-name">${c.name}</span> <span class="char-type">${typeLabels[c.type]||c.type}</span>${c.options ? ` <span class="char-type">- ${c.options}</span>` : ''}</div>
                        <button class="btn btn-sm btn-danger" onclick="removeCharacteristic('${c.name.replace(/'/g,"\\'")}')">✕</button>
                    </div>
                `).join('');
        }

        // USERS
        function openUserModal(userId) {
            document.getElementById('editUserId').value = userId || '';
            document.getElementById('userModalTitle').textContent = userId ? 'Editar Usuario' : 'Nuevo Usuario';
            if (userId) {
                const u = users.find(x => x.id == userId);
                document.getElementById('newUsername').value = u ? u.username : '';
                document.getElementById('newUsername').disabled = true;
                document.getElementById('newPassword').value = '';
                document.getElementById('newUserRole').value = u ? u.role : 'editor';
            } else {
                document.getElementById('newUsername').value = '';
                document.getElementById('newUsername').disabled = false;
                document.getElementById('newPassword').value = '';
                document.getElementById('newUserRole').value = 'editor';
            }
            document.getElementById('userModal').classList.add('active');
        }
        function closeUserModal() { document.getElementById('userModal').classList.remove('active'); }

        async function saveUser() {
            const editId = document.getElementById('editUserId').value;
            const username = document.getElementById('newUsername').value.trim();
            const password = document.getElementById('newPassword').value;
            const role = document.getElementById('newUserRole').value;

            if (editId) {
                const data = { id: editId, role };
                if (password) data.password = password;
                const result = await apiCall('users.php', 'PUT', data);
                if (result && result.status === 'ok') { await loadData(); closeUserModal(); showToast('Usuario actualizado', 'success'); }
            } else {
                if (!username || !password) { showToast('Usuario y contrasena requeridos', 'error'); return; }
                const result = await apiCall('users.php', 'POST', { username, password, role });
                if (result && result.status === 'ok') { await loadData(); closeUserModal(); showToast('Usuario creado', 'success'); }
            }
        }

        async function deleteUser(id) {
            if (!confirm('Eliminar este usuario?')) return;
            const result = await apiCall('users.php?id=' + id, 'DELETE');
            if (result && result.status === 'ok') { await loadData(); showToast('Usuario eliminado', 'info'); }
        }

        // EXPORT / IMPORT
        async function exportData() {
            const data = await apiCall('export.php');
            if (!data) return;
            const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a'); a.href = url;
            a.download = `apppro_backup_${new Date().toISOString().slice(0,10)}.json`;
            a.click(); URL.revokeObjectURL(url);
            showToast('Datos exportados', 'success');
        }

        async function importData(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = async function(e) {
                try {
                    const data = JSON.parse(e.target.result);
                    const result = await apiCall('import.php', 'POST', data);
                    if (result && result.status === 'ok') { await loadData(); showToast(`Importados: ${result.count} productos`, 'success'); }
                } catch(err) { showToast('Error al importar archivo', 'error'); }
            };
            reader.readAsText(file);
            event.target.value = '';
        }

        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            const icons = { success: '✓', error: '✕', info: 'ℹ' };
            toast.innerHTML = `<span>${icons[type]||'ℹ'}</span> ${message}`;
            container.appendChild(toast);
            setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
        }

        checkAuth();
    </script>
</body>
</html>
