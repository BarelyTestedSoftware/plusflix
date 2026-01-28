<?php

use App\Controller\RatingController;
use App\Model\Rating;
/** @var \App\Service\Router $router */

return [
    'template' => 'rate',
    'title' => 'Oceń film',
    'params' => ["router" => $router, "id" => $router->get('id')],
];