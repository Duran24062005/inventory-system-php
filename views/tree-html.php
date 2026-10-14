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
    <nav class="route-links" aria-label="Rutas disponibles">
        <a href="/">Inicio</a>
        <a href="/health">Health</a>
        <a href="/tree-html">Tree HTML</a>
        <a href="/tree-json">Tree JSON</a>
    </nav>
    <pre class="project-tree"><?= htmlspecialchars($tree, ENT_QUOTES, 'UTF-8') ?></pre>
</main>
<script src="scripts/script.js"></script>
</body>
</html>
