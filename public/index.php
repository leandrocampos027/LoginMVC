<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carregar configs
require_once '../config/config.php';
require_once '../config/mailconfig.php';

// Autoload com suporte a namespaces
spl_autoload_register(function($class) {
    $baseDir = realpath(__DIR__ . '/../'); // raiz do projeto

    // Converter namespace para caminho
    $file = $baseDir . '/' . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Iniciar roteamento
\App\Core\Router::dispatch();
