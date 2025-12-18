<?php
namespace App\Service;

use App\Exception\NotFoundException;

class Router
{
    private string $routesPath;
    private string $method;
    private string $uri;

    public function __construct()
    {
        $this->routesPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Routes';
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $this->parseUri();
    }

    private function parseUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = trim($uri, '/');
        
        if (strpos($uri, 'index.php') === 0) {
            $uri = substr($uri, strlen('index.php'));
            $uri = trim($uri, '/');
        }
        
        return $uri;
    }

    public function dispatch(): ?string
    {
        $routeFile = $this->resolveRouteFile();
        
        if (!$routeFile) {
            throw new NotFoundException("Route not found: /{$this->uri}");
        }

        $router = $this;
        $result = require $routeFile;

        if ($result === null) {
            return null;
        }

        if (isset($result['raw'])) {
            return $result['raw'];
        }

        return $this->renderWithLayout($result);
    }

    private function resolveRouteFile(): ?string
    {
        // Pusta ścieżka = index.php
        if ($this->uri === '') {
            $file = $this->routesPath . DIRECTORY_SEPARATOR . 'index.php';
            return file_exists($file) ? $file : null;
        }

        // /posts -> Routes/posts/index.php
        $file = $this->routesPath . DIRECTORY_SEPARATOR . $this->uri . DIRECTORY_SEPARATOR . 'index.php';
        if (file_exists($file)) {
            return $file;
        }

        return null;
    }

    private function renderWithLayout(array $result): string
    {
        // Wyczyść zebrane style przed renderowaniem nowej strony
        clearCollectedStyles();
        
        // Renderuj główny content (zbiera style z komponentów)
        $params = $result['params'] ?? [];
        $main = render($result['template'], $params);
        
        // Renderuj layout
        $layoutParams = [
            'main' => $main,
            'title' => $result['title'] ?? 'Custom Framework',
            'bodyClass' => $result['bodyClass'] ?? '',
            'router' => $this,
        ];
        
        return render('layout', $layoutParams);
    }

    public function render404(): string
    {
        http_response_code(404);
        return $this->renderWithLayout(['template' => '404', 'title' => '404 Not Found', 'router' => $this]);
    }

    // === HTTP Helpers ===
    
    public function isGet(): bool { return $this->method === 'GET'; }
    public function isPost(): bool { return $this->method === 'POST'; }
    public function getMethod(): string { return $this->method; }
    public function getUri(): string { return $this->uri; }
    
    public function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }
    
    public function post(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }
    
    public function redirect(string $path): void
    {
        header("Location: $path");
        exit;
    }
}
