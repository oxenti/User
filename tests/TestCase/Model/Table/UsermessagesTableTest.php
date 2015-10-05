<?php
namespace User\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use User\Model\Table\UsermessagesTable;

/**
 * App\Model\Table\UsermessagesTable Test Case
 */
class UsermessagesTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.user.usermessages',
        'plugin.user.usertypes',
        'plugin.user.userjuridicaltypes',
        'plugin.user.users',
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
        $config = TableRegistry::exists('Usermessages') ? [] : ['className' => 'User\Model\Table\UsermessagesTable'];
        $this->Usermessages = TableRegistry::get('Usermessages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Usermessages);

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
                'user_id' => 1,
                'target_id' => 2,
                'title' => 'Lorem ipsum dolor sit amet',
                'message' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            ],
            [
                'id' => 44,
                'user_id' => 44,
                'target_id' => 44,
                'original_message_id' => 44,
                'title' => 'Lorem ipsum dolor sit amet',
                'message' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'unread' => 1,
                'chatcode' => 1,
                'created' => '2015-09-16 18:38:19',
                'modified' => '2015-09-16 18:38:19',
                'is_active' => 1
            ],
            [
                'id' => '',
                'user_id' => '',
                'target_id' => '',
                'original_message_id' => '',
                'title' => '',
                'message' => '',
                'unread' => '',
                'chatcode' => '',
            ],
            [
                'id' => 'wqdq',
                'user_id' => 'dqwdqw',
                'target_id' => 'dwqdwqd',
                'original_message_id' => 'dqwdqw',
                'title' => 'Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet ',
                'message' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'unread' => 'fwefew',
                'chatcode' => 'eofjggj'
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
        $case1 = $this->Usermessages->validator()->errors($cases[0]);
        $this->assertEmpty($case1, 'Case1 é valido mas retornou erro');

        $case2 = $this->Usermessages->validator()->errors($cases[1]);
        $this->assertEmpty($case2, 'case2 é valido mas retornou erro');

        $case3 = $this->Usermessages->validator()->errors($cases[2]);
        $expected = ['_empty' => 'This field cannot be left empty'];
        $this->assertNotEmpty($case3, 'Case3 não retornou erro');
        $this->assertEquals($expected, $case3['user_id'], 'não foi retornado erro esperado para user_id vazio');
        $this->assertEquals($expected, $case3['target_id'], 'não foi retornado o erro esperado para targed_id vazio');
        $this->assertEquals($expected, $case3['original_message_id'], 'não foi retornado o erro esperado para original_message_id');
        $this->assertEquals($expected, $case3['title'], 'não foi retornado o erro esperado para title');
        $this->assertEquals($expected, $case3['message'], 'não foi retornado o erro esperado para message');
        $this->assertEquals($expected, $case3['unread'], 'não foi retornado o erro esperado para unread');
        $this->assertEquals($expected, $case3['chatcode'], 'não foi retornado o erro esperado para chatcode');

        $case4 = $this->Usermessages->validator()->errors([]);
        $expected = ['_required' => 'This field is required'];
        $this->assertNotEmpty($case4, 'case4 não retornou erro');
        $this->assertEquals($expected, $case4['user_id'], 'case4 não retornou o erro esperado para user_id inexistente');
        $this->assertEquals($expected, $case4['target_id'], 'case4 não retornou o erro esperado para target_id inexistente');
        $this->assertEquals($expected, $case4['title'], 'case4 não retornou o erro esperado para title inexistente');
        $this->assertEquals($expected, $case4['message'], 'case4 não retornou o erro esperado para message inexistente');

        $case5 = $this->Usermessages->validator()->errors($cases[3]);
        $expected = ['valid' => 'The provided value is invalid' ];
        $this->assertNotEmpty($case5, 'nao retornou erro no case5');
        $this->assertEquals($expected, $case5['id'], 'case5 retorna erro inexperado para id invalido');
        $this->assertEquals($expected, $case5['user_id'], 'case5 retorna erro inexperado para user_id invalido');
        $this->assertEquals($expected, $case5['target_id'], 'case5 retorna erro inexperado para target_id invalido');
        $this->assertEquals($expected, $case5['original_message_id'], 'case5 retorna erro inexperado para original_message_id invalido');
        $this->assertEquals($expected, $case5['unread'], 'case5 retorna erro inexperado para unread invalido');
        $this->assertEquals($expected, $case5['chatcode'], 'case5 retorna erro inexperado para chatcode invalido');
        $expected = ['maxLength' => 'The provided value is invalid'];
        $this->assertEquals($expected, $case5['title'], 'case5 retorna erro inexperado para title invalido');
        

        //$this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     * @dataProvider additionProvider
     * @return void
     */
    public function testBuildRules($cases)
    {
        $case1 = $this->Usermessages->newEntity($cases[0]);
        $result = $this->Usermessages->save($case1);
        $this->assertInstanceOf('User\Model\Entity\Usermessage', $result, 'caso valido mas save não gerou a enttity esperada como resultado');
        $case2 = $this->Usermessages->newEntity($cases[1]);
        $result = $this->Usermessages->save($case2);
        $expected = ['_existsIn' => 'This value does not exist'];
        $this->assertFalse($result, 'case2 é invalido mas não retornmou false');
        $errors = $case2->errors();
        $this->assertEquals($expected, $errors['user_id'], 'case2 retornou erro inesperado para user_id inexistente na tabela users');
        $this->assertEquals($expected, $errors['target_id'], 'case2 retornou erro inesperado para target_id inexistente na tabela users');
        $this->assertEquals($expected, $errors['original_message_id'], 'case2 retornou erro inesperado para original_message_id inexistente na tabela usermessages');

        //$this->markTestIncomplete('Not implemented yet.');
    }
}
