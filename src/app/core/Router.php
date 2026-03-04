<?php

class Router {
    private array $routes = [];
    private PDO $db;
    private string $basePath;

    public function __construct(PDO $db, string $basePath = '') {
        $this->db = $db;
        $this->basePath = rtrim($basePath, '/');
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
        // Parse the URL to get the path without query strings
        $parsedUrl = parse_url($path);
        // Decode the path component to handle spaces/diacritics
        $path = urldecode($parsedUrl['path'] ?? '');

        // Remove base path to allow subdirectory installations or flexible virtualhosts
        // Make sure we are matching exactly at the start
        if ($this->basePath !== '' && str_starts_with($path, $this->basePath)) {
            $path = substr($path, strlen($this->basePath));
        }

        if ($path === '' || $path === false) {
            $path = '/';
        }

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