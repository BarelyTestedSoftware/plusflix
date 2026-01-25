<?php
/**
 * Front Controller - wszystko przechodzi przez ten plik
 * 
 * Dla PHP built-in server: php -S localhost:3000 -t public public/index.php
 */



$uri = $_SERVER['REQUEST_URI'];
$publicPath = __DIR__ . parse_url($uri, PHP_URL_PATH);

if (php_sapi_name() === 'cli-server') {
    if (is_file($publicPath) && !preg_match('/\.php$/', $publicPath)) {
        return false;
    }
}

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';

use App\Service\Router;
use App\Exception\NotFoundException;

try {
    $router = new Router();
    $view = $router->dispatch();
    
    if ($view) {
        echo $view;
    }
} catch (NotFoundException $e) {
    http_response_code(404);
    echo $router->render404();
} catch (\Exception $e) {
    http_response_code(500);
    echo '<h1>500 - Błąd serwera</h1>';
    if (ini_get('display_errors')) {
        echo '<pre>' . htmlspecialchars($e->getMessage()) . "\n" . $e->getTraceAsString() . '</pre>';
    }
}
