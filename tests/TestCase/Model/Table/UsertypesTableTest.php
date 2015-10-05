<?php
namespace User\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use User\Model\Table\UsertypesTable;

/**
 * User\Model\Table\UsertypesTable Test Case
 */
class UsertypesTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.user.usertypes',
        'plugin.user.userjuridicaltypes',
        'plugin.user.users',
        // 'plugin.user.genders',
        // 'plugin.user.usermessages',
        // 'plugin.user.sender',
        // 'plugin.user.receiver'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Usertypes') ? [] : ['className' => 'User\Model\Table\UsertypesTable'];
        $this->Usertypes = TableRegistry::get('User.Usertypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Usertypes);

        parent::tearDown();
    }

    /**
     * additionProvider method
     *
     * @return array
     */
    public function additionProvider()
    {
        $cases = [
            [
                'userjuridicaltype_id' => 1,
                'name' => 'case1',
            ],
            [
                'userjuridicaltype_id' => 4,
                'name' => 'case2',
                'created' => '2015-09-15 14:22:14',
                'modified' => '2015-09-15 14:22:14',
                'is_active' => 0
            ],
            [
                'userjuridicaltype_id' => '',
                'name' => '',
                'created' => '',
                'modified' => '',
                'is_active' => ''
            ],
            [
                'userjuridicaltype_id' => 'fmjfhjsd',
                'name' => 'case2',
                'created' => '2015-09-15 14:22:14',
                'modified' => '2015-09-15 14:22:14',
                'is_active' => 'active'
            ],



        ];

        return [[$cases]];
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        //$this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     * @dataProvider additionProvider
     * @return void
     */
    public function testValidationDefault($cases)
    {
        $case1 = $this->Usertypes->validator()->errors($cases[0]);
        $this->assertEmpty($case1, 'caso1 é valido mas retornou erro:' . json_encode($case1));

        $case2 = $this->Usertypes->validator()->errors($cases[1]);
        $this->assertEmpty($case1, 'caso2 é valido mas retornou erro:' . json_encode($case1));
        
        $case3 = $this->Usertypes->validator()->errors($cases[2]);
        $expected = ['_empty' => 'This field cannot be left empty'];
        $this->assertNotEmpty($case3, 'case3 é inavalido mas não retornou erro');
        $this->assertEquals($expected, $case3['userjuridicaltype_id'], 'Erro não esperado para userjuridicaltype_id' . json_encode($case3));
        $this->assertEquals($expected, $case3['name'], 'Erro não esperado para name ' . json_encode($case3));
        $this->assertEquals($expected, $case3['is_active'], 'Erro não esperado para is_active ' . json_encode($case3));
        $this->assertFalse(isset($case3['created']), 'case3 retorno erro para create vazio: ' . json_encode($case3));
        $this->assertFalse(isset($case3['modified']), 'case3 retorno erro para modified vazio: ' . json_encode($case3));

        $case4 = $this->Usertypes->validator()->errors([]);
        $expected = ['_required' => 'This field is required' ];
        $this->assertNotEmpty($case4, 'case é invalido mas não retorno erro');
        $this->assertEquals($expected, $case4['userjuridicaltype_id'], 'Case4 não retornou o erro esperado para userjuridicaltype_id ');
        $this->assertEquals($expected, $case4['name'], 'case4 não retornou o erro esperado para name');

        $case5 = $this->Usertypes->validator()->errors($cases[3]);
        $expected = ['valid' => 'The provided value is invalid'];
        $this->assertNotEmpty($case5, 'Case5 é invalido mas não retorno etrro');
        $this->assertEquals($expected, $case5['userjuridicaltype_id'], 'Mensagem de erro inesperada para userjuridicaltype_id no case5: ' . json_encode($case5));
        $this->assertEquals($expected, $case5['is_active'], 'Mensagem de erro inesperada para is_active no case5: ' . json_encode($case5));
    }

    /**
     * Test buildRules method
     * @dataProvider additionProvider
     * @return void
     */
    public function testBuildRules($cases)
    {
         //caso valido
        $usertypeValido = $this->Usertypes->newEntity($cases[0]);
        $case1 = $this->Usertypes->save($usertypeValido);
        $this->assertInstanceOf('User\Model\Entity\Usertype', $case1, 'O retorno do save no caso valido não foi o esperado');
        $this->assertEmpty($usertypeValido->errors, 'caso valido retornou algum erro ');
        //case userjuridicaltype não existente
        $usertypeInvalido = $this->Usertypes->newEntity($cases[1]);
        $case2 = $this->Usertypes->save($usertypeInvalido);
        $expected = ['_existsIn' => 'This value does not exist'];
        $this->assertEquals($expected, $usertypeInvalido->errors('userjuridicaltype_id'), 'não foi retornado o erro esperado para user_id inexistente em users');
    }
}
