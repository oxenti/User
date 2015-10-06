<?php
namespace User\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use User\Controller\UsersController;

/**
 * User\Controller\UsersController Test Case
 */
class UsersControllerTest extends IntegrationTestCase
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
        'plugin.user.usermessages',
        'plugin.user.usersocialdata',
        // 'plugin.user.addresses',
        // 'plugin.user.cities',
        // 'plugin.user.states',
        // 'plugin.user.countries',
    ];


    public function tokenProvider()
    {
        $token = [];
        $data = [
            'email' => 'root@root.com',
            'password' => 'qwe123'
        ];
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->post('/user/users/token', $data);
        // debug($this->_response->body());die();
        $respondeData = json_decode($this->_response->body());
        $token['Admin'] = 'Bearer ' . $respondeData->data->token;
        return [[$token]];
    }

    /**
     * Test token method
     *
     * @return void
     */
    public function testValidToken()
    {
        $data = [
            'email' => 'root@root.com',
            'password' => 'qwe123'
        ];
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->post('/user/users/token', $data);
        $this->assertResponseOk();
        $this->assertResponseContains('"success": true');
        $respondeData = json_decode($this->_response->body());
        $this->token['Admin'] = 'Bearer ' . $respondeData->data->token;
    }

    /**
     * Test token method
     * Try to get a token with a invalid credentials
     * @return void
     */
    public function testInvalidToken()
    {
        $data = [
            'email' => 'root@root.com',
            'password' => 'qwe11'
        ];
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $requestResult = $this->post('/user/users/token', $data);
        $this->assertResponseError();
    }

    /**
     * Test index method
     * @dataProvider tokenProvider
     */
    public function testIndex($token)
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'Authorization' => $token['Admin']]
        ]);
        $result = $this->get('/user/users');

        $this->assertResponseOk();
        
        $respondeData = json_decode($this->_response->body());
        $count = count($respondeData->users);

        $users = TableRegistry::get('User.Users');
        $query = $users->find('all')->contain(['Usertypes', 'Genders']);
        // debug($query);
        $usersJson = json_encode(['users' => $query], JSON_PRETTY_PRINT);
        
        $this->assertEquals($count, $query->count());
        $this->assertEquals($usersJson, $this->_response->body());
    }

    /**
     * Test info method
     * @dataProvider tokenProvider
     * @return void
     */
    public function testView($token)
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'Authorization' => $token['Admin']]
        ]);
        $result = $this->get('/user/users/view/1');

        // Check that the response was a 200
        $this->assertResponseOk();
        $users = TableRegistry::get('User.Users');
        $user = $users->find()
            ->where(['Users.id' => 1])
            ->contain(['Usertypes', 'Genders'])
            ->first();
        $usersJson = json_encode(['user' => $user], JSON_PRETTY_PRINT);
        $this->assertEquals($usersJson, $this->_response->body());
    }

    /**
     * Test info method
     *
     * @return void
     */
    public function testInfoUnauthorized()
    {
        $result = $this->get('/user/users/view/1');
        $this->assertResponseError();
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $data = [
            'usertype_id' => 1,
            'gender_id' => 1,
            'first_name' => 'usuario',
            'last_name' => 'teste ',
            'email' => 'testeAdd@root.com',
            'password' => 'qwe123',
            'created' => ''
        ];
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->post('/user/users', $data);
        $this->assertResponseSuccess();
        $users = TableRegistry::get('User.Users');
        $query = $users->find()->where(['email' => $data['email']]);
        $this->assertEquals(1, $query->count());
    }

    /**
     * Test edit method
     * @dataProvider tokenProvider
     * @return void
     */
    public function testEditWithoutAddress($token)
    {
        $data = [
            'id' => 1,
            'usertype_id' => 1,
            'gender_id' => 1,
            'first_name' => 'usuario',
            'last_name' => 'teste ',
            'email' => 'rootModificado@root.com',
            'password' => 'qwe123'
        ];

        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'Authorization' => $token['Admin']]
        ]);
        $this->put('/user/users/1', $data);

        $this->assertResponseSuccess();

        $users = TableRegistry::get('User.Users');
        $query = $users->find()->where(['email' => $data['email']]);
        $this->assertEquals(1, $query->count());
    }

    // /**
    //  * Test edit method
    //  *
    //  * @return void
    //  */
    // public function testEditWithAddress()
    // {
    //     $data = [
    //         'id' => 1,
    //         'usertype_id' => 1,
    //         'gender_id' => 1,
    //         'first_name' => 'usuario',
    //         'last_name' => 'teste ',
    //         'email' => 'rootModificado@root.com',
    //         'password' => 'qwe123',
    //         'address' => [
    //             'city_id' => 1,
    //             'street' => 'Novo Endereco',
    //             'neighborhood' => 'Novo bairro',
    //         ]
    //     ];

    //     $this->configRequest([
    //         'headers' => ['Accept' => 'application/json', 'Authorization' => $this->token['Admin']]
    //     ]);
    //     $this->put('/user/users/1', $data);

    //     $this->assertResponseSuccess();
    //     $users = TableRegistry::get('Users');
    //     $query = $users->find()->where(['email' => $data['email']]);
    //     $this->assertEquals(1, $query->count());

    //     $addresses = TableRegistry::get('Addresses');
    //     $query = $addresses->find()->where(['user_id' => 1]);
    //     $this->assertEquals(1, $query->count());
    // }

    /**
     * Test Unauthorized Edit method
     *
     * @return void
     */
    public function testUnauthorizedEdit()
    {
        $data = [
            'id' => 1,
            'usertype_id' => 1,
            'gender_id' => 1,
            'first_name' => 'usuario',
            'last_name' => 'teste ',
            'email' => 'rootModificado@root.com',
            'password' => 'qwe123'
        ];
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->put('/user/users/1', $data);
        $this->assertResponseError();
    }

    /**
     * Test verify method
     *
     * @return void
     */
    public function testVerifyEmail()
    {
        $users = TableRegistry::get('User.Users');
        $data = [
            'emailcheckcode' => 'Lorem ipsum dolor sit amet'
        ];
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $user = $users->find()->where(['emailcheckcode' => $data['emailcheckcode']])->first();
        $this->put('/user/users/verify', $data);
        $this->assertResponseSuccess();
        $user = $users->find()->where(['id' => $user->id ])->first();
        $this->assertEquals('', $user->emailcheckcode);
        //try to verify an invalid check code.
    }

    /**
     * Test verify method
     * @dataProvider tokenProvider
     * @return void
     */
    public function testResendVerification($token)
    {
        $users = TableRegistry::get('User.Users');
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'Authorization' => $token['Admin']]
        ]);

        $user = $users->get(1);
        $oldCode = $user->emailcheckcode;
        $this->get('/user/users/send_verification');
        $this->assertResponseSuccess();
        $user = $users->get(1);
        $newCode = $user->emailcheckcode;

        $this->assertNotEquals($oldCode, $newCode, 'Os codigos de verificação de email devem ser diferente');
        //try to resend a emial to a verified user

        $data = [
            'emailcheckcode' => $newCode
        ];
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'Authorization' => $token['Admin']]
        ]);
        $this->put('/user/users/verify', $data);
        $this->assertResponseOk();
        $this->put('/user/users/verify', $data);
        $this->assertResponseError();
    }

    /**
     * Test verify method
     * Test verify methods with an invalid token
     * @return void
     */
    public function testInvalidVerifyEmail()
    {
        $users = TableRegistry::get('User.Users');
        $data = [
            'emailcheckcode' => 'invalid emailcheckcode'
        ];
        $user = $users->find()->where(['emailcheckcode' => $data['emailcheckcode']])->first();

        $this->assertEquals('', $user);

        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->put('/user/users/verify', $data);
        $this->assertResponseError();
    }


    /**
     * Test reset_password method
     * Test verify reset password
     * @return void
     */
    public function testResetPasswordSendEmailCode()
    {
        $users = TableRegistry::get('User.Users');
        $data = [
            'email' => 'root@root.com',
            'url' => 'www.acadios.com.br/reset_password'
        ];
        $olPasswordChangeCode = $users->get(1)->passwordchangecode;

        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->post('/user/users/reset_password', $data);
        $this->assertResponseOk();
        $passwordChangeCode = $users->get(1)->passwordchangecode;

        $this->assertNotEquals($passwordChangeCode, $olPasswordChangeCode, 'message');
    }


    /**
     * Test reset_password method
     * Test verify reset password
     * @return void
     */
    public function testResetPasswordWithCode()
    {
        $users = TableRegistry::get('User.Users');
        $data = [
            'passwordchangecode' => 'Lorem ipsum dolor sit amet',
            'new_password' => 'Senha de Test',
            'confirm_password' => 'Senha de Test'
        ];
        $oldPassword = $users->get(1)->password;
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->post('/user/users/reset_password', $data);
        $this->assertResponseOk();

        $user = $users->get(1);
        $this->assertNotEquals($oldPassword, $user->password, 'Verificar que os password sao diferentes');
        $this->assertEmpty($user->passwordchangecode, 'message');
        $data = [
            'passwordchangecode' => 'Codigo invalido',
            'new_password' => 'Senha de Test',
            'confirm_password' => 'Senha de Test'
        ];
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->post('/user/users/reset_password', $data);
        $this->assertResponseError();
        
        $data = [
            'passwordchangecode' => 'Lorem ipsum dolor sit amet',
            'new_password' => 'Senha de Test',
            'confirm_password' => 'Senha de diferente'
        ];
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->post('/user/users/reset_password', $data);
        $this->assertResponseError();
    }

    /**
     *
     */
    public function testLinkedinHandler()
    {
        $data = [
            'usersocialdata' => [
                'linkedin_token' => '5Oc-x4xwObe9woICn67sITEscf_YQ4Zt-DyxxUmVWNeuJm2UWVwR-AiSp69vhybc6veCSxLCgwWDHoTGBPhZtHxqEal_VSBFCKef6FyW4fOgNiZHeZo5hYPl5qY_g'
            ]
        ];
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->post('/user/users/linkedin_handler', $data);
        $this->assertResponseError();
    }


    // /**
    //  * Test delete method
    //  *
    //  * @return void
    //  */
    // public function testDelete()
    // {
    //     $this->markTestIncomplete('Not implemented yet.');
    // }
}
