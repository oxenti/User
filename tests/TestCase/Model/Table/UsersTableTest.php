<?php
namespace User\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use User\Model\Table\UsersTable;

/**
 * User\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.user.users',
        'plugin.user.usertypes',
        'plugin.user.userjuridicaltypes',
        'plugin.user.genders',
        // 'plugin.user.addresses',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Users') ? [] : ['className' => 'User\Model\Table\UsersTable'];
        $this->Users = TableRegistry::get('User.Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Users);

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
                'usertype_id' => 2,
                'gender_id' => 1,
                'first_name' => 'Lorem ipsum dolor sit amet',
                'last_name' => 'Lorem ipsum dolor sit amet',
                'password' => 'qwe123',
                'email' => 'emaildetestedoidao@root.com',
            ],
            [
                'id' => 1,
                'usertype_id' => 77,
                'gender_id' => 88,
                'first_name' => 'Lorem ipsum dolor sit amet',
                'last_name' => 'Lorem ipsum dolor sit amet',
                'birth' => '2015-09-14',
                'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'phone1' => '554554454545',
                'phone2' => '665565656565',
                'password' => '$2y$10$W8cHelHWOsN/uOoJlexrv.gMQiJ8LBq4hE8CAPI6.qYJpQQfn6i9u',
                'email' => 'root@root.com',
                'emailcheckcode' => 'Lorem ipsum dolor sit amet',
                'passwordchangecode' => 'Lorem ipsum dolor sit amet',
                'is_active' => 1,
                'expire_account' => '2015-09-14',
                'created' => '2015-09-14 19:57:29',
                'modified' => '2015-09-14 19:57:29'
            ],
            [
                'usertype_id' => '',
                'gender_id' => '',
                'first_name' => '',
                'last_name' => '',
                'birth' => '',
                'avatar_path' => '',
                'phone1' => '',
                'phone2' => '',
                'password' => '',
                'email' => '',
                'emailcheckcode' => '',
                'passwordchangecode' => '',
                'is_active' => '',
                'expire_account' => '',
                'created' => '',
                'modified' => ''
            ],
            [
                'id' => 'hsdjkso',
                'usertype_id' => 'bfvugeu',
                'gender_id' => 'sfkvojpjv',
                'first_name' => 'Lorem ipsum dolor sit amet',
                'last_name' => 'Lorem ipsum dolor sit amet',
                'birth' => 'mgmoperopremh',
                'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'phone1' => 'Lorem ipsum dolor sit amet',
                'phone2' => 'Lorem ipsum dolor sit amet',
                'password' => 'usgousoghs',
                'email' => 'ewgrhgrhreh',
                'emailcheckcode' => 'Lorem ipsum dolor sit amet',
                'passwordchangecode' => 'Lorem ipsum dolor sit amet',
                'is_active' => 'dgsdrrgr',
                'expire_account' => 'ewfwefwefwef',
            ],
            [
                'id' => 1,
                'usertype_id' => 2,
                'gender_id' => 1,
                'first_name' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis',
                'last_name' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'birth' => '2015-09-14',
                'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'phone1' => '45545544545455446454465',
                'phone2' => '5665565656565655464565',
                'password' => '$2y$10$W8cHelHWOsN/uOoJlexrv.gMQiJ8LBq4hE8CAPI6.qYJpQQfn6i9u',
                'email' => 'root$2y$10$W8cHelHWOsN/uOoJlexrv.gMQiJ8LBq4hE8CAPI6.qYJpQQfn6i9u$2y$10$W8cHelHWOsN/uOoJlexrv.gMQiJ8LBq4hE8CAPI6.qYJpQQfn6i9u$2y$10$W8cHelHWOsN/uOoJlexrv.gMQiJ8LBq4hE8CAPI6.qYJpQQfn6i9u@root.com',
                'emailcheckcode' => 'Lorem ipsum dolor sit amet',
                'passwordchangecode' => 'Lorem ipsum dolor sit amet',
                'is_active' => 1,
                'expire_account' => '2015-09-14',
                'created' => '2015-09-14 19:57:29',
                'modified' => '2015-09-14 19:57:29'
            ]
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
        $case1 = $this->Users->validator()->errors($cases[0]);
        $this->assertEmpty($case1, 'Case1 é valido mas retornou erro:' . json_encode($case1));

        $case2 = $this->Users->validator()->errors($cases[1]);
        $this->assertEmpty($case2, 'Case2 é valido mas retornou erro:' . json_encode($case2));

        $case3 = $this->Users->validator()->errors($cases[2]);
        $expected = ['_empty' => 'This field cannot be left empty'];
        $this->assertNotEmpty($case3, 'Case3 é invalido mas naõ retornou erro ');
        $this->assertEquals($expected, $case3['usertype_id'], 'Case3 retorno messagem inesperada para usertype_id vazio: ' . json_encode($case3));
        $this->assertEquals($expected, $case3['gender_id'], 'Case3 retorno messagem inesperada para gender_id vazio: ' . json_encode($case3));
        $this->assertEquals($expected, $case3['first_name'], 'Case3 retorno messagem inesperada para first_name vazio: ' . json_encode($case3));
        $this->assertEquals($expected, $case3['last_name'], 'Case3 retorno messagem inesperada para last_name vazio: ' . json_encode($case3));
        $this->assertEquals($expected, $case3['password'], 'Case3 retorno messagem inesperada para password vazio: ' . json_encode($case3));
        $this->assertEquals($expected, $case3['email'], 'Case3 retorno messagem inesperada para email vazio: ' . json_encode($case3));
        $this->assertEquals($expected, $case3['is_active'], 'Case3 retorno messagem inesperada para is_active vazio: ' . json_encode($case3));
        $this->assertArrayNotHasKey('avatar_path', $case3, 'Case3 retorno erro para avatar_path: ' . json_encode($case3));
        $this->assertArrayNotHasKey('phone1', $case3, 'Case3 retorno erro para phone1: ' . json_encode($case3));
        $this->assertArrayNotHasKey('phone2', $case3, 'Case3 retorno erro para phone2: ' . json_encode($case3));
        $this->assertArrayNotHasKey('emailcheckcode', $case3, 'Case3 retorno erro para emailcheckcode: ' . json_encode($case3));
        $this->assertArrayNotHasKey('passwordchangecode', $case3, 'Case3 retorno erro para passwordchangecode: ' . json_encode($case3));
        $this->assertArrayNotHasKey('expire_account', $case3, 'Case3 retorno erro para expire_account: ' . json_encode($case3));

        $case4 = $this->Users->validator()->errors([]);
        $expected = ['_required' => 'This field is required'];
        $this->assertNotEmpty($case4, 'Case4 é invalido mais não retornou erro');
        $this->assertEquals($expected, $case4['usertype_id'], 'Erro inesperado no case4 para usertype_id: ' . json_encode($case4));
        $this->assertEquals($expected, $case4['gender_id'], 'Erro inesperado no case4 para gender_id: ' . json_encode($case4));
        $this->assertEquals($expected, $case4['first_name'], 'Erro inesperado no case4 para first_name: ' . json_encode($case4));
        $this->assertEquals($expected, $case4['last_name'], 'Erro inesperado no case4 para last_name: ' . json_encode($case4));
        $this->assertEquals($expected, $case4['password'], 'Erro inesperado no case4 para password: ' . json_encode($case4));
        $this->assertEquals($expected, $case4['email'], 'Erro inesperado no case4 para email: ' . json_encode($case4));

        $case5 = $this->Users->validator()->errors($cases[3]);
        $expected = ['valid' => 'The provided value is invalid'];
        $this->assertNotEmpty($case5, 'Case5 é invalido mas não retornou erro');
        $this->assertEquals($expected, $case5['id'], 'case5 retornou erro inesperado para id: ' . json_encode($case5));
        $this->assertEquals($expected, $case5['usertype_id'], 'case5 retornou erro inesperado para usertype_id: ' . json_encode($case5));
        $this->assertEquals($expected, $case5['gender_id'], 'case5 retornou erro inesperado para gender_id: ' . json_encode($case5));
        $this->assertEquals($expected, $case5['birth'], 'case5 não retornou erro inesperado para birth: ' . json_encode($case5));
        $this->assertEquals($expected, $case5['email'], 'case5 não retornou erro inesperado para email: ' . json_encode($case5));
        $this->assertEquals($expected, $case5['is_active'], 'case5 não retornou erro inesperado para is_active: ' . json_encode($case5));
        $this->assertEquals($expected, $case5['phone1'], 'case5 não retornou erro inesperado para phone1: ' . json_encode($case5));
        $this->assertEquals($expected, $case5['phone2'], 'case5 não retornou erro inesperado para phone2: ' . json_encode($case5));
        //testando validação de tamanho da entrada
        $case6 = $this->Users->validator()->errors($cases[4]);
        $expected = ['maxLength' => 'The provided value is invalid'];
        $this->assertNotEmpty($case6, 'Caso 6 é invalido mas não retornou erro');
        $this->assertEquals($expected, $case6['first_name'], 'case não retorna erro esperado para first_name: ' . json_encode($case6));
        $this->assertEquals($expected, $case6['last_name'], 'case não retorna erro esperado para last_name: ' . json_encode($case6));
        $this->assertEquals($expected, $case6['phone1'], 'case não retorna erro esperado para phone1: ' . json_encode($case6));
        $this->assertEquals($expected, $case6['phone2'], 'case não retorna erro esperado para phone2: ' . json_encode($case6));
        $this->assertEquals($expected, $case6['email'], 'case não retorna erro esperado para email: ' . json_encode($case6));
        //$this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     * @dataProvider additionProvider
     * @return void
     */
    public function testBuildRules($cases)
    {
        $case1 = $this->Users->newEntity($cases[0]);
        $result = $this->Users->save($case1);
        $this->assertInstanceOf('User\Model\Entity\User', $result, 'Caso valido não gerou obejeto esperado');
        debug($result);
        $case2 = $this->Users->newEntity($cases[1]);
        $result = $this->Users->save($case2);
        $this->assertFalse($result, 'caso incvalido n retornou false');
        $expected = ['_existsIn' => 'This value does not exist' ];
        $errors = $case2->errors();
        $this->assertEquals($expected, $errors['usertype_id'], 'não foi retornado erro para usertype invalido');
        $this->assertEquals($expected, $errors['gender_id'], 'não foi retornado erro para gender invalido');
        $expected = ['_isUnique' => 'This value is already in use'];
        $this->assertEquals($expected, $errors['email'], 'message');
        //$this->markTestIncomplete('Not implemented yet.');
    }

    public function testPasswordValidate()
    {
        $data = [
            'new_password' => '123',
            'confirm_password' => '123'
        ];
        $user = $this->Users->get(1);
        $oldPassword = $user->password;

        $teste = $this->Users->resetPassword($user, $data);
        $this->assertTrue($teste, 'metodo deveria retornar true');

        $user = $this->Users->get(1);
        $newPassword = $user->password;
        $this->assertNotEquals($oldPassword, $newPassword, 'passwords deveriam ser diferentes');

        $data = [
            'new_password' => '123',
            'confirm_password' => 'arroz'
        ];
        $teste = $this->Users->resetPassword($user, $data);
        $this->assertFalse($teste, 'metodo deveria retornar false');
    }

    public function testCheckPasswordToken()
    {
        $passwordChangeCode = 'Lorem ipsum dolor sit amet';

        $user = $this->Users->checkPasswordToken($passwordChangeCode);
        $this->assertInstanceOf('User\Model\Entity\User', $user, 'Caso valido não gerou obejeto esperado');

        $passwordChangeCode = 'Codigo Invalido!';

        $user = $this->Users->checkPasswordToken($passwordChangeCode);
        $this->assertFalse($user, 'Codigo invalido deve retornar false');
    }

    public function testHash()
    {
        $password = 'Lorem ipsum dolor sit amet';

        $hashed = $this->Users->hash($password);
        $this->assertNotSame($password, $hashed, 'texto e hash devem ser diferentes');
    }

    public function testpasswordResetCode()
    {
        $data = [
            'email' => 'root@root.com'
        ];
        $user = $this->Users->passwordResetCode($data);
        $this->assertInstanceOf('User\Model\Entity\User', $user, 'Caso valido não gerou obejeto esperado');
        $this->assertNotEmpty($user->passwordchangecode, 'message');
        
        // caso invalido
        $data = [
            'email' => 'emailque@naoexiste.com'
        ];
        $user = $this->Users->passwordResetCode($data);
        $this->assertFalse($user, 'should return false');
    }
}
