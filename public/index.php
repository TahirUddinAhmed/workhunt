<?php
require __DIR__ . '/../vendor/autoload.php';

use Framework\Router;
use Framework\Session;

Session::start();

require '../helpers.php';

// Instantiate the Router 
$router = new Router();

require basePath('routes.php');

// Get the current URI 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 


// Route the request 
$router->route($uri);  