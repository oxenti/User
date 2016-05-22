<?php

namespace User\Controller;

use App\Controller\AppController as BaseController;

if (!function_exists('getallheaders'))
{
   function getallheaders()
   {
          $headers = '';
      foreach ($_SERVER as $name => $value)
      {
          if (substr($name, 0, 5) == 'HTTP_')
          {
              $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
          }
      }
      return $headers;
   }
}

class AppController extends BaseController
{
    /**
     * Initialize function
     */
    public function initialize()
    {
        parent::initialize();
        // $this->loadComponent('User.Linkedin');
    }
}
