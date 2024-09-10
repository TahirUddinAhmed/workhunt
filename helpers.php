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
    $path = basePath('views/' . $name);
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
function loadPartial($name) {
    $path = basePath("views/partials/{$name}.php");

    if(!file_exists($path)) {
        die('View File not found ' . $path);
    } else {
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