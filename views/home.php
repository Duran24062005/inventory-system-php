<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="styles/styles.css">
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
