<?php

declare(strict_types=1);

namespace AlexiDg\InventorySystem\Views;

use RuntimeException;
use Throwable;

final class View
{
    public static function render(string $name, array $data = []): string
    {
        $path = dirname(__DIR__, 2) . '/views/' . $name . '.php';

        if (!is_file($path)) {
            throw new RuntimeException(sprintf('View not found: %s', $name));
        }

        extract($data, EXTR_SKIP);
        ob_start();

        try {
            require $path;

            return (string) ob_get_clean();
        } catch (Throwable $exception) {
            ob_end_clean();
            throw $exception;
        }
    }
}
