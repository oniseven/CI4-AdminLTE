<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('login', 'Login::index');
$routes->get('register', 'Register::index');
$routes->get('/tables/dtable', 'Home::index');
$routes->group('tables', static function ($routes) {
  $routes->get('simple', 'Tables\Simple::index');
  $routes->group('dtables', static function ($routes) {
    $routes->get('/', 'Tables\Dtables::index');
    $routes->post('datatable', 'Tables\Dtables::datatable');
  });
});
