<?php
/**
 * Route: /
 * Strona główna
 */

use App\Controller\ShowController;


$controller = new ShowController();
$data = $controller->getAll();
$shows = $data['shows'] ?? [];
$highlightedShow = null;

if (!empty($shows)) {

    $highlightedShow = end($shows);


    reset($shows);
}


return [
    'template' => 'index',
    'title' => 'Plusflix - Strona Główna',
    'params' => [
        'router' => $router,
        'shows' => $shows,
        'highlightedShow' => $highlightedShow
    ],
    'bodyClass' => 'index',
];