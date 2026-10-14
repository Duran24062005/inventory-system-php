Este proyecto es una base inicial para construir un sistema de ventas/inventario con PHP puro. Actualmente todavía no implementa productos, clientes, ventas, usuarios ni CRUD; solo contiene la infraestructura inicial.

## 1. Flujo principal

Una petición sigue este recorrido:

```text
Navegador
   ↓
public/index.php
   ↓
Request → Router
   ↓
Controller
   ↓
Response HTML o JSON
   ↓
View
```

El punto de entrada principal es [`public/index.php`]( /var/www/html/inventory-system/public/index.php:11). Allí se:

1. Carga Composer.
2. Cargan las variables de `.env`.
3. Se registran las rutas.
4. Se crea la petición.
5. Se ejecuta el controlador correspondiente.

Las rutas actuales son:

- `GET /`: pantalla de bienvenida.
- `GET /health`: respuesta JSON de estado.
- `GET /tree-json`: estructura del proyecto en formato JSON.
- `GET /tree-html`: pantalla HTML de prueba del árbol del proyecto.
- Cualquier otra ruta: página 404.

## 2. Organización de carpetas

- [`public/`]( /var/www/html/inventory-system/public/index.php:1): entrada HTTP pública.
- [`src/Config/`]( /var/www/html/inventory-system/src/Config/Database.php:1): configuración y conexión a PostgreSQL.
- [`src/Http/`]( /var/www/html/inventory-system/src/Http/Router.php:1): petición, respuestas y router.
- [`src/Controllers/`]( /var/www/html/inventory-system/src/Controllers/HomeController.php:1): lógica de cada ruta.
- [`src/Views/`]( /var/www/html/inventory-system/src/Views/View.php:1): renderizador de plantillas.
- [`views/`]( /var/www/html/inventory-system/views/home.php:1): HTML visible.
- [`public/styles/`]( /var/www/html/inventory-system/public/styles/styles.css:1): estilos públicos.
- [`public/scripts/`]( /var/www/html/inventory-system/public/scripts/script.js:1): JavaScript público.
- [`docs/`]( /var/www/html/inventory-system/docs/init.md:1): decisiones, instalación y despliegue.
- [`api/index.php`]( /var/www/html/inventory-system/api/index.php:1): puente alternativo hacia el bootstrap principal.

Utiliza Composer y PSR-4; el namespace `AlexiDg\InventorySystem\` apunta a la carpeta `src/`, según [`composer.json`]( /var/www/html/inventory-system/composer.json:1).

## 3. El archivo `Database.php`

El archivo activo que tienes abierto centraliza la conexión con PostgreSQL.

### `Database::connection()`

En [`Database.php`]( /var/www/html/inventory-system/src/Config/Database.php:15):

- Crea una conexión PDO.
- La guarda en una propiedad estática para reutilizarla.
- Configura errores mediante excepciones.
- Devuelve resultados como arrays asociativos.
- Desactiva las consultas preparadas emuladas.

```php
Database::connection()
```

Cuando posteriormente se implemente, por ejemplo, un controlador de productos, ese controlador podría usar esta conexión para ejecutar consultas SQL.

### Variables aceptadas

Primero intenta encontrar una URL PostgreSQL:

- `DATABASE_URL`
- `DATABASE_URL_UNPOOLED`
- `POSTGRES_URL`
- `POSTGRES_URL_NON_POOLING`

Si no existen, utiliza variables separadas como:

- `PGHOST`
- `PGPORT`
- `PGDATABASE`
- `PGUSER`
- `PGPASSWORD`

La URL se convierte internamente en un DSN PDO PostgreSQL. También conserva opciones como `sslmode`, `channel_binding` y `connect_timeout`.

### Importante sobre `/health`

[`HealthController.php`]( /var/www/html/inventory-system/src/Controllers/HealthController.php:10) no intenta conectarse realmente a PostgreSQL. Solo verifica si existen variables de configuración mediante `Database::isConfigured()`.

Por tanto:

```json
{
  "status": "ok",
  "service": "inventory-system",
  "database": "configured"
}
```

significa “hay configuración disponible”, no necesariamente “la base de datos responde correctamente”.

## 4. Router y respuestas

[`Router.php`]( /var/www/html/inventory-system/src/Http/Router.php:9) es un router pequeño hecho manualmente.

Actualmente solo soporta `GET`:

```php
$router->get('/health', [new HealthController(), 'index']);
```

Si la ruta existe pero se utiliza otro método HTTP, devuelve `405 Method Not Allowed`. Si la ruta no existe, devuelve `404`.

[`Response.php`]( /var/www/html/inventory-system/src/Http/Response.php:9) permite devolver:

- HTML con `Response::html(...)`.
- JSON con `Response::json(...)`.
- Código HTTP y headers personalizados.

## 5. Controladores y vistas

`HomeController` prepara los datos de la página inicial:

```php
return Response::html(View::render('home', [
    'appName' => 'Sistema de ventas',
]));
```

Después [`View.php`]( /var/www/html/inventory-system/src/Views/View.php:12) busca la plantilla correspondiente dentro de `views/`.

La vista actual es [`views/home.php`]( /var/www/html/inventory-system/views/home.php:1), que muestra una pantalla de bienvenida sencilla.

## 6. Ejecución local

La forma recomendada es:

```bash
composer install
php -S localhost:8080 -t public public/index.php
```

También existe Docker. [`docker-compose.yml`]( /var/www/html/inventory-system/docker-compose.yml:1) levanta solamente PHP; no crea un PostgreSQL local.

La imagen instala:

- PHP 8.5.
- Composer.
- `pdo_pgsql`.

Esto está definido en [`Dockerfile`]( /var/www/html/inventory-system/Dockerfile:1).

## 7. Despliegue en Vercel

Vercel usa un contenedor separado definido en [`Dockerfile.vercel`]( /var/www/html/inventory-system/Dockerfile.vercel:1).

El archivo [`vercel.json`]( /var/www/html/inventory-system/vercel.json:1):

- Declara el servicio `inventory`.
- Usa runtime `container`.
- Utiliza `Dockerfile.vercel`.
- Envía todas las rutas al contenedor.

La configuración de PostgreSQL para Vercel debe colocarse como variables de entorno en el panel, nunca dentro del repositorio.

## Estado actual

El proyecto está correctamente preparado como esqueleto técnico:

- Composer válido.
- PHP 8.5 disponible.
- Todos los archivos PHP pasan la validación sintáctica.
- Las rutas `/`, `/health`, `/tree-json` y `/tree-html` forman parte del bootstrap actual.
- Los recursos estáticos se sirven desde `public/styles/` y `public/scripts/`.

La siguiente fase real sería implementar módulos de dominio: productos, categorías, clientes, movimientos de inventario, ventas, autenticación y migraciones de base de datos.
