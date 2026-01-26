<?php
/**
 * Route: /admin/streaming
 * GET - wyświetlenie listy platform streamingowych
 */

use App\Controller\StreamingController;
use App\Model\Streaming;

/** @var \App\Service\Router $router */

$controller = new StreamingController();
$streamingsObj = $controller->getAll()["streamings"];

$streamingsData = array_map(fn($streaming) => $streaming->toArray(), $streamingsObj);

if ($router->isGet() && null !== $router->get('id')) {
    $id = (int) $router->get('id');
    $streamingsData = array_values(array_filter($streamingsData, fn($row) => (int)($row['id'] ?? 0) === $id));
}

return [
    'template' => 'admin-table',
    'params' => [
        'router' => $router,
        'table_column_names' => ['ID', 'Nazwa'],
        'data' => $streamingsData,
        'header' => 'Lista platform streamingowych',
    ],
    'title' => 'Platformy streamingowe',
    'bodyClass' => 'streaming-list',
];
