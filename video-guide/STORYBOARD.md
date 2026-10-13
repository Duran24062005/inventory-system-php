# Storyboard

## Parámetros

- Canvas: 1080×1920.
- FPS: 30.
- Duración final: 56 segundos, ajustada al audio de 55,936 s.
- Transición principal: push horizontal de derecha a izquierda.
- Audio: `audio/narration.wav` con marcas de tiempo desde `audio/transcript.json`.

## Escenas

| Tiempo objetivo | Escena | Elementos visuales | Movimiento |
| --- | --- | --- | --- |
| 0–5 s | Introducción | Nombre del proyecto, sello PHP/MVC/PostgreSQL y árbol mínimo | Título desde abajo, etiquetas con stagger y regla vertical |
| 5–14 s | Preparar entorno | `.env.example`, `composer install`, PHP integrado y Docker | Comandos entran como líneas de terminal; acento hace un pulso |
| 14–27 s | Añadir ruta | `public/index.php`, `Router`, ejemplo conceptual `/products` y rutas reales | Árbol se desplaza; línea de flujo conecta entrada con router |
| 27–38 s | Controlador y vista | `src/Controllers`, `Response::html`, `View::render`, `views` | Panel de código entra desde la derecha; resaltado escala suavemente |
| 38–51 s | Datos y despliegue | `Database`, `DATABASE_URL`, `Dockerfile`, `Dockerfile.vercel`, `vercel.json` | Dos columnas local/Vercel se revelan con push escalonado |
| 51–60 s | Checklist | `php -l`, `composer validate`, `/health`, documentación y nota de alcance | Checks aparecen uno a uno; cierre con fade final |

## Reglas de contenido

- `/` y `/health` se presentan como rutas existentes.
- `/products` se presenta como ejemplo de una futura modificación, nunca como
  una ruta ya implementada.
- No se muestran credenciales ni contenido de `.env`.
- No se afirma que Vercel o PostgreSQL hayan sido probados en vivo.
- Los subtítulos ocupan una sola línea o dos líneas cortas, con contraste AA,
  y se eliminan de forma determinista al terminar cada grupo.

## Asset audit

| Asset | Origen | Tratamiento |
| --- | --- | --- |
| Paleta | `views/home.php` | Copiada a `DESIGN.md` y usada literalmente |
| Rutas | `public/index.php` | Código visible actual |
| Router | `src/Http/Router.php` | Código visible actual |
| Vista | `src/Views/View.php`, `views/` | Código visible actual |
| Base de datos | `src/Config/Database.php` | Solo nombres de variables y flujo |
| Despliegue | `Dockerfile`, `Dockerfile.vercel`, `vercel.json` | Código de configuración sin secretos |
