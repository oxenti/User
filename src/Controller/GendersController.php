<?php
namespace User\Controller;

use Cake\Event\Event;
use User\Controller\AppController;

/**
 * Genders Controller
 *
 * @property \App\Model\Table\GendersTable $Genders
 */
class GendersController extends AppController
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
        $finder = !isset($this->request->query['finder'])?'All': $this->request->query['finder'];
        $this->paginate = [
           'finder' => $finder,
           'order' => ['Genders.name'],
        ];
        $this->set('genders', $this->paginate($this->Genders));
        $this->set('_serialize', ['genders']);
    }
}
