<?php

namespace User\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController
{
    /**
     * Initialize function
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('User.Linkedin');
    }
}
