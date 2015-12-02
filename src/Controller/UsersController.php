<?php
namespace User\Controller;

use Cake\Event\Event;
use Cake\Network\Email\Email;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\UnauthorizedException;
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
        if (isset($this->Auth) && !isset(getallheaders()['Authorization'])) {
            $this->Auth->allow(['getToken', 'add', 'verify', 'resetPassword', 'linkedinHandler']);
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
        // Admin can access every action
        if (isset($user['usertype_id'])) {
            if ($user['usertype_id'] == 100) {
                return true;
            } elseif (isset($this->request->params['pass'][0])) {
                if ($this->request->params['pass'][0] == $user['id']) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($this->request->action === 'info') {
                return true;
            } else {
                return false;
            }
        }
        parent::isAuthorized($user);
        // Default deny
        throw new UnauthorizedException('UnauthorizedException ');
        return false;
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
        $user = $this->Auth->identify();
        if (! $user) {
            throw new UnauthorizedException('Invalid username or password');
        } elseif (!empty($user['emailcheckcode'])) {
            throw new UnauthorizedException('Before login, please confirm email');
        }
        $this->set([
            'success' => true,
            'data' => [
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
        $finder = !isset($this->request->query['finder'])?'All': $this->request->query['finder'];
        $this->paginate = [
            'finder' => $finder,
            'contain' => ['Usertypes', 'Personalinformations'],
            'order' => ['Users.email'],
        ];
        
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
        $user = $this->Users->find()
            ->where(['Users.id' => $userId])
            ->contain(['Usertypes', 'Personalinformations', 'Personalinformations.Genders', 'Addresses'])
            ->first();
        if (!$user) {
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
        if ($user) {
            $message = 'The user has been saved.';
            $this->set([
                'success' => true,
                'message' => $message,
                '_serialize' => ['success', 'message']
            ]);
        } else {
            throw new BadRequestException('The user could not be saved.');
        }
    }

    /**
     * Edit method
     *
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($userId = null)
    {
        $this->request->allowMethod(['put']);
        $user = $this->Users->get($userId, ['contain' => ['Addresses', 'Personalinformations']]);
        if ($this->request->is('put')) {
            $user = $this->Users->patchEntity($user, $this->Users->formatRequestData($this->request->data));
            if ($this->Users->save($user)) {
                $message = 'The user has been saved.';
                $this->set([
                    'success' => true,
                    'message' => $message,
                    '_serialize' => ['success', 'message']
                ]);
            } else {
                throw new NotFoundException('Could not edit that user');
            }
        }
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
            $message = 'The user has been disable.';
            $this->set([
                'success' => true,
                'message' => $message,
                '_serialize' => ['success', 'message']
            ]);
        } else {
            throw new BadRequestException('The user could not be disable.');
        }
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

        if (empty($user)) {
            throw new NotFoundException(__('Code not found'));
        } else {
            $user->emailcheckcode = '';
            if ($this->Users->save($user)) {
                $message = __('The confirmation code has been accepted. You may log in now!');
                $success = true;
            } else {
                $message = __('Try again');
                $success = 'error';
            }
            $this->set([
                'success' => $success,
                'message' => $message,
                '_serialize' => ['success', 'message']
            ]);
        }
    }

    /**
     * Reset Password Action
     *
     * Handles the trigger of the reset, also takes the token, validates it and let the user enter
     * a new password.
     *
     * @return void
     */
    public function resetPassword()
    {
        $this->request->allowMethod('post');

        $code = isset($this->request->data['code']) ? $this->request->data['code'] : null;
        $email = isset($this->request->data['email']) ? $this->request->data['email'] : null;
        $newPass = isset($this->request->data['password']) ? $this->request->data['password'] : null;

        if ((!$code) || (!$email) || (!$newPass)) {
            throw new BadRequestException(__('Empty data provided'));
        }

        if (! $this->Users->resetPassword($code, $email, $newPass)) {
            throw new BadRequestException(__('Password could not be updated'));
        }

        $this->set([
            'success' => true,
            'message' => 'Password updated',
            '_serialize' => ['success', 'message']
        ]);
    }

    // /**
    //  * This method allows the user to change his password if the reset token is correct
    //  *
    //  * @param string $passwordchangecode Token
    //  * @return void
    //  */
    // protected function _resetPassword($passwordChangeCode)
    // {
    //     $user = $this->Users->checkPasswordToken($passwordChangeCode);
    //     if (!$user) {
    //         throw new NotFoundException(__('Invalid password change Code'));
    //     }
    //     if (!empty($this->request->data) && $this->Users->resetPassword($user, $this->request->data)) {
    //         $message = __('Password changed');
    //         $success = true;
    //         $this->set([
    //             'success' => $success,
    //             'message' => $message,
    //             '_serialize' => ['success', 'message']
    //         ]);
    //     } else {
    //         throw new NotFoundException(__('Invalid password'));
    //     }
    // }

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
        if (!empty($options)) {
            $user = $this->Users->passwordResetCode($options);//password reset code
            if (!empty($user)) {
                $email = new Email('default');
                $code = $user->passwordchangecode;
                $email->from(['me@example.com' => 'Your System'])
                    ->emailFormat('html')
                    ->template('lost_password', 'default')
                    ->viewVars(['code' => $code, 'url' => $options['url']])
                    ->to($user->email)
                    ->subject('About')
                    ->send();
                if ($admin) {
                    $message = __('has been sent an email with instruction to reset their password.');
                    $success = true;
                } else {
                    $message = __('You should receive an email with further instructions shortly');
                    $success = true;
                }
                $this->set([
                    'success' => $success,
                    'message' => $message,
                    '_serialize' => ['success', 'message']
                ]);
            } else {
                throw new NotFoundException(__('No user was found with that email.'));
            }
        }
    }

    /**
     * Checks if an email is already verified and if not renews the expiration time
     *
     * @return void
     */
    public function sendVerificationEmail()
    {
        $user = $this->Users->get($this->Auth->user('id'));
        $url = $this->request->data['urlVerify'];
        if (empty($user->emailcheckcode)) {
            throw new UnauthorizedException('Email already confirmed');
        } else {
            $user->emailcheckcode = md5(time() * rand());
            if ($this->Users->sendVerificationEmail($user, $url)) {//mudar nome
                $this->Users->save($user);
                $message = __('The email was resent. Please check your inbox.');
                $success = true;
            } else {
                $message = __('The email could not be sent. Please check errors.');
                $success = false;
            }
        }
        
        $this->set([
            'success' => $success,
            'message' => $message,
            '_serialize' => ['success', 'message']
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

    public function info()
    {
        $this->set('user', $this->Auth->user());
        $this->set('_serialize', ['user']);
    }
}
