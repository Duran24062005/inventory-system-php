<?php

declare(strict_types=1);

namespace AlexiDg\InventorySystem\Controllers;

use AlexiDg\InventorySystem\Http\Response;
use AlexiDg\InventorySystem\Views\View;

final class HomeController
{
    public function index(): Response
    {
        return Response::html(View::render('home', [
            'appName' => 'Sistema de ventas',
        ]));
    }
}
