<?php
require '../helpers.php';

require basePath('Router.php');

$router = new Router();

require basePath('routes.php');

// Get the current URI
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);