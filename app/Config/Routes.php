<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index', ['filter' => 'auth']);
$routes->get('/documentation', 'Home::documentation', ['filter' => 'auth']);
$routes->post('apikey/store', 'ApiController::store', ['filter' => 'auth']);


$routes->group('api', ['filter' => 'apikey'], function ($routes) {
    $routes->post('hello', 'ApiController::index');
    $routes->post('insert', 'ApiController::insert');
    $routes->post('get_reservations_between_dates', 'ApiController::get_reservations_between_dates');
    $routes->post('get_all', 'ApiController::get_all');
});

service('auth')->routes($routes);