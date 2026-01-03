<?php
/**
 * Route: /
 * Strona główna
 */


return [
    'template' => 'index',
    'params' => ['router' => $router, 'hello' => "Witamy w Plusflix!"],
    'title' => 'Plusflix',
    'bodyClass' => 'index',

];