<?php
namespace User\Controller;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Email\Email;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Cake\Utility\Text;
use User\Controller\AppController;

/**
 * Users Controller
 *
 * @property \User\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    /**
     * beforeFilter method
     * @param Event $event CakePHP event
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        if (isset($this->Auth)) {
            $this->Auth->allow(['getToken', 'add', 'verify', 'resetPassword', 'linkedinHandler', 'sendVerificationEmail']);
        }
    }

    /**
     * isAuthorized method
     * Handles the user Authorization inside the controller.
     * @param array $user Authorization component's user
     * @return bool
     */
    public function isAuthorized($user)
    {
        if (isset($this->request->params['pass'][0])) {
            return ($this->request->params['pass'][0] == $user['id']);
        }
        // Default deny
        parent::isAuthorized($user);
    }

    /**
     * logout method
     */
    public function logout()
    {
        //ações para invalidar o token do usuario
        return $this->Auth->logout();
    }

    /**
     * getToken method
     * returns the user's access token
     */
    public function getToken()
    {
        $user = $this->Auth->identify([], 012);
        if (! $user) {
            throw new UnauthorizedException('Invalid username or password');
        }
        $this->set([
            'success' => true,
            'data' => [
                'id' => $user['id'],
                'token' => $this->_makeToken($user['id'])
            ],
            '_serialize' => ['success', 'data']
        ]);
    }

    /**
     * _makeToken method
     * Generates the user's token based on the id
     * @param int $userId User's id
     * @return string
     */
    protected function _makeToken($userId)
    {
        $token = \JWT::encode(
            [
                'id' => $userId,
                'exp' => time() + 604800
            ],
            Security::salt()
        );
        return $token;
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $finder = !isset($this->request->query['finder']) ? 'All': $this->request->query['finder'];

        $this->paginate = [
            'finder' => $finder,
            'contain' => ['Usertypes', 'Personalinformations'],
            'order' => ['Users.email'],
        ];
        
        $contain = isset($this->request->query['contain']) ? explode(',', $this->request->query['contain']) : [];
        foreach ($contain as $key) {
            $this->paginate['contain'][] = $key;
        }

        $users = $this->paginate($this->Users);
        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }


    /**
     * view method
     *
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($userId = null)
    {
        $this->request->allowMethod(['get']);
        
        $contain = ['Usertypes', 'Personalinformations', 'Personalinformations.Genders'];

        $associations = isset($this->request->query['contain']) ? explode(',', $this->request->query['contain']) : [];
        foreach ($associations as $key) {
            $contain[] = $key;
        }
        $contain = $this->Users->getValidAssociations($contain);

        $user = $this->Users->find()
            ->where(['Users.id' => $userId])
            ->contain($contain)
            ->first();

        if (! $user) {
            throw new NotFoundException('The user could not be found. Please, try again.');
        }

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->request->allowMethod(['post']);
        $user = $this->Users->saveUser($this->request->data);
        
        if (! empty($user->errors())) {
            throw new BadRequestException(json_encode($user->errors()));
        }

        $message = 'The user has been saved.';
        $this->set([
            'success' => true,
            'message' => $message,
            '_serialize' => ['success', 'message']
        ]);
    }

    /**
     * Edit method
     *
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($userId = null)
    {
        $this->request->allowMethod(['put', 'post']);
        
        $data = $this->Users->formatRequestData($this->request->data);
        $contain = $this->Users->getRequestAssociations($data);

        $queryAssociations = isset($this->request->query['contain']) ? explode(',', $this->request->query['contain']) : [];
        $contain = $this->Users->getValidAssociations(array_merge($contain, $queryAssociations));
        
        $user = $this->Users->get($userId, ['contain' => $contain]);

        if (isset($data['password'])) {
            unset($data['password']);
        }

        $user = $this->Users->patchEntity($user, $this->Users->setEntityUserIds($data, $user));

        if (! $this->Users->save($user, ['associated' => ['Personalinformations', 'Addresses'] ])) {
            throw new BadRequestException(json_encode($user->errors(), JSON_PRETTY_PRINT));
        }

        $message = 'The user has been updated.';
        $this->set([
            'success' => true,
            'message' => $message,
            '_serialize' => ['success', 'message']
        ]);
    }

    /**
     * Updates the user password
     * @param int $userid User's Id
     * @return null
     */
    public function changePassword($userId)
    {
        $this->request->allowMethod('post');
        if (empty($this->request->data)) {
            throw new BadRequestException("No data provided.");
        }
        $data = isset($this->request->data['user']) ? $this->request->data['user'] : $this->request->data;
        if (!isset($data['old_password']) || !isset($data['new_password'])) {
            throw new BadRequestException("No password provided.");
        }
        $user = $this->Users->get($userId);

        if (! $this->Users->checkPassword($this->request->data['old_password'], $user->password)) {
            throw new BadRequestException("Invalid user password.");
        }

        $user->password = $data['new_password'];
        if (!$this->Users->save($user)) {
            throw new BadRequestException("Password could not be altered");
        }

        $this->set([
            'message' => __("The password was successfully updated."),
            '_serialize' => ['message']
        ]);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($userId = null)
    {
        $this->request->allowMethod('delete');
        $user = $this->Users->get($userId);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * Verify method
     *
     * @param string|null $emailcheckcode User's email check code.
     * @return json with status and messager.
     */
    public function verify($emailcheckcode = null)
    {
        if (! $emailcheckcode) {
            $emailcheckcode = isset($this->request->query['code']) ? $this->request->query['code'] : null;
        }

        if (! $emailcheckcode) {
            throw new BadRequestException(__('Invalid code provided'));
        }
        
        $user = $this->Users->find('all', [
            'conditions' => [
                'emailcheckcode' => $emailcheckcode
            ]
        ])->first();

        if (! $user) {
            throw new NotFoundException(__('Code not found'));
        }
    
        $user->emailcheckcode = '';
        $user->is_active = 1;
        
        if (! $this->Users->save($user)) {
            throw new BadRequestException("Sorry. The user could not be verifyed");
        }

        $this->set([
            'success' => true,
            'message' => __('The confirmation code has been accepted. You may log in now!'),
            '_serialize' => ['success', 'message']
        ]);
    }

    /**
     * Reset Password Action
     *
     * Handles the trigger of the reset, also takes the token, validates it and let the user enter
     * a new password.
     *
     * @return void
     */
    public function resetPassword($passwordchangecode = null)
    {
        $this->request->allowMethod(['post', 'get']);

        $code = $passwordchangecode ? $passwordchangecode :
            (isset($this->request->data['passwordchangecode']) ? $this->request->data['passwordchangecode'] : null);
        
        if ($code) {
            return $this->_resetPassword($code);
        }

        if (!isset($this->request->data['email'])) {
            throw new BadRequestExceptionException(__('No e-mail provided.'));
        }

        return $this->_sendPasswordResetCode($this->request->data);
    }

    /**
     * This method allows the user to change his password if the reset token is correct
     *
     * @param string $passwordchangecode Token
     * @return void
     */
    protected function _resetPassword($passwordChangeCode)
    {
        $user = $this->Users->checkPasswordToken($passwordChangeCode);
        if (! $user) {
            throw new NotFoundException(__('Invalid password Code'));
        }

        if (! $this->Users->resetPassword($user, $this->request->data)) {
            throw new NotFoundException(__('Invalid password'));
        }

        $message = __('Password changed');
        $this->set([
            'message' => $message,
            '_serialize' => ['message']
        ]);
    }

    /**
     * Checks if the email is in the system and authenticated, if yes create the token
     * save it and send the user an email
     *
     * @param array $options Options
     * @param bool $admin Admin boolean
     * @return void
     */
    protected function _sendPasswordResetCode($options = [], $admin = null)
    {
        // options
        // options of email configuration
        if (empty($options)) {
            throw new BadRequestException(__("Invalid data provided."));
        }

        $user = $this->Users->passwordResetCode($options);//password reset code
        if (empty($user)) {
            throw new BadRequestException(__('No user was found with that email.'));
        }
        
        $email = new Email(Configure::read('auth_plugin.email_settings.transport')); // read Config file
        $code = $user->passwordchangecode;

        $resetUrl = Configure::read('debug') ? Configure::read('auth_plugin.reset_pass_url.dev') : Configure::read('auth_plugin.reset_pass_url.production');
        $email->from(Configure::read('auth_plugin.email_settings.from'))
            ->emailFormat('html')
            ->template('lost_password', 'default')
            ->viewVars(['serviceName' => Configure::read('auth_plugin.service_name'), 'code' => $code, 'url' => $resetUrl])
            ->to($user->email)
            ->subject(Configure::read('auth_plugin.email_settings.reset_pass_subject'))
            ->send();

        $message = ($admin) ? __('has been sent an email with instruction to reset their password.') : __('You should receive an email with further instructions shortly');
        
        $this->set([
            'message' => $message,
            '_serialize' => ['message']
        ]);
    }

    /**
     * Checks if an email is already verified and if not renews the expiration time
     *
     * @return void
     */
    public function sendVerificationEmail()
    {
        $this->request->allowMethod(['post']);
        if (! isset($this->request->data['email'])) {
            throw new BadRequestException('Invalid data provided.');
        }

        $user = $this->Users->findByEmail($this->request->data['email'])->first();
        if (empty($user->emailcheckcode)) {
            throw new UnauthorizedException('Email already confirmed');
        }
        
        $user->emailcheckcode = md5(time() * rand());
        if (! $this->Users->sendVerificationEmail($user)) {//mudar nome
            throw new BadRequestException('Sorry. The email could not be sent.');
        }
        
        $this->Users->save($user);
        $message = __('The email was resent. Please check your inbox.');
        
        $this->set([
            'message' => $message,
            '_serialize' => ['message']
        ]);
    }

    /**
     * linkedinHandler
     * LinkedIn oauth2 login callback
     */
    public function linkedinHandler()
    {
        $this->autoRender = false;
        $this->request->allowMethod(['post']);
        if ($this->request->data) {
            $token = $this->request->data['usersocialdata']['linkedin_token'];
            $linkedinData = $this->Linkedin->linkedinget('/v1/people/~:(id)', $token);
            $usersocialdata = $this->Users->Usersocialdata->find()->where(['linkedin_id' => $linkedinData['id']])->contain('Users')->first();
            if ($usersocialdata) {//login action
                $token = $this->_makeToken($usersocialdata['user']['id']);
                $success = true;
            } else {
                $user = $this->Users->newEntity($this->request->data);
                if ($this->Users->save($user)) {
                    $token = $this->_makeToken($user->id);
                    $success = true;
                } else {
                    throw new NotFoundException('The user could not be saved. Please, try again.');
                }
            }
            $this->set([
            'success' => $success,
                'data' => [
                    'token' => $token
                ],
                '_serialize' => ['success', 'data']
            ]);
        } else {
            throw new NotFoundException('The user could not be saved. Please, try again.');
        }
    }
}
