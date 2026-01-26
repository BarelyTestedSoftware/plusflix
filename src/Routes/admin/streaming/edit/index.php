<?php
/**
 * Route: /admin/streaming/edit
 * GET -> formularz edycji platformy
 * POST -> zapis edytowanej platformy
 */

use App\Controller\StreamingController;

/** @var \App\Service\Router $router */

$controller = new StreamingController();

if ($router->isGet()) {
    $id = $router->get('id');
    if (null !== $id) {
        $streaming = $controller->get($id)["streaming"];
        return [
            'template' => 'admin/streaming-form',
            'params' => ['router' => $router, 'streaming' => $streaming],
            'title' => 'Edytuj platformę streamingową',
            'bodyClass' => 'streaming-edit',
        ];
    }
}

if ($router->isPost()) {
    $id = $router->get('id');
    if (null !== $id) {
        $controller->update($id, $_POST, $router);
    }
    return null;
}
