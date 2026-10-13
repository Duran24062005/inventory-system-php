# Inventory System PHP

Base de práctica para construir un sistema de ventas con PHP puro, MVC ligero,
Composer y PostgreSQL.

## Requisitos

- PHP 8.2 o superior para ejecución local.
- Composer 2.
- Docker y Docker Compose para ejecutar el servicio con `pdo_pgsql`.
- Una conexión PostgreSQL disponible en las variables de entorno.

## Configuración local

1. Copia `.env.example` a `.env` si todavía no existe.
2. Completa las variables PostgreSQL en `.env`. El archivo `.env` es local y no
   debe versionarse.
3. Instala las dependencias:

   ```bash
   composer install
   ```

4. Inicia el servidor integrado de PHP:

   ```bash
   php -S localhost:8080 -t public
   ```

## Ejecución con Docker

Docker levanta únicamente la aplicación PHP. No crea un contenedor PostgreSQL;
el servicio usa la conexión definida en `.env`.

```bash
docker compose up --build
```

La aplicación queda disponible en `http://localhost:8080` o en el puerto
definido por `APP_PORT`.

## Rutas iniciales

- `GET /`: página de bienvenida del sistema.
- `GET /health`: estado JSON básico de la aplicación y configuración de base de
  datos.

## Estructura

```text
public/       Punto de entrada HTTP
src/Config/   Configuración y conexión PDO
src/Http/     Request, Response y Router
src/Controllers/
src/Views/    Renderizador de vistas
views/        Plantillas HTML
docs/         Decisiones y planificación del proyecto
```

La conexión está centralizada en `Database::connection()`. Usa `DATABASE_URL`
como opción principal y puede utilizar las variables `PG*` como alternativa.
