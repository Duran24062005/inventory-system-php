<?php

declare(strict_types=1);

namespace AlexiDg\InventorySystem\Controllers;

use AlexiDg\InventorySystem\Config\Database;
use AlexiDg\InventorySystem\Http\Response;

final class HealthController
{
    public function index(): Response
    {
        return Response::json([
            'status' => 'ok',
            'service' => 'inventory-system',
            'database' => Database::isConfigured() ? 'configured' : 'not_configured',
        ]);
    }
}
