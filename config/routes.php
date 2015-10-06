<?php
use Cake\Routing\Router;

// Router::plugin('User', function ($routes) {
//     $routes->fallbacks('InflectedRoute');
Router::plugin('User', function ($routes) {
    $routes->fallbacks('DashedRoute');
    
    // debug($routes);die();
    $routes->resources('Usermessages');
    $routes->resources('Users', function ($routes) {
        $routes->resources('Usermessages');
    });
});
