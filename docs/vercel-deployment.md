# Despliegue en Vercel

Este archivo documenta únicamente la configuración de Vercel para este
proyecto. Vercel ejecuta PHP mediante el runtime comunitario `vercel-php`; no
ejecuta el `Dockerfile` como un servidor Apache persistente.

## Cómo está conectado el proyecto

```text
petición HTTP
    |
    v
vercel.json: /(.*) -> /api/index.php
    |
    v
api/index.php
    |
    v
public/index.php -> Router -> Controller -> View
```

`api/index.php` es deliberadamente pequeño: evita mantener un segundo
bootstrap y conserva el mismo comportamiento que el desarrollo local.

## Archivos de configuración

- `vercel.json`: declara `vercel-php@0.9.0` para `api/index.php` y redirige
  todas las rutas al router PHP.
- `api/index.php`: función PHP de entrada.
- `.vercelignore`: excluye `vendor/`, archivos de entorno, documentación y
  archivos de Docker que no son necesarios en el bundle.
- `composer.json` y `composer.lock`: dependencias PHP que el runtime instala.
- `public/index.php`: bootstrap compartido.

No edites `vercel.json` para poner secretos. Las variables se configuran en el
proyecto de Vercel.

## Requisitos previos

1. El repositorio debe estar en GitHub, GitLab o Bitbucket y el proyecto de
   Vercel debe apuntar a la raíz del repositorio, no a `public/` ni a `api/`.
2. Tener una base PostgreSQL externa accesible desde Vercel si el siguiente
   módulo requiere persistencia.
3. Tener Node.js/npm para instalar el CLI de Vercel y PHP/Composer para las
   comprobaciones locales.

## Variables de entorno

En Vercel, crea las variables en Preview y Production según el entorno que
quieras utilizar:

| Variable | Obligatoria ahora | Uso |
| --- | --- | --- |
| `APP_ENV` | No | Identifica el entorno (`preview` o `production`) |
| `APP_DEBUG` | No | Debe ser `false` en Production |
| `DATABASE_URL` | Solo para conectar DB | URL PostgreSQL con `sslmode=require` cuando el proveedor lo exija |

También se aceptan `DATABASE_URL_UNPOOLED`, `POSTGRES_URL`,
`POSTGRES_URL_NON_POOLING` o las variables separadas `PG*`/`POSTGRES_*`, pero
conviene elegir un solo contrato por entorno. No copies `.env` al repositorio ni
pegues valores reales en `.env.example`.

## Publicación con CLI

Desde la raíz del proyecto:

```bash
npm install --global vercel
vercel login
vercel link
vercel env add DATABASE_URL preview
vercel env add DATABASE_URL production
vercel deploy
vercel deploy --prod
```

`vercel deploy` crea un Preview. Revisa `/` y `/health` en esa URL antes de
publicar Production. Si se usa CI, guarda `VERCEL_TOKEN`, `VERCEL_ORG_ID` y
`VERCEL_PROJECT_ID` como secretos del proveedor; nunca en el repositorio.

Para un flujo de build explícito:

```bash
vercel pull --yes --environment=preview
vercel build
vercel deploy --prebuilt
```

No ejecutes `vercel deploy --prod` hasta haber validado el Preview y revisado
las variables de Production.

## Publicación desde Git

También puedes importar el repositorio en el dashboard de Vercel. Configura:

- **Root Directory:** raíz del repositorio.
- **Framework Preset:** `Other` si Vercel no detecta otro framework.
- **Build Command:** vacío; `vercel-php` instala Composer para la función.
- **Output Directory:** vacío.
- **Install Command:** no agregues un `npm install` ficticio; este proyecto no
  tiene `package.json`.

Después agrega las variables para cada entorno y crea un deployment Preview.

## Diagnóstico posterior al despliegue

- **Se muestra un listado o 404:** confirma que el Root Directory es la raíz y
  que `api/index.php` y `vercel.json` están versionados.
- **Error de Composer:** revisa `composer.json`, `composer.lock`, la versión de
  PHP requerida y los logs de build.
- **`/health` muestra `not_configured`:** falta `DATABASE_URL` (o un contrato
  equivalente) en el entorno del deployment.
- **Error de conexión PostgreSQL:** revisa host, usuario, contraseña, SSL,
  allowlist y que el proveedor acepte conexiones desde Vercel.
- **Falla durante la ejecución:** consulta los Runtime Logs de la función;
  no imprimas la URL de conexión ni contraseñas.

## Límites importantes

- El runtime PHP es una función serverless: no hay un proceso Apache
  permanente ni estado en memoria garantizado entre solicitudes.
- El filesystem de Vercel es de solo lectura durante la ejecución, salvo el
  espacio temporal de `/tmp`; no lo uses para guardar inventario o archivos de
  usuarios.
- El `Dockerfile` sirve para reproducir el entorno local con `pdo_pgsql`; no
  es el mecanismo de ejecución de este despliegue.
- Un deployment exitoso demuestra que Vercel construyó y expuso la función,
  pero no demuestra por sí solo que PostgreSQL tenga el esquema esperado ni
  que las operaciones futuras de inventario funcionen.

## Fuentes y fecha de revisión

La configuración sigue la documentación de runtimes de Vercel y el README del
runtime PHP consultados el 13 de octubre de 2026:

- https://vercel.com/docs/functions/runtimes
- https://vercel.com/docs/functions/configuring-functions/runtime
- https://github.com/vercel-community/php
