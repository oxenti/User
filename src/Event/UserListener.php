<?php
namespace User\Event;

use Cake\Event\EventListenerInterface;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\TableRegistry;

class UserListener implements EventListenerInterface
{
    use MailerAwareTrait;
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
        // $Users = TableRegistry::get('User.Users');
        // // $Users->sendVerificationEmail($event->data['entity']);
        // $this->getMailer('User')->send('verification', [$agenda]);
    }
}
