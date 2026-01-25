<?php
/**
 * Route: /admin/streaming/add
 * POST -> zapis nowej platformy
 */

use App\Controller\StreamingController;

/** @var \App\Service\Router $router */

$controller = new StreamingController();

if ($router->isPost()) {
    $controller->store($_POST, $router);
    return null;
}

return [
    'template' => 'admin/streaming-form',
    'params' => ['router' => $router],
    'title' => 'Dodaj platformę streamingową',
    'bodyClass' => 'streaming-add',
];
