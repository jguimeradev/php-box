<?php

namespace Debian\Php\auth\src\http;

use RuntimeException;


class Router
{

    private array $routes = [];

    public function get(string $path, callable $handle): void
    {
        $this->setRoutes('GET', $path, $handle);
    }

    public function post(string $path, callable $handle): void
    {
        $this->setRoutes('POST', $path, $handle);
    }

    public function setRoutes(string $method, string $path, callable $handle): void
    {
        $this->routes[$method][$path] = $handle;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            call_user_func($handler, $this);
        } else {
            http_response_code(404);
            echo "404 not found";
        }
    }

    public function render(string $template, array|null $params = []): void
    {
        $path = __DIR__ . '/../views/' . $template . '.php';

        if (!file_exists($path)) {
            throw new RuntimeException("View not found: $path");
        }

        if ($params) extract($params);
        ob_start();
        require $path;
        $content = ob_get_clean();
        echo $content;
    }
}
