# Design: Inventory System PHP — guía audiovisual

## Style Prompt

Guía técnica clara, precisa y accesible para una persona que mantiene una
aplicación PHP pequeña. La pieza debe sentirse como un editor de código
ordenado convertido en tutorial vertical: jerarquía fuerte, paneles luminosos,
líneas de conexión y movimiento funcional. La interfaz actual es la referencia
visual; no usar estética de producto terminado ni dashboards inventados.

## Colors

| Rol | Color |
| --- | --- |
| Canvas | `#f3f6fb` |
| Foreground | `#172033` |
| Panel | `#ffffff` |
| Border | `#dce3ef` |
| Muted text | `#59667e` |
| Accent | `#3c5ccf` |
| Code background | `#edf2f8` |

## Typography

- `IBM Plex Sans`: títulos, etiquetas y explicación; pesos 500, 700 y 800.
- `IBM Plex Mono`: rutas, comandos, nombres de archivo y fragmentos de código.
- Títulos de 64–88 px; texto explicativo mínimo de 24 px; código mínimo de
  22 px para conservar legibilidad en 1080×1920.
- Números de paso con `font-variant-numeric: tabular-nums`.

## Composition

- Canvas vertical 1080×1920.
- Contenido principal en la zona superior y central, con padding lateral de
  72 px.
- Subtítulos en la zona inferior media, sin cubrir el código principal.
- Cada escena tiene tres capas: tratamiento de fondo, contenido principal y
  una línea/acento estructural.
- Código y árboles de archivos se representan como tarjetas, no como capturas
  dependientes de un servidor local.

## Motion

- Entradas con `power3.out`, `expo.out`, `back.out(1.4)` y `sine.inOut` según
  el rol del elemento.
- Staggers cortos, con la etiqueta de paso antes que el título y el código.
- Transición principal push/slide de 0.45–0.60 s entre escenas.
- Movimiento ambiental mínimo: regla vertical, brillo radial y desplazamiento
  lento de un patrón de líneas.
- No usar `Math.random()`, `Date.now()`, `repeat: -1` ni lógica asíncrona.

## What NOT to Do

- No inventar módulos, tablas, usuarios ni pantallas que aún no existen.
- No mostrar valores reales de `.env`, URLs de conexión o contraseñas.
- No usar gradientes neón, fondos oscuros genéricos ni tarjetas repetidas sin
  relación con el contenido.
- No saturar una escena con más de un bloque de código principal.
- No usar saltos de línea HTML forzados para el texto narrativo.
