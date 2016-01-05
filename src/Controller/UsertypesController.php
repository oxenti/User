<?php
namespace User\Controller;

use Cake\Event\Event;
use User\Controller\AppController;

/**
 * Usertypes Controller
 *
 * @property \App\Model\Table\UsertypesTable $Usertypes
 */
class UsertypesController extends AppController
{
    /**
     * beforeFilter function
     * Allow everyone access the gender list
     *
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['index']);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $finder = isset($this->request->query['complete'])? 'all' : 'list';
        
        $this->paginate = [
           'finder' => $finder,
           'conditions' => ['id <>' => 100],
           'order' => ['Usertypes.name'],
        ];

        $this->set('usertypes', $this->paginate($this->Usertypes));
        $this->set('_serialize', ['usertypes']);
    }
}
