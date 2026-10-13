# PRD: video-guía del proyecto con HyperFrames

## Problema y objetivo

El proyecto es una base de sistema de ventas en PHP puro, pero una persona que
llega por primera vez necesita recorrer varios archivos para entender dónde
modificar rutas, controladores, vistas, configuración y despliegue.

El objetivo es entregar una guía audiovisual vertical, breve y reproducible,
que explique ese recorrido sin presentar como terminadas funcionalidades que
todavía no existen.

## Alcance

Incluye:

- una composición HyperFrames independiente en `video-guide/`;
- formato vertical 1080×1920, aproximadamente 60 segundos y 30 FPS;
- narración sintética en español y subtítulos sincronizados;
- explicación del arranque local, rutas, controladores, vistas, PostgreSQL,
  Docker y Vercel;
- fuentes editables, guion, storyboard y comandos de regeneración;
- un MP4 renderizado para revisión local.

No incluye:

- cambios en el runtime PHP, sus rutas o sus vistas;
- implementación de productos, ventas, inventario o migraciones;
- ejecución de consultas contra PostgreSQL remoto;
- publicación en Vercel o envío del video a un servicio externo.

## Actores y audiencia

- **Desarrollador nuevo:** necesita saber qué archivo editar y qué validar.
- **Mantenedor del proyecto:** necesita una fuente audiovisual que pueda
  regenerarse cuando cambie la arquitectura.

## Guion funcional

La pieza seguirá seis escenas: introducción, preparación local, registro de
rutas, controlador y vista, base de datos y despliegue, y checklist final.
Los ejemplos de `/products` serán conceptuales y estarán rotulados como una
ampliación futura; las únicas rutas existentes que se mostrarán como reales
son `/` y `/health`.

## Decisiones visuales y técnicas

- Estilo claro técnico derivado de `views/home.php`.
- `IBM Plex Sans` para narración visual y `IBM Plex Mono` para código.
- Fondo `#f3f6fb`, texto `#172033`, panel `#ffffff`, borde `#dce3ef`,
  texto secundario `#59667e` y acento `#3c5ccf`.
- Entradas escalonadas y transiciones push/slide entre escenas.
- La narración se generará localmente con la voz española `ef_dora`; si la
  voz no está disponible, se usará otra voz española `e*` listada por la CLI.
- La transcripción se generará con el modelo `small` y el idioma `es`, sin
  usar modelos `.en`.

## Riesgos y límites

- Un render correcto no prueba la conexión real de PostgreSQL ni un deployment
  de Vercel; el video lo indicará como procedimiento, no como evidencia viva.
- La duración de la narración puede variar según el modelo TTS; los tiempos de
  escena se ajustarán después de generar y transcribir el audio.
- El modelo TTS puede requerir una descarga inicial; no se añadirá una API
  externa ni se expondrán credenciales si esa descarga falla.
- La composición debe evitar desbordes de texto en formato vertical y mantener
  los subtítulos lejos de los paneles de código.

## Criterios de aceptación

- HyperFrames `lint`, `validate` e `inspect --samples 15` terminan sin errores.
- La vista previa se puede abrir con la URL del Studio indicada por la CLI.
- El MP4 tiene resolución 1080×1920, 30 FPS, duración aproximada de 60 s y
  contiene una pista de audio.
- El contenido coincide con el código y la documentación actuales.
- No aparecen secretos, valores del `.env` ni afirmaciones de conectividad real.
