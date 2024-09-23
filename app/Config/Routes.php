<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/dashboard', 'DashboardController::index', ['filter' => 'auth']);
$routes->get('/', 'Home::documentation');
$routes->post('apikey/store', 'ApiController::store', ['filter' => 'auth']);
$routes->post('dashboard/submit_reservation', 'DashboardController::submitReservation');
$routes->get('populate-database', 'DashboardController::populateDatabase');



$routes->group('api', ['filter' => ['cors', 'apikey']], function ($routes) {
    $routes->post('hello', 'ApiController::index');
    $routes->post('insert', 'ApiController::insert');
    $routes->post('get_reservations_between_dates', 'ApiController::get_reservations_between_dates');
    $routes->post('get_all', 'ApiController::get_all');
    $routes->post('populate-database', 'ApiController::populateDatabase');
    $routes->post('get-available-dates', 'ApiController::getAvailableDates');
    $routes->post('get-campsite-spots', 'ApiController::getCampsiteSpots');
    $routes->post('register', 'ApiController::register');
});

service('auth')->routes($routes);
