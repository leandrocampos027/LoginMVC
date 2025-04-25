<?php
namespace App\Core;

class Router {
    public static function dispatch() {
        $url = $_GET['url'] ?? 'auth/login'; // rota padrão

        $url = rtrim($url, '/');
        $segments = explode('/', $url);

        $folder = ucfirst($segments[0] ?? 'Auth');
        $controller = ucfirst($segments[1] ?? 'Login') . 'Controller';
        $method = $segments[2] ?? 'index';
        $params = array_slice($segments, 3);

        $controllerClass = "App\\Controllers\\$folder\\$controller";

        if (class_exists($controllerClass)) {
            $controllerInstance = new $controllerClass();
            if (method_exists($controllerInstance, $method)) {
                call_user_func_array([$controllerInstance, $method], $params);
                return;
            }
        }

        require_once '../app/views/404.php';
    }
}
