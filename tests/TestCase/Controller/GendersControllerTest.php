<?php
namespace User\Test\TestCase\Controller;

use User\Controller\GendersController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\GendersController Test Case
 */
class GendersControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.user.genders',
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
        $result = $this->get('/user/genders');
        $this->assertResponseOk();

        $responseData = json_decode($this->_response->body());
        $count = count($responseData->genders);

        $genders = TableRegistry::get('User.Genders');
        $query = $genders->find('all')
            ->select(['id', 'name'])
            ->order(['Genders.name']);
            
        $gendersJson = json_encode(['genders' => $query->toArray()], JSON_PRETTY_PRINT);
        
        $this->assertEquals($count, $query->count());
        $this->assertResponseOk();
        $this->assertEquals($gendersJson, $this->_response->body());
    }
}
