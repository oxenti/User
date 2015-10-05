<?php
namespace User\Controller;

use Cake\Event\Event;
use Cake\Network\Email\Email;
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

    public function isAuthorized($user)
    {
        // Admin can access every action
        parent::isAuthorized($user);
        if (isset($user['usertype_id'])) {
            if (isset($this->request->params['pass'][0])) {
                if ($this->request->params['pass'][0] == $user['id']) {
                    return true;
                }
            }
            return true;
        }
        // Default deny
        throw new UnauthorizedException('UnauthorizedException ');
        return false;
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['token', 'add', 'verify', 'reset_password', 'linkedin_handler']);
    }

    public function logout()
    {
        //ações para invalidar o token do usuario
        return $this->redirect($this->Auth->logout());
    }

    public function token()
    {
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('Invalid username or password');
        }
        $this->set([
            'success' => true,
            'data' => [
                'token' => $this->_makeToken($user['id'])
            ],
            '_serialize' => ['success', 'data']
        ]);
    }

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
        $this->paginate = [
            'contain' => ['Usertypes', 'Genders']
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
            ->contain(['Usertypes', 'Genders'])
            ->first();
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
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Users->sendVerificationEmail($user);
                debug($user);
                $message = 'The user has been saved.';
                $this->set([
                    'success' => true,
                    'message' => $message,
                    '_serialize' => ['success', 'message']
                ]);
            } else {
                debug($user);
                throw new NotFoundException('The user could not be saved. Please, try again.');
            }
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
        $user = $this->Users->get($userId);
        if ($this->request->is('put')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
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
        $this->request->allowMethod(['put']);
        if ($emailcheckcode != null) {
            $this->request->data['emailcheckcode'] = $emailcheckcode;
        }
        if ($this->request->is('put')) {
            $user = $this->Users->find('all', [
                'conditions' => [
                    'emailcheckcode' => $this->request->data['emailcheckcode']
                ]
            ])->first();
            if (empty($user)) {
                throw new NotFoundException(__('Bad identification data'));
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
    }

    /**
     * Reset Password Action
     *
     * Handles the trigger of the reset, also takes the token, validates it and let the user enter
     * a new password.
     *
     * @return void
     */
    public function reset_password()
    {
        if ($this->request->is('post')) {
            if (isset($this->request->data['passwordchangecode'])) {
                $this->_resetPassword($this->request->data['passwordchangecode']);
            } else {
                $this->_sendPasswordResetCode($this->request->data);
            }
        }
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
        if (!$user) {
            throw new NotFoundException(__('Invalid password change Code'));
        }
        if (!empty($this->request->data) && $this->Users->resetPassword($user, $this->request->data)) {
            $message = __('Password changed');
            $success = true;
            $this->set([
                'success' => $success,
                'message' => $message,
                '_serialize' => ['success', 'message']
            ]);
        } else {
            throw new NotFoundException(__('Invalid password'));
        }
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
    public function send_verification()
    {
        $user = $this->Users->get($this->Auth->user('id'));
        if (empty($user->emailcheckcode)) {
            throw new UnauthorizedException('Email already confirmed');
        } else {
            $user->emailcheckcode = md5(time() * rand());
            if ($this->Users->sendVerificationEmail($user)) {//mudar nome
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

    public function linkedin_handler()
    {
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
