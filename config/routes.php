<?php
use Cake\Routing\Router;

Router::plugin('User', function ($routes) {
    $routes->fallbacks('InflectedRoute');
    
    $routes->resources('Users');
    $routes->resources('Usermessages');
    $routes->resources('Users', function ($routes) {
        $routes->resources('Usermessages');
    });
});
