<?php

declare(strict_types=1);

namespace AlexiDg\InventorySystem\Http;

use AlexiDg\InventorySystem\Views\View;

final class Router
{
    /** @var array<int, array{method: string, path: string, handler: callable}> */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes[] = [
            'method' => 'GET',
            'path' => $path,
            'handler' => $handler,
        ];
    }

    public function dispatch(Request $request): Response
    {
        $pathMatched = false;

        foreach ($this->routes as $route) {
            if ($route['path'] !== $request->path) {
                continue;
            }

            $pathMatched = true;

            if ($route['method'] !== $request->method) {
                continue;
            }

            return ($route['handler'])($request);
        }

        if ($pathMatched) {
            return Response::json(
                ['error' => 'Method not allowed'],
                405,
                ['Allow' => 'GET'],
            );
        }

        return Response::html(View::render('errors/404'), 404);
    }
}
