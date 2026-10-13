# Base PHP para sistema de ventas

## Resumen

Configurar el proyecto como una aplicación de PHP puro con MVC ligero, Composer y PostgreSQL. El primer entregable será únicamente la estructura funcional de arranque; todavía no se implementarán productos, ventas ni inventario.

Docker levantará solo la aplicación PHP y se conectará al PostgreSQL definido en `.env`. No se agregará un contenedor de base de datos local.

## Cambios principales

- Crear una estructura base con `public/index.php`, controladores, configuración, vistas y rutas.
- Añadir configuración de conexión PDO PostgreSQL:
  - `DATABASE_URL` como variable principal.
  - Compatibilidad con `PGHOST`, `PGPORT`, `PGUSER`, `PGDATABASE` y `PGPASSWORD`.
  - Interfaz centralizada `Database::connection(): PDO`.
- Incorporar `vlucas/phpdotenv` para cargar variables en ejecuciones locales.
- Crear `Dockerfile` con PHP, Composer y la extensión `pdo_pgsql`.
- Crear `docker-compose.yml` con un único servicio `app`, puerto configurable y montaje del proyecto.
- Completar `.env.example` usando los nombres existentes en `.env`, pero únicamente con valores de ejemplo o marcadores; nunca se copiarán credenciales reales.
- Actualizar `.gitignore` para excluir `.env`, `vendor/`, logs y archivos locales.
- Añadir una página inicial y endpoints básicos:
  - `GET /`: pantalla de bienvenida del sistema.
  - `GET /health`: respuesta JSON para comprobar que la aplicación está activa.
- Crear un PRD breve en `docs/` documentando objetivo, arquitectura, conexión PostgreSQL, alcance inicial, riesgos y evolución prevista hacia productos, clientes, ventas e inventario.

## Validaciones

- `composer validate`.
- Regeneración y comprobación del autoload PSR-4.
- Validación sintáctica de todos los archivos PHP.
- `docker compose config`.
- Construcción correcta de la imagen.
- Confirmación de que el contenedor tiene `pdo_pgsql`.
- Prueba de `/` y `/health` mediante el puerto publicado.
- Prueba opcional de conexión PostgreSQL con `SELECT 1`, sin modificar datos.

## Supuestos

- La base de datos actual definida en `.env` seguirá siendo la fuente PostgreSQL utilizada durante la práctica.
- Docker solo ejecutará la aplicación; no se crearán tablas, migraciones, semillas ni un servicio PostgreSQL adicional en esta etapa.
- `.env` permanecerá local y no se incluirá en Git ni en la documentación.
- Se usará PHP 8.5 como referencia local y una imagen Docker compatible con PHP moderno y `pdo_pgsql`.
- No se crearán commits todavía; el directorio `.git` actual no contiene historial utilizable.
