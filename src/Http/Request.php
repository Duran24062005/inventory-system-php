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
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = '/' . trim($path, '/');

        return new self(
            strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET'),
            $path === '//' ? '/' : $path,
        );
    }
}
