# Cama Box Pro

Aplicación web para gestionar y comparar productos de **camas box** con estilo ecommerce. Permite organizar un catálogo completo de productos con todas sus especificaciones técnicas y generar tablas comparativas.

## Características

### Gestión de Productos
- **Alta, baja y edición** de productos cama box
- **Fotos múltiples** por producto con carrusel de navegación
- **Colores personalizados** con selector hex y nombre
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

### Persistencia y Portabilidad
- **localStorage**: Los datos se guardan automáticamente en el navegador
- **Exportar**: Descarga un archivo JSON con todos los productos y características
- **Importar**: Carga un archivo JSON de backup
- **Datos de ejemplo**: Botón para cargar 4 productos de demostración

## Estructura del Proyecto

```
tugboat-cama-box/
├── .tugboat/
│   └── config.yml          # Configuración de Tugboat previews
├── site/
│   └── index.html          # Aplicación principal (HTML + CSS + JS)
└── README.md               # Este archivo
```

## Uso

### Local
Abrí `site/index.html` directamente en cualquier navegador moderno. No requiere servidor ni dependencias.

### Con Tugboat
El proyecto está configurado para desplegarse automáticamente con [Tugboat](https://tugboatqa.com). Al hacer push a una rama, Tugboat genera una preview URL con el sitio funcionando.

**Configuración de Tugboat** (`.tugboat/config.yml`):
- Servicio: Apache (`tugboatqa/php:apache-trixie`)
- Document root enlazado al directorio `site/`

## Tecnologías

- **HTML5** — Estructura semántica
- **CSS3** — Diseño responsive con variables custom, grid y flexbox
- **JavaScript vanilla** — Sin dependencias externas
- **localStorage** — Persistencia del lado del cliente

## Formato de Datos (JSON)

Al exportar, el archivo tiene esta estructura:

```json
{
  "products": [
    {
      "id": "string",
      "name": "string",
      "location": "string",
      "price": 0,
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
