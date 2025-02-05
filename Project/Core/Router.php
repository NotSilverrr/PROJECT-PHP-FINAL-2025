<?php

namespace Core;

class Router
{
    private array $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    public function get(string $path, string $controllerName, string $methodName): void
    {
        $this->addRoute('GET', $path, $controllerName, $methodName);
    }

    public function post(string $path, string $controllerName, string $methodName): void
    {
        $this->addRoute('POST', $path, $controllerName, $methodName);
    }

    private function addRoute(string $method, string $path, string $controllerName, string $methodName): void
    {
        // Convertit le chemin en pattern regex
        $pattern = $this->pathToRegex($path);
        
        $this->routes[] = [
            "method" => $method,
            "path" => $path,
            "pattern" => $pattern,
            "controllerName" => $controllerName,
            "methodName" => $methodName
        ];
    }

    private function pathToRegex(string $path): string
    {
        // Échappe les caractères spéciaux, sauf les accolades
        $path = preg_quote($path, '/');
        
        // Remplace les paramètres {param} par un groupe de capture
        return '/^' . preg_replace('/\\\{([^}]+)\\\}/', '(?P<$1>[^\/]+)', $path) . '$/';
    }

    private function extractParameters(string $pattern, string $path): array
    {
        $params = [];
        if (preg_match($pattern, $path, $matches)) {
            // Ne garde que les paramètres nommés
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }
        }
        return $params;
    }

    public function start(): void
    {
        $method = $_SERVER["REQUEST_METHOD"];
        $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($method === $route["method"] && preg_match($route["pattern"], $path, $matches)) {
                $params = $this->extractParameters($route["pattern"], $path);
                $methodName = $route["methodName"];
                $controllerName = $route["controllerName"];

                // Instancie le contrôleur
                $controller = new $controllerName();
                
                // Appelle la méthode avec les paramètres
                $response = $controller->$methodName(...$params);

                if ($response instanceof View) {
                    echo $response;
                } elseif (is_string($response)) {
                    echo $response;
                } elseif (is_array($response)) {
                    header('Content-Type: application/json');
                    echo json_encode($response);
                }
                exit;
            }
        }
        
        http_response_code(404);
        echo "Page non trouvée";
    }
}