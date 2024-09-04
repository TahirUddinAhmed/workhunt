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
 * @return void
 * 
 */
function loadView($name) {
    $path = basePath('views/' . $name);
    $ext = '.view.php';
    $fullPath = $path . $ext;

    if(!file_exists($fullPath)) {
        die('View File not found ' . $fullPath);
    } else {
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