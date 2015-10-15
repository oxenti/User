<?php
use Cake\Routing\Router;

// Router::plugin('User', function ($routes) {
//     $routes->fallbacks('InflectedRoute');
Router::plugin('User', function ($routes) {

    $routes->extensions(['json']);
    
    $routes->connect('/users/get_token', ['plugin' => 'User', 'controller' => 'users', 'action' => 'getToken']);
    $routes->connect('/users/linkedin_handler', ['plugin' => 'User', 'controller' => 'users', 'action' => 'linkedinHandler']);
    $routes->connect('/users/register', ['plugin' => 'User', 'controller' => 'users', 'action' => 'add']);
    $routes->connect('/users/reset_password', ['plugin' => 'User', 'controller' => 'users', 'action' => 'resetPassword']);
    $routes->connect('/users/send_verification_email', ['plugin' => 'User', 'controller' => 'users', 'action' => 'sendVerificationEmail']);
   
    $routes->resources('Genders');
    $routes->resources('Users', function ($routes) {
        $routes->resources('Personalinformations');
    });

    $routes->fallbacks('DashedRoute');
    // debug($routes);die();
});
