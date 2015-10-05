<?php
namespace User\Controller;

use Cake\Event\Event;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use User\Controller\AppController;

/**
 * Usermessages Controller
 *
 * @property \App\Model\Table\UsermessagesTable $Usermessages
 */
class UsermessagesController extends AppController
{

    public function isAuthorized($user)
    {
        parent::isAuthorized($user);
        if (isset($user['usertype_id'])) {
            if (isset($this->request->params['user_id'])) {
                if ($this->request->params['user_id'] == $user['id']) {
                    return true;
                } else {
                    throw new UnauthorizedException('UnauthorizedException ');
                    return false;
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
    }
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        if (isset($this->request->params['user_id'])) {
            $userId = $this->request->params['user_id'];
        } else {
            $user = $this->Auth->user();
            $userId = $user['id'];
        }

        $query = $this->Usermessages->find()->contain(['Sender', 'Receiver'])
            ->select(['id', 'title', 'message', 'original_message_id', 'chatcode', 'unread', 'created',
                'Sender.id', 'Sender.first_name', 'Sender.last_name', 'Receiver.id', 'Receiver.first_name', 'Receiver.last_name'])
            ->orWhere(['Sender.id' => $userId])
            ->orwhere([ 'Receiver.id' => $userId]);
        $this->set('usermessages', $this->paginate($query));
        $this->set('_serialize', ['usermessages']);
    }

    /**
     * View method
     *
     * @param string|null $id Usermessage id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $userId = (isset($this->request->params['user_id']))?$this->request->params['user_id']:$this->Auth->user()['id'];
        $usermessage = $this->Usermessages->find()
            ->contain(['Sender', 'Receiver'])
            ->select(['id', 'title', 'message', 'original_message_id', 'chatcode', 'unread', 'created',
                'Sender.id', 'Sender.first_name', 'Sender.last_name', 'Receiver.id', 'Receiver.first_name', 'Receiver.last_name'])
            ->orWhere(['Sender.id' => $userId])
            ->orwhere([ 'Receiver.id' => $userId])
            ->andwhere(['Usermessages.id' => $id])
            ->first();
        if (is_null($usermessage)) {
            throw new NotFoundException(__('The message could not be finded'));
        }
        $this->set('usermessage', $usermessage);
        $this->set('_serialize', ['usermessage']);
    }

    /**
     * Add method
     *
     */
    public function add()
    {
        $usermessage = $this->Usermessages->newEntity();
        if ($this->request->is('post')) {
            if (isset($this->request->params['user_id'])) {
                $this->request->data['user_id'] = $this->request->params['user_id'];
            }
            $usermessage = $this->Usermessages->patchEntity($usermessage, $this->request->data);
            if ($this->Usermessages->save($usermessage)) {
                $message = 'The message has been saved.';
                $this->set([
                    'success' => true,
                    'message' => $message,
                    '_serialize' => ['success', 'message']
                ]);
            } else {
                throw new NotFoundException(__('The message could not be saved. Please, try again.'));
            }
        }
    }
}
