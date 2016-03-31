<?php
namespace User\Mailer;

use Cake\Core\Configure;
use Cake\I18n\Number;
use Cake\I18n\Time;
use Cake\Mailer\Mailer;
use Cake\ORM\TableRegistry;

class UserMailer extends Mailer
{
    
    /**
     * List of events to listen to
     */
    public function implementedEvents()
    {
        return [
            'Model.User.requireVerification' => 'requestVerification',
        ];
    }


    /**
     * Request Verification Event listner
     */
    public function requestVerification($event, $entity, $options)
    {
        $this->send('verification', [$event->data['entity'], $event->data['action']]);
    }

    /**
     * Send emails after agendas creation
     */
    public function verification($user, $template = 'register')
    {
        $code = $user->emailcheckcode;
        $verificationUrl = Configure::read('debug') ? Configure::read('auth_plugin.verify_url.dev') : Configure::read('auth_plugin.verify_url.production');
        $this
            ->from(Configure::read('auth_plugin.email_settings.from'))
            ->transport(Configure::read('auth_plugin.email_settings.transport'))
            ->to($user->email)
            ->subject(Configure::read('auth_plugin.email_settings.register_subject'))
            ->emailFormat('html')
            ->template($template) // By default template with same name as method name is used.
            ->layout('default')
            ->viewVars([
                'name' => $user->personalinformation->first_name,
                'serviceName' => Configure::read('auth_plugin.service_name'),
                'code' => $code,
                'url' => $verificationUrl
            ]);
    }
}
