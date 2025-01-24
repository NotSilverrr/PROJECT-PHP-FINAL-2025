<?php

namespace App\Core;

class Router
{
  private array $routes;

  public function __construct()
  {
    $this->routes = [];
  }

  public function get(string $path, string $controllerName, string $methodName): void
  {
    $this->routes[] = [
      "method" => "GET",
      "path" => $path,
      "controllerName" => $controllerName,
      "methodName" => $methodName
    ];
  }

  public function post(string $path, string $controllerName, string $methodName): void
  {
    $this->routes[] = [
      "method" => "POST",
      "path" => $path,
      "controllerName" => $controllerName,
      "methodName" => $methodName
    ];
  }

  public function start(): void
  {
    $method = $_SERVER["REQUEST_METHOD"];
    $path = $_SERVER["REQUEST_URI"];

    foreach ($this->routes as $route) {
      if ($method === $route["method"] && $path === $route["path"]) {
          $methodName = $route["methodName"];
          $controllerName = $route["controllerName"];

          $response = $controllerName::$methodName();

          // Gestion flexible des réponses
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
