<?php

declare(strict_types=1);

namespace AlexiDg\InventorySystem\Http;

final readonly class Request
{
    private function __construct(
        public string $method,
        public string $path,
    ) {
    }

    public static function fromGlobals(): self
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH);
        $path = is_string($path) && $path !== '' ? $path : '/';
        $path = self::removeScriptDirectory($path, (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
        $path = '/' . trim($path, '/');

        return new self(
            strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET'),
            $path === '//' ? '/' : $path,
        );
    }

    private static function removeScriptDirectory(string $path, string $scriptName): string
    {
        $scriptPath = parse_url($scriptName, PHP_URL_PATH);

        if (!is_string($scriptPath) || $scriptPath === '') {
            return $path;
        }

        $basePath = rtrim(str_replace('\\', '/', dirname($scriptPath)), '/');

        if ($basePath === '' || $basePath === '.') {
            return $path;
        }

        if ($path === $basePath) {
            return '/';
        }

        if (str_starts_with($path, $basePath . '/')) {
            return substr($path, strlen($basePath)) ?: '/';
        }

        return $path;
    }
}
