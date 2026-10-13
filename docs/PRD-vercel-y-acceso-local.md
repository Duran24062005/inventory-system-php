# PRD: entrada HTTP local y despliegue en Vercel

## Problema y objetivo

Apache está sirviendo el directorio del proyecto como una carpeta porque el
checkout no tenía un `index.php` en su raíz. El código de la aplicación sí
existía en `public/index.php`, pero esa convención solo funciona cuando el
servidor se configura explícitamente con `public/` como document root.

El objetivo es que el proyecto:

- muestre la aplicación al abrirlo desde Apache bajo `/inventory-system/`;
- conserve `public/` como punto de entrada recomendado para el servidor PHP
  integrado y Docker;
- exponga un entrypoint específico para el runtime PHP de Vercel;
- deje documentados los puntos de edición manual, las variables y los límites
  operativos.

## Alcance

Incluye:

- un puente `index.php` en la raíz para instalaciones locales que apuntan al
  checkout completo;
- reglas `.htaccess` para evitar listados, proteger archivos internos y
  reescribir rutas Apache hacia el puente;
- un ejemplo de VirtualHost/Alias que sirve `public/` como document root,
  porque un cambio dentro del repositorio no puede habilitar `AllowOverride` en
  una instalación Apache externa;
- el comando del servidor PHP integrado y Docker con `public/index.php` como
  router para que `/health` no dependa de un archivo físico;
- normalización del prefijo de carpeta en `Request`, sin cambiar las rutas
  públicas (`/` y `/health`);
- `api/index.php` como función única para Vercel;
- `vercel.json` con `vercel-php@0.9.0` y una ruta de fallback hacia la función;
- `.vercelignore` para no enviar dependencias locales ni archivos de entorno;
- README y documentación operativa actualizados.

No incluye todavía productos, ventas, inventario, autenticación, migraciones ni
un proveedor de PostgreSQL. La función `/health` solo informa si hay variables
de conexión configuradas; no ejecuta una consulta contra la base de datos.

## Actores y flujo

- **Desarrollador local con Apache:** entra por `index.php` y reutiliza el
  bootstrap de `public/index.php`.
- **Desarrollador local con PHP/Docker:** continúa usando `public/index.php`
  como document root.
- **Vercel:** invoca `api/index.php`; el runtime instala Composer y ese
  entrypoint delega al mismo bootstrap de la aplicación.
- **PostgreSQL externo:** se configura mediante `DATABASE_URL` o las variables
  `PG*`/`POSTGRES_*` en el entorno de ejecución.

## Decisiones técnicas

1. No se duplica el bootstrap MVC. Los tres puntos de entrada delegan en
   `public/index.php`.
2. `Request` elimina únicamente el directorio que contiene el script actual.
   Así `/inventory-system/health` se convierte en `/health` en Apache, pero
   `/health` permanece intacto en Vercel o en el servidor PHP integrado.
3. Vercel usa una sola función PHP y un fallback global porque el router actual
   es el responsable de distinguir las rutas.
4. No se versionan credenciales ni `vendor/`. Composer debe instalar las
   dependencias en el build/runtime de Vercel.

## Validaciones y riesgos

- Verificar sintaxis PHP y `composer validate` antes de desplegar.
- Probar `/` y `/health` con el prefijo local y sin él.
- Configurar `DATABASE_URL` en Vercel antes de esperar el estado
  `configured`.
- La conexión PostgreSQL requiere un proveedor accesible desde Vercel y
  credenciales válidas; la configuración de routing no prueba conectividad.
- El filesystem de una función Vercel no debe usarse como almacenamiento
  persistente.

## Resultado esperado

El listado de directorios desaparece al abrir el proyecto por Apache, las
rutas iniciales siguen funcionando con el servidor PHP y el repositorio queda
con una configuración reproducible para crear un deployment de Vercel.
