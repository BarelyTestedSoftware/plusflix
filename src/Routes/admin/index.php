<?php

use App\Controller\CategoryController;
use App\Controller\PersonController;
use App\Controller\ShowController;
use App\Controller\StreamingController;
use App\Model\Category;
use App\Model\Person;
use App\Model\Show;
use App\Model\Streaming;

/**
 * Route: /admin
 * Wybór podstrony administracyjnej
 * 
 * @var \App\Service\Router $router
 */

$header = 'Zarządzanie';

return [
    'template' => 'admin',
    'params' => [
        'router' => $router,
    ],
    'title' => 'Zarządzanie',
    'bodyClass' => 'admin-page',
];