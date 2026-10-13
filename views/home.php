<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #172033;
            background: #f3f6fb;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
        }

        main {
            width: min(680px, calc(100% - 40px));
            padding: 48px;
            background: #ffffff;
            border: 1px solid #dce3ef;
            border-radius: 20px;
            box-shadow: 0 18px 50px rgba(32, 53, 88, 0.10);
        }

        h1 {
            margin: 0 0 16px;
            font-size: clamp(2rem, 5vw, 3.5rem);
            letter-spacing: -0.05em;
        }

        p {
            color: #59667e;
            line-height: 1.7;
        }

        code {
            padding: 3px 7px;
            border-radius: 6px;
            background: #edf2f8;
        }
    </style>
</head>
<body>
<main>
    <p>Base de práctica en PHP puro</p>
    <h1><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></h1>
    <p>
        La aplicación está lista para crecer por módulos. El endpoint
        <code>/health</code> confirma el estado básico del servicio.
    </p>
</main>
</body>
</html>
