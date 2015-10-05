<?php
namespace User\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use User\Model\Table\GendersTable;

/**
 * App\Model\Table\GendersTable Test Case
 */
class GendersTableTest extends TestCase
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
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('User.Genders') ? [] : ['className' => 'User\Model\Table\GendersTable'];
        $this->Genders = TableRegistry::get('User.Genders', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Genders);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->assertNotEmpty($this->Genders);
        $this->assertNotEmpty($this->Genders->Associations(), 'message');
        $this->assertNotEmpty($this->Genders->Behaviors());
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
//*************** Entradas validas********************************** 
        $gender = $this->Genders->newEntity([
            'name' => 'Masculino'
        ]);
        $this->assertEmpty($gender->errors());
       
        $gender = $this->Genders->newEntity([
            'name' => 'Masculino',
            'created' => '2015-09-15 14:45:05',
            'modified' => '2015-09-15 14:45:05',
            'is_active' => 1
        ]);
        $this->assertEmpty($gender->errors());

//*************** Entradas NÃO validas **********************************
        $teste = $this->Genders->newEntity();
        $gender = $this->Genders->validator()->errors([
            'name' => '',
            'created' => '2015-09-15 14:45:05',
            'modified' => '2015-09-15 14:45:05',
            'is_active' => 1
        ]);
        $expected = ['_empty' => 'This field cannot be left empty'];
        $this->assertEquals($expected, $gender['name'], 'Campo name vazio');//($gender->errors(), 'message');

        $gender = $this->Genders->validator()->errors([
            'created' => '2015-09-15 14:45:05',
            'modified' => '2015-09-15 14:45:05',
            'is_active' => 1
        ]);
        $expected = ['_required' => 'This field is required'];
        $this->assertEquals($expected, $gender['name'], 'campo name inexistente');
    }
}
