<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsertokensTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsertokensTable Test Case
 */
class UsertokensTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UsertokensTable
     */
    public $Usertokens;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.usertokens',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Usertokens') ? [] : ['className' => 'App\Model\Table\UsertokensTable'];
        $this->Usertokens = TableRegistry::get('Usertokens', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Usertokens);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
