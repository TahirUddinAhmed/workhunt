<?php
require '../helpers.php';
require basePath('Router.php');
require basePath('Database.php');

// Instantiate the Router 
$router = new Router();

require basePath('routes.php');

// Get the current URI and HTTP method
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Route the request 
$router->route($uri, $method);