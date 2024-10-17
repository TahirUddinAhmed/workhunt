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

$router->get('/auth/register', 'UserController@create');
$router->get('/auth/login', 'UserController@login');

$router->post('/auth/register', 'UserController@store');
$router->post('/auth/logout', 'UserController@logout');
$router->post('/auth/login', 'UserController@authenticate');