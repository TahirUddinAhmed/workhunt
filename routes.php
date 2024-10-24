<?php

$router->get('/', 'HomeController@index');
$router->get('/listings', 'ListingController@index');
$router->get('/listings/create', 'ListingController@create', ['auth']);
$router->get('listings/edit/{id}', 'ListingController@edit', ['auth']);
$router->get('/listings/{id}', 'ListingController@show');
$router->get('listing/search', 'ListingController@search');

// Form Submission
$router->post('/listings', 'ListingController@store');
$router->put('/listings/{id}', 'ListingController@update', ['auth']);
// Delete Request 
$router->delete('/listings/{id}', 'ListingController@destroy', ['auth']);

$router->get('/auth/register', 'UserController@create', ['guest']);
$router->get('/auth/login', 'UserController@login', ['guest']);

$router->post('/auth/register', 'UserController@store', ['guest']);
$router->post('/auth/logout', 'UserController@logout', ['auth']);
$router->post('/auth/login', 'UserController@authenticate', ['guest']);