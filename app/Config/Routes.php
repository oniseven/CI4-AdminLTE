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

$routes->group('setting', static function ($routes) {
  $routes->group('privileges', static function ($routes) {
    $routes->get('', 'Setting\Privileges::index');
    $routes->get('list', 'Setting\Privileges::list');
    $routes->get('menus', 'Setting\Privileges::menutree');
    
    $routes->post('datatable', 'Setting\Privileges::datatable');
    $routes->post('save', 'Setting\Privileges::save');
    $routes->post('menus', 'Setting\Privileges::menus');

    $routes->patch('status', 'Setting\Privileges::status');
    $routes->delete('(:num)', 'Setting\Privileges::delete/$1');
  });

  $routes->group('user', static function ($routes) {
    $routes->post('save', 'Setting\User::save');
    $routes->post('datatable', 'Setting\User::datatable');
    $routes->delete('(:num)', 'Setting\User::delete/$1');
    $routes->patch('status', 'Setting\User::status');
  });
});