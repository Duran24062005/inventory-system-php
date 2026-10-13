# Guion de narración

## Metadata

- Idioma: español.
- Voz inicial: `ef_dora`.
- Duración objetivo: aproximadamente 60 segundos.
- Subtítulos: transcripción palabra por palabra generada desde el audio.

## Texto

Este es Inventory System PHP: una base de sistema de ventas construida con PHP
puro, MVC ligero, Composer y PostgreSQL. Para empezar, copia `.env.example` a
`.env`, instala dependencias con `composer install` y arranca PHP o Docker.

La entrada pública está en `public/index.php`. Para añadir una ruta, crea su
controlador y regístralo en el router; por ejemplo, `/products` sería una
ampliación futura. Hoy solo existen `/` y `/health`.

El controlador devuelve una respuesta y renderiza una vista desde `views`.
Escapa los datos con `htmlspecialchars` y mantén las consultas fuera de la
vista. La conexión central vive en `Database` y usa `DATABASE_URL` o variables
`PG`.

Para desplegar, Docker y `Dockerfile.vercel` separan los entornos; Vercel usa
el servicio contenedorizado documentado en `vercel.json`. Antes de cada cambio,
ejecuta `php -l`, `composer validate`, prueba `/health` y actualiza la
documentación. Productos, ventas e inventario son la siguiente fase.
