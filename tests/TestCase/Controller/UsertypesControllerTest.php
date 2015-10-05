<?php
namespace User\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use User\Controller\UsertypesController;

/**
 * App\Controller\UsertypesController Test Case
 */
class UsertypesControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.user.usertypes',
        'plugin.user.userjuridicaltypes',
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $result = $this->get('/user/usertypes');

        $respondeData = json_decode($this->_response->body());
        $count = count($respondeData->usertypes);

        $users = TableRegistry::get('Usertypes');
        $query = $users->find('all')->select(['id', 'name']);
        $usertypeJson = json_encode(['usertypes' => $query], JSON_PRETTY_PRINT);
        
        $this->assertEquals($count, $query->count());
        $this->assertResponseOk();
        $this->assertEquals($usertypeJson, $this->_response->body());
    }
}
