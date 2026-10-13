# Despliegue en Vercel

Este archivo es la referencia operativa del despliegue de este repositorio.
La aplicación se publica como un único servicio contenedorizado de Vercel.
La imagen instala PHP, `pdo_pgsql`, Composer y las dependencias bloqueadas; al
recibir tráfico inicia el servidor integrado de PHP con `public/index.php`.

## Qué estaba ocurriendo

El commit anterior mezclaba dos modelos de ejecución:

1. `vercel.json` declaraba el builder comunitario `vercel-php@0.9.0` como si el
   proyecto fuera una función PHP.
2. El proyecto de Vercel estaba siendo procesado como un servicio de
   contenedor.

El builder de funciones no le entregaba a ese servicio un `entrypoint` de
contenedor. Por eso el build terminaba con:

```text
Container service must specify an entrypoint
```

La configuración actual elimina esa mezcla: declara el servicio y su
`Dockerfile.vercel` explícitamente.

## Flujo de una petición

```text
petición HTTP
    |
    v
vercel.json: rewrite /(.*) -> service inventory
    |
    v
Dockerfile.vercel: php -S ... -t public public/index.php
    |
    v
public/index.php -> Router -> Controller -> View
```

`api/index.php` se conserva como puente pequeño para usos compatibles y
pruebas directas, pero ya no es el entrypoint de Vercel.

## Archivos que se deben entender antes de editar

- `vercel.json`: declara el servicio `inventory`, el runtime `container`, el
  entrypoint y el rewrite público.
- `Dockerfile.vercel`: imagen específica para Vercel. No reemplaza el
  `Dockerfile` usado por Docker Compose local.
- `composer.json` y `composer.lock`: contrato de dependencias PHP que se
  instala durante la construcción de la imagen.
- `public/index.php`: bootstrap compartido y punto de entrada HTTP.
- `.vercelignore`: evita enviar `.git`, `vendor/`, secretos, documentación y
  configuración local innecesaria al build.
- `.env.example`: referencia de nombres de variables; nunca contiene valores
  reales.

No pongas secretos en `vercel.json`, `Dockerfile.vercel` ni en el repositorio.
Las variables se configuran en Vercel por entorno.

## Configuración obligatoria en el dashboard

El archivo por sí solo no puede cambiar la opción de Framework del proyecto ya
creado. En **Project Settings > Build and Deployment** configura:

1. **Framework Preset:** `Services`.
2. **Root Directory:** raíz del repositorio, donde están `vercel.json` y
   `Dockerfile.vercel`.
3. **Build Command:** déjalo vacío; la construcción ocurre en el Dockerfile.
4. **Install Command:** déjalo vacío; Composer se ejecuta dentro de la
   imagen.
5. **Output Directory:** déjalo vacío.

El proyecto solo se procesa como Services cuando el Framework está en
`Services` y existe la clave `services` en `vercel.json`. No agregues otra
configuración de `functions` al mismo archivo: los campos de build de una
función y los de Services son modelos distintos.

## Variables de entorno

En **Project Settings > Environment Variables**, crea las variables en
Preview y Production según corresponda:

| Variable | Obligatoria ahora | Uso |
| --- | --- | --- |
| `APP_ENV` | No | Identifica el entorno (`preview` o `production`). |
| `APP_DEBUG` | No | Debe ser `false` en Production. |
| `DATABASE_URL` | Solo para conectar DB | URL PostgreSQL; agrega `sslmode=require` cuando el proveedor lo exija. |

También se aceptan `DATABASE_URL_UNPOOLED`, `POSTGRES_URL`,
`POSTGRES_URL_NON_POOLING` o las variables separadas `PG*`/`POSTGRES_*`, pero
conviene mantener un solo contrato por entorno. No copies `.env` al
repositorio ni pegues valores reales en `.env.example`.

## Publicación con Git

1. Guarda los cambios y súbelos al repositorio conectado. La compilación usa
   la raíz del checkout.
2. Espera un deployment Preview.
3. Comprueba `GET /` y `GET /health` en la URL Preview.
4. Solo después promueve el Preview o publica Production.

Si el dashboard sigue mostrando `vercel-php@0.9.0`, está usando un commit
anterior o una configuración de build antigua. Verifica que el deployment
incluya `vercel.json` y `Dockerfile.vercel` desde el commit actual.

## Publicación con CLI

Instala una versión actual del CLI antes de validar o publicar:

```bash
npm install --global vercel@latest
vercel --version
vercel login
vercel link
vercel deploy
```

`vercel deploy` crea un Preview. Para Production, primero valida el Preview y
luego ejecuta:

```bash
vercel deploy --prod
```

Para revisar el artefacto local sin publicar:

```bash
vercel build
```

El build puede necesitar Docker disponible si se prueba localmente el flujo de
contenedores. No ejecutes `vercel deploy --prod` desde una sesión no vinculada
al proyecto correcto. No se debe crear un vínculo automático dentro de otro
checkout.

## Diagnóstico posterior al despliegue

- **`Container service must specify an entrypoint`:** confirma que el commit
  contiene `Dockerfile.vercel`, que `vercel.json` tiene
  `services.inventory.entrypoint` y que el Framework Preset es `Services`.
- **Se muestra un listado o un 404:** confirma que el Root Directory es la
  raíz y que el rewrite apunta al servicio `inventory`.
- **Error de Composer:** revisa `composer.json`, `composer.lock` y los logs de
  build; las dependencias se instalan dentro de la imagen.
- **`/health` muestra `not_configured`:** falta `DATABASE_URL` (o un contrato
  equivalente) en el entorno de ese deployment.
- **Error de conexión PostgreSQL:** revisa host, usuario, contraseña, SSL,
  allowlist y que el proveedor acepte conexiones desde Vercel.
- **Falla durante la ejecución:** consulta Runtime Logs; no imprimas la URL
  de conexión ni contraseñas.

## Límites importantes

- El contenedor es una función stateless: no hay un proceso Apache permanente
  ni memoria persistente entre instancias.
- El filesystem de Vercel no es almacenamiento persistente; usa PostgreSQL u
  otro servicio externo para inventario y archivos.
- Vercel enruta el tráfico al puerto indicado por `PORT`; el Dockerfile usa
  `3000` como valor local por defecto.
- Las imágenes contenedorizadas no sustituyen una base de datos. Un build
  exitoso solo demuestra que la imagen se construyó y que el proceso puede
  arrancar.
- Las funciones contenedorizadas no son la opción adecuada si el proyecto
  necesita Secure Compute o una IP de salida estática.

## Fuentes y fecha de revisión

Configuración revisada el 13 de octubre de 2026:

- [Vercel: Does Vercel support Docker deployments?](https://vercel.com/kb/guide/does-vercel-support-docker-deployments)
- [Vercel: The Complete Guide to Vercel Services](https://vercel.com/kb/guide/vercel-services)
- [Vercel: Functions runtimes](https://vercel.com/docs/functions/runtimes)
- [Runtime PHP comunitario](https://github.com/vercel-community/php)
