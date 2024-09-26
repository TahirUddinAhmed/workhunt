<?php

namespace Framework;

use App\Controllers\ErrorController;

class Router {
    protected $routes = [];

    /**
     * Add a new Route
     *
     * @param string $method
     * @param string $uri
     * @param string $action
     * @return void
     */
    protected function registerRoute($method, $uri, $action) {
        list($controller, $controllerMethod) = explode('@', $action);
        

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod
        ];
    }

    /**
     * Add a GET route
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function get($uri, $controller) {
        $this->registerRoute('GET', $uri, $controller);
    }

    /**
     * Add a POST route
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function post($uri, $controller) {
        $this->registerRoute('POST', $uri, $controller);
    }

    /**
     * Add a PUT route
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function put($uri, $controller) {
        $this->registerRoute('PUT', $uri, $controller);
    }

    /**
     * Add a DELETE route
     * 
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function delete($uri, $controller) {
        $this->registerRoute('DELETE', $uri, $controller);
    }

    /**
     * Load Error Page
     * 
     * @param int $httpCode
     * @return void
     */
    protected function error($httpCode = 404) {
        http_response_code($httpCode);
        loadView("error/{$httpCode}");
        exit;
    }

    /**
     * Route the request (logic)
     * 
     * @param string $uri
     * 
     * @return void
     */
    public function route($uri) {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Check of _method input 
        if($requestMethod == 'POST' && isset($_POST['_method'])) {
            //Override the request method with the value of _method
            $requestMethod = strtoupper($_POST['_method']);

            inspectAndDie($requestMethod);
        }

        foreach($this->routes as $route) {

            // split the current uri into segments 
            $uriSegments = explode('/', trim($uri, '/'));

            
            // split the route URI into segments 
            $routeSegments = explode('/', trim($route['uri'], '/'));

            $match = true;

            // Check if the number of segments matches 
            if(count($uriSegments) === count($routeSegments) && strtoupper($route['method'] === $requestMethod)) {
                $params = [];

                $match = true;

                for($i = 0; $i < count($uriSegments); $i++) {
                    // If the uri's do not match and there is no params
                    if($routeSegments[$i] !== $uriSegments[$i] && !preg_match('/\{(.+?)\}/', $routeSegments[$i])) {
                        $match = false;
                        break;
                    }

                    // Check for the param and add to $params array
                    if(preg_match('/\{(.+?)\}/', $routeSegments[$i], $matches)) {
                        $params[$matches[1]] = $uriSegments[$i];
                    }
                }

                if($match) {
                    // extract controller and controller method
                    $controller = 'App\\Controllers\\' . $route['controller'];
                    $controllerMethod = $route['controllerMethod'];

                    // Instantiate the controller and call the method 
                    $controllerInstance = new $controller();
                    // call method
                    $controllerInstance->$controllerMethod($params);
                    return; // if found return the function
                }
            }
        }

        ErrorController::notFound();
    }

    

}