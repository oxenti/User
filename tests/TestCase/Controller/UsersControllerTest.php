<?php
namespace User\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use Cake\Utility\Security;
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
        'plugin.user.personalinformations',
        'plugin.user.users',
        'plugin.user.usertypes',
        'plugin.user.userjuridicaltypes',
        'plugin.user.usersocialdata',
    ];

    public $basicToken = '';

    public function setup()
    {
        // Add a new user
        $data = [
            'usertype_id' => 1,
            'gender_id' => 1,
            'first_name' => 'usuario',
            'last_name' => 'teste ',
            'email' => 'tokenuser@test.com',
            'password' => 'qwe123',
            'created' => ''
        ];

        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->post('/user/users', $data);
        $responseData = json_decode($this->_response->body());
        $this->assertResponseSuccess();

        $data = [
            'email' => 'tokenuser@test.com',
            'password' => 'qwe123',
        ];
        
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->post('/user/users/get_token', $data);
        $responseData = json_decode($this->_response->body());
        $this->basicToken = 'Bearer ' . $responseData->data->token;
    }
    
    /**
     * Test token method
     *
     * @return void
     */
    public function testValidToken()
    {
        $data = [
            'email' => 'tokenuser@test.com',
            'password' => 'qwe123'
        ];
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->post('/user/users/get_token', $data);
        $this->assertResponseOk();
        $this->assertResponseContains('"success": true');
        $responseData = json_decode($this->_response->body());
        $this->token['Admin'] = 'Bearer ' . $responseData->data->token;
    }

    /**
     * Test token method
     * Try to get a token with a invalid credentials
     * @return void
     */
    public function testInvalidToken()
    {
        $data = [
            'email' => 'tokenuser@test.com',
            'password' => 'qwe11'
        ];
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->post('/user/users/get_token', $data);
        $this->assertResponseError();
    }

    /**
     * Test index method
     *
     */
    public function testIndex()
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'Authorization' => $this->basicToken]
        ]);
        $this->get('/user/users');

        $this->assertResponseOk();
        
        $respondeData = json_decode($this->_response->body());
        $count = count($respondeData->users);

        $users = TableRegistry::get('User.Users');
        $query = $users->find('all')
            ->contain(['Usertypes', 'Personalinformations'])
            ->order('Users.email');
        // debug($query);
        $usersJson = json_encode(['users' => $query], JSON_PRETTY_PRINT);
        
        $this->assertEquals($count, $query->count());
        $this->assertEquals($usersJson, $this->_response->body());
    }

    /**
     * Test info method
     *
     * @return void
     */
    public function testView()
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'Authorization' => $this->basicToken]
        ]);

        $this->get('/user/users/view/1');

        // Check that the response was a 200
        $this->assertResponseOk();
        $users = TableRegistry::get('User.Users');
        $user = $users->find()
            ->where(['Users.id' => 1])
            ->contain(['Usertypes', 'Personalinformations'])
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
        $this->get('/user/users/view/1');
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
     *
     * @return void
     */
    public function testEditWithoutAddress()
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
            'headers' => ['Accept' => 'application/json', 'Authorization' => $this->basicToken]
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
        
        $userId = $users->find()->where(['emailcheckcode' => 'w4d98c4w6d5c4w9dc6wd5c46w4cd9wdc'])->first()->id;
        
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->get('/user/users/verify/?code=w4d98c4w6d5c4w9dc6wd5c46w4cd9wdc');
        $this->assertResponseSuccess();

        $user = $users->find()->where(['id' => $userId ])->first();
        $this->assertEquals('', $user->emailcheckcode);
        

        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->get('/user/users/verify/?code=w4d98c4w6d5c4w9dc6wd5c46w4cd9wdc');
        $this->assertResponseError();

        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->get('/user/users/verify/?w4d98c4w6d5c4w9dc6wd5c46w4cd9wdc');
        $this->assertResponseError();

        $userId = $users->find()->where(['emailcheckcode' => '11111111111111111111111111111111111'])->first()->id;

        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->get('/user/users/verify/?code=11111111111111111111111111111111111');
        $this->assertResponseSuccess();

        $user = $users->find()->where(['id' => $userId ])->first();
        $this->assertEquals('', $user->emailcheckcode);

    }

    // /**
    //  * Test verify method
    //  *
    //  * @return void
    //  */
    // public function testResendVerification()
    // {
    //     $users = TableRegistry::get('User.Users');
    //     $this->configRequest([
    //         'headers' => ['Accept' => 'application/json', 'Authorization' => $this->basicToken]
    //     ]);

    //     $user = $users->get(1);
    //     $oldCode = $user->emailcheckcode;
    //     $this->get('/user/users/send_verification_email');
    //     $this->assertResponseSuccess();
    //     $user = $users->get(1);
    //     $newCode = $user->emailcheckcode;

    //     $this->assertNotEquals($oldCode, $newCode, 'Os codigos de verificação de email devem ser diferente');
    //     //try to resend a email to a verified user
    // }

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
        
        $olPasswordChangeCode = $users->get(1)->passwordchangecode;
        
        $data = [
            'email' => $users->get(1)->email,
            'code' => $olPasswordChangeCode,
            'password' => "qwe123"
        ];

        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->post('/user/users/reset_password', $data);
        $this->assertResponseOk();
        $passwordChangeCode = $users->get(1)->passwordchangecode;

        $this->assertNotEquals($passwordChangeCode, $olPasswordChangeCode, 'message');
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
