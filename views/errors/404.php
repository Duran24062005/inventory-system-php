<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#08111f">
    <title>404 · Coordenada no encontrada</title>
    <style>
        :root {
            color-scheme: dark;
            --night: #08111f;
            --night-soft: #0e1b2d;
            --ink: #edf6ff;
            --muted: #8ea4bc;
            --line: rgba(168, 202, 230, 0.16);
            --cyan: #72e4ff;
            --cyan-soft: rgba(114, 228, 255, 0.14);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: var(--night);
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-width: 320px;
            min-height: 100vh;
            margin: 0;
            color: var(--ink);
            background: var(--night);
        }

        a {
            color: inherit;
        }

        .error-page {
            position: relative;
            display: grid;
            min-height: 100svh;
            place-items: center;
            padding: 32px 20px;
            overflow: hidden;
            isolation: isolate;
            background:
                linear-gradient(rgba(114, 228, 255, 0.045) 1px, transparent 1px),
                linear-gradient(90deg, rgba(114, 228, 255, 0.045) 1px, transparent 1px),
                radial-gradient(circle at 82% 14%, rgba(44, 113, 161, 0.28), transparent 32rem),
                var(--night);
            background-size: 42px 42px, 42px 42px, auto, auto;
        }

        .error-page::before,
        .error-page::after {
            position: absolute;
            z-index: -1;
            display: block;
            width: 42rem;
            height: 42rem;
            border: 1px solid rgba(114, 228, 255, 0.06);
            border-radius: 50%;
            content: "";
            pointer-events: none;
        }

        .error-page::before {
            top: -28rem;
            right: -12rem;
        }

        .error-page::after {
            bottom: -34rem;
            left: -18rem;
            width: 52rem;
            height: 52rem;
        }

        .error-card {
            display: grid;
            grid-template-columns: minmax(280px, 0.9fr) minmax(320px, 1.1fr);
            width: min(1080px, 100%);
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 28px;
            background: linear-gradient(135deg, rgba(19, 39, 61, 0.92), rgba(7, 17, 30, 0.94));
            box-shadow: 0 32px 100px rgba(0, 0, 0, 0.38), inset 0 1px rgba(255, 255, 255, 0.08);
            animation: card-in 760ms cubic-bezier(0.2, 0.75, 0.2, 1) both;
        }

        .error-copy {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: clamp(36px, 7vw, 84px);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            width: fit-content;
            margin: 0 0 28px;
            color: var(--cyan);
            font-family: "IBM Plex Mono", ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .eyebrow::before {
            width: 28px;
            height: 1px;
            background: var(--cyan);
            box-shadow: 0 0 14px var(--cyan);
            content: "";
        }

        h1 {
            max-width: 8ch;
            margin: 0;
            font-size: clamp(2.7rem, 6vw, 5.4rem);
            line-height: 0.95;
            letter-spacing: -0.075em;
        }

        h1 span {
            display: block;
            color: var(--cyan);
        }

        .error-description {
            max-width: 38ch;
            margin: 26px 0 0;
            color: var(--muted);
            font-size: clamp(1rem, 1.7vw, 1.15rem);
            line-height: 1.7;
        }

        .error-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 34px;
        }

        .error-actions a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 0 18px;
            border: 1px solid transparent;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 750;
            text-decoration: none;
            transition: transform 180ms ease, background-color 180ms ease, border-color 180ms ease;
        }

        .error-actions a:first-child {
            color: #06111d;
            background: var(--cyan);
            box-shadow: 0 0 24px rgba(114, 228, 255, 0.2);
        }

        .error-actions a:last-child {
            border-color: var(--line);
            color: var(--ink);
            background: rgba(255, 255, 255, 0.035);
        }

        .error-actions a:hover {
            transform: translateY(-2px);
        }

        .error-actions a:first-child:hover {
            background: #a5eeff;
        }

        .error-actions a:last-child:hover {
            border-color: rgba(114, 228, 255, 0.5);
            background: var(--cyan-soft);
        }

        .error-actions a:focus-visible {
            outline: 3px solid var(--cyan);
            outline-offset: 4px;
        }

        .error-art {
            position: relative;
            display: grid;
            min-height: 540px;
            place-items: center;
            overflow: hidden;
            border-left: 1px solid var(--line);
            background: radial-gradient(circle at center, rgba(114, 228, 255, 0.1), transparent 54%);
        }

        .error-art::after {
            position: absolute;
            right: 10%;
            bottom: 9%;
            width: 40%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--cyan), transparent);
            box-shadow: 0 0 18px rgba(114, 228, 255, 0.8);
            content: "";
            opacity: 0.55;
            transform: rotate(-30deg);
        }

        .orbit-art {
            width: min(92%, 520px);
            height: auto;
            overflow: visible;
        }

        .orbit-ring {
            fill: none;
            stroke: rgba(114, 228, 255, 0.25);
            stroke-width: 1;
        }

        .orbit-ring--accent {
            stroke: rgba(114, 228, 255, 0.7);
            stroke-dasharray: 8 12;
            animation: ring-drift 12s linear infinite;
            transform-origin: 240px 210px;
        }

        .orbit-core {
            fill: url(#core-gradient);
            filter: url(#soft-glow);
        }

        .orbit-track {
            animation: orbit 9s linear infinite;
            transform-origin: 240px 210px;
        }

        .orbit-satellite {
            fill: var(--cyan);
            filter: url(#soft-glow);
        }

        .signal {
            fill: none;
            stroke: var(--cyan);
            stroke-linecap: round;
            stroke-width: 2;
            stroke-dasharray: 1 13;
            animation: signal 2.8s ease-in-out infinite;
        }

        .svg-number {
            fill: rgba(237, 246, 255, 0.94);
            font-family: Inter, ui-sans-serif, system-ui, sans-serif;
            font-size: 74px;
            font-weight: 800;
            letter-spacing: -7px;
        }

        .art-label {
            position: absolute;
            right: 28px;
            bottom: 24px;
            color: rgba(142, 164, 188, 0.72);
            font-family: "IBM Plex Mono", ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 0.68rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        @keyframes card-in {
            from {
                opacity: 0;
                transform: translateY(18px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes orbit {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes ring-drift {
            to {
                transform: rotate(-360deg);
            }
        }

        @keyframes signal {
            0%,
            100% {
                opacity: 0.2;
                stroke-dashoffset: 0;
            }
            50% {
                opacity: 1;
                stroke-dashoffset: -28;
            }
        }

        @media (max-width: 760px) {
            .error-page {
                padding: 18px;
            }

            .error-card {
                grid-template-columns: 1fr;
            }

            .error-copy {
                order: 2;
                padding: 36px 28px 40px;
            }

            .error-art {
                order: 1;
                min-height: 330px;
                border-right: 0;
                border-bottom: 1px solid var(--line);
                border-left: 0;
            }

            .art-label {
                right: 20px;
                bottom: 16px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
<main class="error-page">
    <section class="error-card" aria-labelledby="error-title">
        <div class="error-copy">
            <p class="eyebrow">Sistema de ventas · señal perdida</p>
            <h1 id="error-title">Esta ruta <span>no existe.</span></h1>
            <p class="error-description">
                La coordenada que buscas no está disponible. Regresa al inicio o consulta el estado del sistema.
            </p>
            <nav class="error-actions" aria-label="Acciones de navegación">
                <a href="/">Volver al inicio</a>
                <a href="/health">Ver estado</a>
            </nav>
        </div>

        <div class="error-art" aria-hidden="true">
            <svg class="orbit-art" viewBox="0 0 480 420" role="img" aria-labelledby="orbit-title orbit-description">
                <title id="orbit-title">Coordenada 404 fuera de órbita</title>
                <desc id="orbit-description">Un núcleo luminoso con una pequeña señal orbitando a su alrededor.</desc>
                <defs>
                    <radialGradient id="core-gradient" cx="35%" cy="30%">
                        <stop offset="0" stop-color="#effcff" />
                        <stop offset="0.28" stop-color="#72e4ff" />
                        <stop offset="1" stop-color="#18618c" />
                    </radialGradient>
                    <filter id="soft-glow" x="-80%" y="-80%" width="260%" height="260%">
                        <feGaussianBlur stdDeviation="7" result="blur" />
                        <feMerge>
                            <feMergeNode in="blur" />
                            <feMergeNode in="SourceGraphic" />
                        </feMerge>
                    </filter>
                </defs>
                <ellipse class="orbit-ring" cx="240" cy="210" rx="180" ry="94" transform="rotate(-24 240 210)" />
                <ellipse class="orbit-ring orbit-ring--accent" cx="240" cy="210" rx="180" ry="94" transform="rotate(-24 240 210)" />
                <ellipse class="orbit-ring" cx="240" cy="210" rx="136" ry="70" transform="rotate(42 240 210)" />
                <circle class="orbit-core" cx="240" cy="210" r="70" />
                <text class="svg-number" x="193" y="232">404</text>
                <g class="orbit-track">
                    <circle class="orbit-satellite" cx="420" cy="210" r="8" />
                    <circle cx="420" cy="210" r="18" fill="none" stroke="rgba(114, 228, 255, 0.4)" />
                </g>
                <path class="signal" d="M74 108 C120 74 146 78 176 106" />
                <path class="signal" d="M302 338 C348 366 384 360 416 326" style="animation-delay: -1.1s" />
            </svg>
            <span class="art-label">navigation / 404</span>
        </div>
    </section>
</main>
</body>
</html>
