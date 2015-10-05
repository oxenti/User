<?php
namespace User\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use User\Model\Table\UserjuridicaltypesTable;

/**
 * App\Model\Table\UserjuridicaltypesTable Test Case
 */
class UserjuridicaltypesTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.user.userjuridicaltypes',
        'plugin.user.usertypes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Userjuridicaltypes') ? [] : ['className' => 'User\Model\Table\UserjuridicaltypesTable'];
        $this->Userjuridicaltypes = TableRegistry::get('Userjuridicaltypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Userjuridicaltypes);

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
                'name' => 'caso 1'
            ],
            [
                'name' => 'caso 2',
                'created' => '2015-09-15 14:22:29',
                'modified' => '2015-09-15 14:22:29',
                'is_active' => 1
            ],
            [
                'name' => '',
                'created' => '',
                'modified' => '',
                'is_active' => ''
            ],
            
        ];

        return [[$cases]];
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    // public function testInitialize()
    // {
    //     $this->assertNotEmpty($this->Userjuridicaltypes, 'testInitialize na classe UserjuridicaltypesTableTest');
    //     //$this->markTestIncomplete('Not implemented yet.');
    // }

    /**
     * Test validationDefault method
     * @dataProvider additionProvider
     * @return void
     */
    public function testValidationDefault($cases)
    {
        $case1 = $this->Userjuridicaltypes->validator()->errors($cases[0]);
        $this->assertEmpty($case1, 'caso1 é valido mas retornou erro:' . json_encode($case1));

        $case2 = $this->Userjuridicaltypes->validator()->errors($cases[1]);
        $this->assertEmpty($case2, 'caso1 é valido mas retornou erro:' . json_encode($case2));

        $case3 = $this->Userjuridicaltypes->validator()->errors($cases[2]);
        $this->assertNotEmpty($case3, 'Caso invalido não retornou erro');
        $expected = ['_empty' => 'This field cannot be left empty'];
        $this->assertEquals($expected, $case3['name'], 'Case3 retornou um erro inesperado para name:' . json_encode($case3));
        $this->assertEquals($expected, $case3['is_active'], 'Case3 retornou um erro inesperado para is_active:' . json_encode($case3));
        $this->assertFalse(isset($case['create']), 'case3 retornou erro para create vazio' . json_encode($case3));
        $this->assertFalse(isset($case['modified']), 'case3 retornou erro para modified vazio' . json_encode($case3));

        $case4 = $this->Userjuridicaltypes->validator()->errors([]);
        $expected = ['_required' => 'This field is required'];
        $this->assertNotEmpty($case4, 'Case 4 é invalido mas  não retornou erro');
        $this->assertEquals($expected, $case4['name'], 'Erro inesperado para name' . json_encode($case4));
        $this->assertFalse(isset($case['create']), 'case4 retornou erro para create inixistente' . json_encode($case4));
        $this->assertFalse(isset($case['modified']), 'case4 retornou erro para modified inixistente' . json_encode($case4));
        $this->assertFalse(isset($case['modified']), 'case4 retornou erro para is_active vazio' . json_encode($case4));
        //$this->assertNotEmpty($this->Userjuridicaltypes, 'testValidationDefault na classe UserjuridicaltypesTableTest');
        //$this->markTestIncomplete('Not implemented yet.');
    }
}
