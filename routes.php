<?php

$router->get('/', 'HomeController@index');
$router->get('/listings', 'ListingController@index');
$router->get('/listings/create', 'ListingController@create');
$router->get('/listings/{id}', 'ListingController@show');
// Form Submission
$router->post('/listings', 'ListingController@store');
// Delete Request 
$router->delete('/listings/{id}', 'ListingController@destroy');