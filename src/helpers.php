<?php
/**
 * Funkcje pomocnicze do renderowania templatek
 */

// Globalna tablica na zebrane style CSS
global $__collected_styles;
$__collected_styles = [];

/**
 * Renderuje template i zwraca HTML
 * 
 * @param string $template Ścieżka od folderu templates (np. 'post/index.html.php')
 * @param array $params Dane dostępne w renderze jako $params
 * @return string Wyrenderowany HTML
 */
function render(string $template, array $params = []): string
{
    ob_start();
    $templatePath = dirname(__DIR__) 
        . DIRECTORY_SEPARATOR . 'templates' 
        . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $template)
        . DIRECTORY_SEPARATOR . 'index.php';
    
    if (!file_exists($templatePath)) {
        throw new \App\Exception\NotFoundException("Template '$template' nie istnieje");
    }
    
    // Check for CSS file in same directory
    $cssPath = dirname($templatePath) . DIRECTORY_SEPARATOR . 'style.css';
    if (file_exists($cssPath)) {
        collectStyle($cssPath);
    }
    
    require $templatePath;
    return ob_get_clean();
}

/**
 * Renderuje komponent i wypisuje HTML (do użycia wewnątrz templatek)
 * 
 * @param string $name Nazwa komponentu (folder w components/)
 * @param array $params Dane dostępne w komponencie jako $params
 */
function component(string $name, array $params = []): void
{
    ob_start();
    $componentPath = dirname(__DIR__) 
        . DIRECTORY_SEPARATOR . 'components' 
        . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $name)
        . DIRECTORY_SEPARATOR . 'index.php';
    
    if (!file_exists($componentPath)) {
        throw new \App\Exception\NotFoundException("Component '$name' nie istnieje");
    }
    
    // Check for CSS file in same directory
    $cssPath = dirname($componentPath) . DIRECTORY_SEPARATOR . 'style.css';
    if (file_exists($cssPath)) {
        collectStyle($cssPath);
    }
    
    require $componentPath;
    echo ob_get_clean();
}

/**
 * Zbiera ścieżkę do pliku CSS
 */
function collectStyle(string $cssPath): void
{
    global $__collected_styles;
    if (!in_array($cssPath, $__collected_styles)) {
        $__collected_styles[] = $cssPath;
    }
}

/**
 * Zwraca zebrane style jako tagi <style>
 */
function getCollectedStyles(): string
{
    global $__collected_styles;
    $output = '';
    foreach ($__collected_styles as $cssPath) {
        $output .= '<style>' . file_get_contents($cssPath) . '</style>' . "\n";
    }
    return $output;
}

/**
 * Czyści zebrane style (wywołaj przed renderowaniem strony)
 */
function clearCollectedStyles(): void
{
    global $__collected_styles;
    $__collected_styles = [];
}

/**
 * Escape HTML - zabezpieczenie przed XSS
 * 
 * @param string|null $value Wartość do escape'owania
 * @return string
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
