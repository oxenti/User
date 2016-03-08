<?php
namespace User\Event;

use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

class UserListener implements EventListenerInterface
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
