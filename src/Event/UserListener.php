<?php
namespace User\Event;

use Cake\Event\EventListener;
use Cake\ORM\TableRegistry;

class UserListener implements EventListener
{
    /**
     * List of implemented events
     */
    public function implementedEvents()
    {
        return [
            'Model.User.requireVerification' => 'requestVerification',
        ];
    }

    /**
     * requireVerification Event handler
     */
    public function requestVerification($event, $entity, $options)
    {
        debug($entity);
        die();
        $Users = TableRegistry::get('User.Users');
        $Users->sendVerificationEmail($entity);
    }
}
