<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'DashboardController::index', ['filter' => 'auth']);
$routes->get('/documentation', 'Home::documentation', ['filter' => 'auth']);
$routes->post('apikey/store', 'ApiController::store', ['filter' => 'auth']);
$routes->post('dashboard/submit_reservation', 'DashboardController::submitReservation');
$routes->get('populate-database', 'DashboardController::populateDatabase');



$routes->group('api', ['filter' => 'apikey'], function ($routes) {
    $routes->post('hello', 'ApiController::index');
    $routes->post('insert', 'ApiController::insert');
    $routes->post('get_reservations_between_dates', 'ApiController::get_reservations_between_dates');
    $routes->post('get_all', 'ApiController::get_all');
    $routes->post('populate-database', 'ApiController::populateDatabase');
    $routes->post('get-available-dates', 'ApiController::getAvailableDates');

});

service('auth')->routes($routes);
