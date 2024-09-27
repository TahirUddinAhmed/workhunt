<?php

$router->get('/', 'HomeController@index');
$router->get('/listings', 'ListingController@index');
$router->get('/listings/create', 'ListingController@create');
$router->get('listings/edit/{id}', 'ListingController@edit');
$router->get('/listings/{id}', 'ListingController@show');

// Form Submission
$router->post('/listings', 'ListingController@store');
$router->put('/listings/{id}', 'ListingController@update');
// Delete Request 
$router->delete('/listings/{id}', 'ListingController@destroy');