<?php
require '../helpers.php';

// Custom Autoloader
spl_autoload_register(function($class) {
    $path = basePath('Framework/' . $class . '.php');

    if(file_exists($path)) {
        require $path;
    } else {
        die('Invalid Class File');
    }
});

// Instantiate the Router 
$router = new Router();

require basePath('routes.php');

// Get the current URI and HTTP method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 

$method = $_SERVER['REQUEST_METHOD'];

// Route the request 
$router->route($uri, $method);