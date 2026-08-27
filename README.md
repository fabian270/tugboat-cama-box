# AppPro

Aplicación web para gestionar y comparar productos de **camas box** con estilo ecommerce. Backend PHP con SQLite/MySQL con Docker.

## Características

### Gestión de Productos
- **Alta, baja y edición** de productos cama box
- **Fotos múltiples** por producto con carrusel de navegación
- **Colores personalizados** con selector hex y nombre
- **URL del sitio** por producto
- **Búsqueda en tiempo real** por nombre, ubicación o tipo

### Especificaciones Técnicas
| Campo | Descripción |
|---|---|
| **Espacios de guardado** | Cajones (cantidad), zapatero, guardado interior, estante |
| **Tipo de cierre** | Telescópico, rieles, hidráulico, manual, resorte, otro |
| **Tamaño** | Tipo de cama (individual, queen, king, doble, matrimonial, etc.) |
| **Medidas** | Dimensiones personalizadas (Ancho x Largo x Alto) |
| **Armado** | Viene armado, no viene armado, fácil de armar, difícil de armar |
| **Instructivo** | Indica si incluye instructivo de armado |
| **Lugar de armado** | Dónde se realiza el armado |

### Características Dinámicas
- Crear nuevas características desde la interfaz (text, número, sí/no, selección)
- Se aplican **automáticamente a todos los productos** existentes y futuros
- Ejemplo: material del tapizado, garantía, resistente a manchas, etc.

### Tabla Comparativa
- Seleccionar productos con checkbox para comparar
- Panel de comparación que aparece al seleccionar 2+ productos
- Vista lado a lado con todas las especificaciones

### Vistas
- **Cuadrícula**: Tarjetas estilo ecommerce con imagen, precio, specs y colores
- **Tabla**: Vista tabular completa con todas las columnas

### Export / Import
- **Exportar**: Descarga un archivo JSON con todos los productos y características desde MariaDB
- **Importar**: Carga un archivo JSON de backup (reemplaza todos los datos en MariaDB)
- **Datos de ejemplo**: Botón para cargar 4 productos de demostración

## Stack Tecnológico

| Componente | Tecnología |
|---|---|
| **Frontend** | HTML5, CSS3, JavaScript vanilla |
| **Backend API** | PHP 8.2 |
| **Servidor web** | Apache |
| **Base de datos** | MariaDB 11 |
| **Containerización** | Docker + Docker Compose |

## Estructura del Proyecto

```
tugboat-cama-box/
├── .htaccess                    # Reescritura de URLs raíz
├── .tugboat/
│   └── config.yml               # Configuración de Tugboat previews
├── docker/
│   └── apache-vhost.conf        # Configuración VirtualHost de Apache
├── db/
│   └── schema.sql               # Esquema de la base de datos
├── api/
│   ├── .htaccess                # Reescritura de URLs para API
│   ├── config.php               # Conexión a MariaDB + CORS
│   ├── products.php             # CRUD de productos (GET/POST/PUT/DELETE)
│   ├── characteristics.php      # CRUD de características personalizadas
│   ├── export.php               # Exportar todos los datos como JSON
│   ├── import.php               # Importar datos desde JSON
│   └── health.php               # Health check del API
├── site/
│   └── index.html               # Frontend (HTML + CSS + JS)
├── docker-compose.yml           # Servicios: web + db
├── Dockerfile                   # Imagen PHP-Apache
└── README.md
```

## Inicio Rápido

### Requisitos
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado y ejecutándose

### Ejecutar

```bash
docker compose up -d --build
```

La app estará disponible en: **http://localhost:8080**

### Parar

```bash
docker compose down
```

### Reiniciar con datos frescos

```bash
docker compose down -v
docker compose up -d --build
```

## API REST

| Endpoint | Método | Descripción |
|---|---|---|
| `/api/products.php` | GET | Obtener todos los productos |
| `/api/products.php` | POST | Crear un producto |
| `/api/products.php` | PUT | Actualizar un producto |
| `/api/products.php?id=X` | DELETE | Eliminar un producto |
| `/api/characteristics.php` | GET | Obtener características |
| `/api/characteristics.php` | POST | Crear característica |
| `/api/characteristics.php?name=X` | DELETE | Eliminar característica |
| `/api/export.php` | GET | Exportar todos los datos |
| `/api/import.php` | POST | Importar datos completos |
| `/api/health.php` | GET | Health check |

## Base de Datos

### Tablas

- **products** — Producto principal (nombre, precio, URL, medidas, etc.)
- **product_colors** — Colores disponibles por producto
- **custom_characteristics** — Características personalizadas globales
- **product_dynamic_features** — Valores de características por producto

El esquema se crea automáticamente al iniciar Docker gracias a `db/schema.sql`.

## Despliegue

### Tugboat
Configurado para previews automáticos. Push a una rama y se genera la preview URL.

### Producción
El `Dockerfile` y `docker-compose.yml` pueden adaptarse a cualquier infraestructura Docker (AWS ECS, DigitalOcean App Platform, etc.).

## Formato de Datos (JSON)

```json
{
  "products": [
    {
      "id": "string",
      "name": "string",
      "location": "string",
      "price": 0,
      "url": "https://...",
      "images": ["base64..."],
      "colors": [{ "hex": "#000", "name": "Negro" }],
      "drawers": 0,
      "shoeRack": true,
      "innerStorage": true,
      "shelf": false,
      "closures": ["Telescópico", "Rieles"],
      "sizeType": "queen",
      "dimensions": "160 x 200 x 42 cm",
      "assembly": "easy",
      "manual": true,
      "assemblyPlace": "Se arma en el domicilio",
      "dynamicFeatures": {
        "Material Tapizado": "Lino"
      }
    }
  ],
  "customCharacteristics": [
    {
      "name": "Material Tapizado",
      "type": "select",
      "options": "Lino, Algodón, Poliéster"
    }
  ]
}
```
