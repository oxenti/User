<?php
namespace User\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use User\Controller\Component\Vendor\LinkedinLib;

class LinkedinComponent extends Component
{

    /**
     *
     */
    public function linkedinget($resource, $token)
    {
        Configure::load('User.linkedin');
        $credentials = Configure::read('credentials');
        $linkedIn = new LinkedInLib($credentials['api_key'], $credentials['api_secret']);

        $result = $linkedIn->linkedinget($resource, $token);
        return $result;
    }
}
