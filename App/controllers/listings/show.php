<?php

use Framework\Database;

$config = require basePath('config/db.php');
$db = new Database($config);

$id = $_GET['id'] ?? '';

$query = 'SELECT * FROM listings WHERE id = :id';

$params = [
    'id'=> $id
];

$listing = $db->query($query, $params)->fetch();


loadView('listings/show', [
    'listing' => $listing
]);