<?php
require '../helpers.php';
require basePath('vendor/autoload.php');

use Framework\Router;

// Instantiate the Router 
$router = new Framework\Router();

require basePath('routes.php');

// Get the current URI and HTTP method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 

$method = $_SERVER['REQUEST_METHOD'];

// Route the request 
$router->route($uri, $method);