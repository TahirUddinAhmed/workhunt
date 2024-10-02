<?php 

/**
 * Get the base Path
 * 
 * @param string $path
 * @return string
 */
function basePath($path = '') {
    return __DIR__ . '/' . $path;
}

/**
 * Load a View 
 * 
 * @param string $name
 * @param array $data
 * @return void
 * 
 */
function loadView($name, $data = []) {
    $path = basePath('App/views/' . $name);
    $ext = '.view.php';
    $fullPath = $path . $ext;

    if(!file_exists($fullPath)) {
        die('View File not found ' . $fullPath);
    } else {
        extract($data);
        // $data;
        require $fullPath;
    }
    
}

/**
 * load Partials
 * @param string $name
 * @return void
 */
function loadPartial($name, $data = []) {
    $path = basePath("App/views/partials/{$name}.php");

    if(!file_exists($path)) {
        die('View File not found ' . $path);
    } else {
        extract($data);
        require $path;
    }
}

/**
 * Inspect a Value(s)
 * 
 * @param mixed $value
 * @return void 
 */

 function inspect($value) {
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
 }

 /**
  * Inspect a value(s) and die
   *
   * @param mixed $value
   * @return void 
  */
  function inspectAndDie($value) {
    echo '<pre>';
    die(var_dump($value));
    echo '</pre>';
  }

/**
 * Formate Salary 
 * 
 * @param string $salary
 * @return string $formatted salary 
 */
function formateSalary($salary) {
    return '₹' . number_format(floatval($salary));
}

/**
 * Sanitize Data 
 * 
 * @param string $dirty
 * @return string
 */
function sanitize($dirty) {
    return filter_var(trim($dirty), FILTER_SANITIZE_SPECIAL_CHARS);
}

/**
 * Redirect to a given URL
 * 
 * @param string $url
 * @return void
 */
function redirect($url) {
    header("Location: {$url}");
    exit;
}