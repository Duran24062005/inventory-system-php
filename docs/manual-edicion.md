# Guía de edición manual

Esta guía indica dónde iniciar una modificación y qué límites tiene cada
parte. La aplicación usa PHP puro, Composer, un router pequeño y vistas PHP;
no hay un framework que genere archivos por detrás.

## Orden recomendado para empezar

1. Revisa `README.md` y `docs/init.md` para conocer el alcance actual.
2. Copia `.env.example` a `.env` solo en local y completa la conexión
   PostgreSQL. Nunca pongas credenciales en Git.
3. Identifica si el cambio es de HTTP, negocio, datos o presentación.
4. Cambia el archivo dueño de esa responsabilidad y prueba la ruta afectada.
5. Si cambia una ruta, variable, permiso, integración o flujo de despliegue,
   actualiza la documentación correspondiente.

## Mapa de archivos

| Necesidad | Archivo o carpeta | Qué editar |
| --- | --- | --- |
| Entrada HTTP común | `public/index.php` | Bootstrap, carga de `.env` y registro de rutas |
| Entrada Apache bajo una carpeta | `index.php` | Solo el puente; no dupliques lógica aquí |
| Reescritura Apache local | `.htaccess` | Desactiva listados, bloquea archivos internos y envía rutas al puente |
| Document root recomendado | `public/.htaccess` y `docs/apache-local.conf.example` | Envía rutas desconocidas a `public/index.php` |
| Entrada Vercel | `api/index.php` | Solo el puente hacia `public/index.php` |
| Rutas y métodos | `src/Http/Router.php` | Registro y despacho de rutas |
| Lectura de la petición | `src/Http/Request.php` | Método, URI y eliminación del prefijo local |
| Respuestas | `src/Http/Response.php` | HTML, JSON, status y headers |
| Controladores | `src/Controllers/` | Orquestación de cada caso de uso HTTP |
| Conexión PostgreSQL | `src/Config/Database.php` | DSN y variables de conexión |
| Render de vistas | `src/Views/View.php` | Resolución segura de plantillas |
| HTML visible | `views/` | Marcado y estilos de cada pantalla |
| Dependencias PHP | `composer.json` | Requisitos; después ejecuta `composer update` de forma consciente |
| Variables de ejemplo | `.env.example` | Nombres y valores ficticios, nunca secretos |
| Despliegue Vercel | `vercel.json` y `docs/vercel-deployment.md` | Runtime, routing y procedimiento de publicación |

## Añadir una ruta GET

1. Crea o reutiliza un controlador en `src/Controllers/`.
2. Devuelve `Response::html(...)` o `Response::json(...)`.
3. Registra la ruta en `public/index.php`:

```php
$router->get('/products', [new ProductController(), 'index']);
```

4. Si la ruta necesita datos, usa `Database::connection()` en una capa de
   aplicación clara; no pongas consultas SQL dentro de la vista.
5. Actualiza la sección de rutas del README si la URL forma parte del contrato
   del sistema.

El router actual solo implementa `GET`. Para `POST`, `PUT` o `DELETE` hay que
añadir el método al router, declarar el header `Allow` correspondiente y
documentar validación, errores y permisos antes de usarlo.

## Cambiar una pantalla

Las plantillas están en `views/`. Los datos se pasan desde el controlador:

```php
return Response::html(View::render('home', [
    'appName' => 'Sistema de ventas',
]));
```

Escapa cualquier valor que provenga de una petición o de la base de datos con
`htmlspecialchars` antes de imprimirlo en HTML. Si la pantalla requiere
JavaScript, CSS o imágenes, documenta su ubicación y recuerda que Vercel solo
servirá archivos estáticos si se dejan fuera del fallback dinámico de
`vercel.json`.

## Cambiar la base de datos

La aplicación acepta primero una URL PostgreSQL (`DATABASE_URL`,
`DATABASE_URL_UNPOOLED`, `POSTGRES_URL` o `POSTGRES_URL_NON_POOLING`) y como
alternativa variables separadas (`PGHOST`, `PGDATABASE`, `PGUSER`,
`PGPASSWORD`, `PGPORT`).

No ejecutes semillas ni migraciones contra una base remota sin revisar antes
el entorno y obtener autorización. La función `/health` no sustituye una
prueba real de conectividad.

## Comprobaciones mínimas después de editar

```bash
composer validate
find . -path './vendor' -prune -o -name '*.php' -print0 \
  | xargs -0 -n1 php -l
php -S localhost:8080 -t public public/index.php
```

`public/index.php` es el router del servidor integrado. Sin ese último
argumento, una ruta virtual como `/health` devuelve 404.

En otra terminal comprueba `/` y `/health`. Para Apache, comprueba también la
URL con el prefijo de la carpeta, por ejemplo `/inventory-system/health`.

Si Apache muestra 404 para `/health` o todavía lista `docs/` y `src/`, su
VirtualHost no está leyendo `.htaccess` o sigue apuntando al checkout completo.
Apunta el document root a `public/` y permite `AllowOverride All` usando
[`docs/apache-local.conf.example`](apache-local.conf.example). La portada raíz
es un puente de compatibilidad; no reemplaza esa configuración segura.

En Ubuntu, después de instalar o habilitar el sitio, valida y recarga Apache:

```bash
sudo a2enmod rewrite
sudo apache2ctl configtest
sudo systemctl reload apache2
```

Antes de subir a Vercel, sigue exclusivamente
[`docs/vercel-deployment.md`](vercel-deployment.md), porque allí se separan
las variables locales, Preview y Production.
