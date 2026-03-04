<?php

class Router {
    private array $routes = [];
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function addRoute(string $method, string $path, string $controller, string $action): void {
        // Transform {slugs} into regex named capturing groups for dynamic routing
        // Match any character except slashes for the parameter to allow diacritics and spaces
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $path);
        
        // Wrap with delimiters; make trailing slashes optional
        $pattern = '#^' . rtrim($pattern, '/') . '/?$#';
        
        $this->routes[$method][$pattern] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch(string $method, string $path): void {
        // Strip query string and decode URL to handle spaces, +, and diacritics correctly
        $path = urldecode(parse_url($path, PHP_URL_PATH) ?? '');

        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        if (!isset($this->routes[$method])) {
            echo "404 Not Found";
            return;
        }

        foreach ($this->routes[$method] as $pattern => $route) {
            if (preg_match($pattern, $path, $matches)) {
                $controller = new $route['controller']($this->db);
                
                // Extract named groups to pass as ordered arguments to the controller method
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                $controller->{$route['action']}(...array_values($params));
                return;
            }
        }

        echo "404 Not Found";
    }

}

?>