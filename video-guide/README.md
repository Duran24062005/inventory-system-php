# Video-guía HyperFrames

Esta carpeta contiene la fuente editable y el render local de la guía vertical
del proyecto `Inventory System PHP`. Es independiente de la aplicación PHP y no
forma parte de su runtime.

## Requisitos

- Node.js 22 o superior.
- FFmpeg en `PATH`.
- HyperFrames disponible mediante `npx hyperframes`.

## Regenerar la narración y los subtítulos

Desde esta carpeta:

```bash
npx hyperframes tts audio/narration.txt --voice ef_dora --output audio/narration.wav
npx hyperframes transcribe audio/narration.wav --model small --language es
```

La transcripción queda en `audio/transcript.json`. Si `ef_dora` no está
disponible, consulta `npx hyperframes tts --list` y usa una voz española con
prefijo `e`.

## Validar y previsualizar

```bash
npx hyperframes doctor
npx hyperframes lint
npx hyperframes validate
npx hyperframes inspect --samples 15
npx hyperframes preview --port 3017
```

La vista de Studio se abre en la URL que indique la CLI, normalmente con la
forma `http://localhost:3017/#project/video-guide`.

## Renderizar

```bash
npx hyperframes render \
  --output renders/inventory-system-guide.mp4 \
  --fps 30 \
  --quality high \
  --strict
```

Verificación del archivo final:

```bash
ffprobe -v error -show_entries format=duration:stream=codec_type,width,height,r_frame_rate \
  -of default=noprint_wrappers=1 renders/inventory-system-guide.mp4
```

El render es una instantánea del código y documentación existentes al momento
de generarlo. Si cambian las rutas o el flujo de despliegue, actualiza
`SCRIPT.md`, `STORYBOARD.md` y `index.html` antes de regenerar.
