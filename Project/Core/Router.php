<?php
namespace Core;

class Router {
    private array $routes;
    private array $redirects = [];
    
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

    public function redirect(string $from, string $to, int $statusCode = 302): void
    {
        $this->redirects[$from] = ["destination" => $to, "statusCode" => $statusCode];
    }
    
    private function addRoute(string $method, string $path, string $controllerName, string $methodName): void
    {
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
        $path = preg_quote($path, '/');
        return '/^' . preg_replace('/\\\{([^}]+)\\\}/', '(?P<$1>[^\/]+)', $path) . '$/';
    }
    
    private function extractParameters(string $pattern, string $path): array
    {
        $params = [];
        if (preg_match($pattern, $path, $matches)) {
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

        if (array_key_exists($path, $this->redirects)) {
            $redirect = $this->redirects[$path];
            http_response_code($redirect["statusCode"]);
            header("Location: " . $redirect["destination"]);
            exit;
        }

        if (preg_match('/\.(?:js|css|png|jpg|jpeg|gif|ico|svg|woff2?|ttf|eot)$/', $path)) {
            return;
        }
        
        foreach ($this->routes as $route) {
            if ($method === $route["method"] && preg_match($route["pattern"], $path, $matches)) {
                $urlParams = $this->extractParameters($route["pattern"], $path);
                $methodName = $route["methodName"];
                $controllerName = $route["controllerName"];
                
                $controller = new $controllerName();
                
                // Convertir les types si nécessaire
                foreach ($urlParams as $key => $value) {
                    if ($key === 'id') {
                        $urlParams[$key] = (int)$value;
                    }
                }
                
                // Ne passer que les paramètres d'URL à la méthode
                $response = $controller->$methodName(...array_values($urlParams));
                
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
        echo view('errors.404');
    }
}