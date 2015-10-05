<?php
namespace User\Test\TestCase\Controller;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use User\Controller\UsermessagesController;

/**
 * App\Controller\UsermessagesController Test Case
 */
class UsermessagesControllerTest extends IntegrationTestCase
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
        $respondeData = json_decode($this->_response->body());
        $token['Admin'] = 'Bearer ' . $respondeData->data->token;
        return [[$token]];
    }

    /**
     * Test info method
     *
     * @return void
     */
    public function testUnauthorized()
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $result = $this->get('/user/usermessages/view/1');
        $this->assertResponseError();
        $result = $this->get('/user/usermessages');
        $this->assertResponseError();
        $result = $this->get('/user/users/123/usermessages/view/1');
        $this->assertResponseError();
        $result = $this->get('/user/users/123/usermessages');
        $this->assertResponseError();

        $data = [
            'user_id' => 1,
            'target_id' => 1,
            'title' => 'Titulo Unico para testar se ADD funfou',
            'message' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'unread' => 1,
        ];
        $this->configRequest([
            'headers' => ['Accept' => 'application/json']
        ]);
        $result = $this->post('/user/usermessages', $data);
        $this->assertResponseError();

        $result = $this->put('/user/usermessages/', $data);
        $this->assertResponseError();
    }

    /**
     * Test index method
     * @dataProvider tokenProvider
     * @return void
     */
    public function testIndex($token)
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'Authorization' => $token['Admin']]
        ]);
        $this->get('/user/usermessages');
        $this->assertResponseOk();

        $usermessages = TableRegistry::get('User.Usermessages');
        $usermessagesJson = json_encode(['usermessages' => $usermessages->find()->contain(['Sender', 'Receiver'])
            ->select(['id', 'title', 'message', 'original_message_id', 'chatcode', 'unread',
                'Sender.id', 'Sender.first_name', 'Sender.last_name', 'Receiver.id', 'Receiver.first_name', 'Receiver.last_name'])
            ->orWhere(['Sender.id' => 1])
            ->formatResults(function (\Cake\Datasource\ResultSetInterface $results) {
                return $results->map(function ($row) {
                    $row['Sender']['full_name'] = $row['Sender']['first_name'] . ' ' . $row['Sender']['last_name'];
                    $row['Receiver']['full_name'] = $row['Receiver']['first_name'] . ' ' . $row['Receiver']['last_name'];
                    return $row;
                });
            })
            ->orwhere([ 'Receiver.id' => 1])], JSON_PRETTY_PRINT);
        $this->assertEquals($usermessagesJson, $this->_response->body());
    }

    /**
     * Test index method
     * @dataProvider tokenProvider
     * @return void
     */
    public function testAllMessagesFromUser($token)
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'Authorization' => $token['Admin']]
        ]);
        $result = $this->get('/user/users/1/usermessages');
        $this->assertResponseOk();

        $usermessages = TableRegistry::get('User.Usermessages');
        $usermessagesJson = json_encode(['usermessages' => $usermessages->find()->contain(['Sender', 'Receiver'])
            ->select(['id', 'title', 'message', 'original_message_id', 'chatcode', 'unread',
                'Sender.id', 'Sender.first_name', 'Sender.last_name', 'Receiver.id', 'Receiver.first_name', 'Receiver.last_name'])
            ->orWhere(['Sender.id' => 1])
            ->formatResults(function (\Cake\Datasource\ResultSetInterface $results) {
                return $results->map(function ($row) {
                    $row['Sender']['full_name'] = $row['Sender']['first_name'] . ' ' . $row['Sender']['last_name'];
                    $row['Receiver']['full_name'] = $row['Receiver']['first_name'] . ' ' . $row['Receiver']['last_name'];
                    return $row;
                });
            })
            ->orwhere([ 'Receiver.id' => 1])], JSON_PRETTY_PRINT);
        $this->assertEquals($usermessagesJson, $this->_response->body());

        $result = $this->get('/user/users/666/usermessages');
        $this->assertResponseError();
    }


    /**
     * Test view method
     * @dataProvider tokenProvider
     * @return void
     */
    public function testView($token)
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'Authorization' => $token['Admin']]
        ]);
        $result = $this->get('/user/usermessages/1');

        // Check that the response was a 200
        $this->assertResponseOk();

        $usermessages = TableRegistry::get('User.Usermessages');
        $usermessageJson = json_encode([ 'usermessage' => $usermessages->find()->contain(['Sender', 'Receiver'])
            ->select(['id', 'title', 'message', 'original_message_id', 'chatcode', 'unread',
                'Sender.id', 'Sender.first_name', 'Sender.last_name', 'Receiver.id', 'Receiver.first_name', 'Receiver.last_name'])
            ->where(['Usermessages.id' => 1])
            ->formatResults(function (\Cake\Datasource\ResultSetInterface $results) {
                return $results->map(function ($row) {
                    $row['Sender']['full_name'] = $row['Sender']['first_name'] . ' ' . $row['Sender']['last_name'];
                    $row['Receiver']['full_name'] = $row['Receiver']['first_name'] . ' ' . $row['Receiver']['last_name'];
                    return $row;
                });
            })->first()], JSON_PRETTY_PRINT);
        $this->assertEquals($usermessageJson, $this->_response->body());

        $result = $this->get('/user/usermessages/32');
        $this->assertResponseError();
    }

    /**
     * Test view method
     * @dataProvider tokenProvider
     * @return void
     */
    public function testViewFromUser($token)
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'Authorization' => $token['Admin']]
        ]);
        $result = $this->get('/user/users/1/usermessages/1');

        // Check that the response was a 200
        $this->assertResponseOk();

        $usermessages = TableRegistry::get('User.Usermessages');
        $usermessageJson = json_encode([ 'usermessage' => $usermessages->find()->contain(['Sender', 'Receiver'])
            ->select(['id', 'title', 'message', 'original_message_id', 'chatcode', 'unread',
                'Sender.id', 'Sender.first_name', 'Sender.last_name', 'Receiver.id', 'Receiver.first_name', 'Receiver.last_name'])
            ->where(['Usermessages.id' => 1])
            ->formatResults(function (\Cake\Datasource\ResultSetInterface $results) {
                return $results->map(function ($row) {
                    $row['Sender']['full_name'] = $row['Sender']['first_name'] . ' ' . $row['Sender']['last_name'];
                    $row['Receiver']['full_name'] = $row['Receiver']['first_name'] . ' ' . $row['Receiver']['last_name'];
                    return $row;
                });
            })->first()], JSON_PRETTY_PRINT);
        $this->assertEquals($usermessageJson, $this->_response->body());

        $result = $this->get('/user/users/666/usermessages/1');
        $this->assertResponseError();
    }

    /**
     * Test add method
     * @dataProvider tokenProvider
     * @return void
     */
    public function testAdd($token)
    {
        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'Authorization' => $token['Admin']]
        ]);
        $data = [
            'user_id' => 1,
            'target_id' => 1,
            'title' => 'Titulo Unico para testar se ADD funfou',
            'message' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'unread' => 1,
        ];
        $result = $this->post('/user/usermessages', $data);
        $this->assertResponseOk();

        $usermessages = TableRegistry::get('User.Usermessages');
        $query = $usermessages->find()->where(['title' => $data['title']]);
        $this->assertEquals(1, $query->count());

        $data = [
            'target_id' => 1,
            'title' => 'Titulo Unico para testar se ADD funfou',
            'message' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'unread' => 1,
        ];

        $this->configRequest([
            'headers' => ['Accept' => 'application/json', 'Authorization' => $token['Admin']]
        ]);
        $result = $this->post('/user/users/1/usermessages', $data);
        $this->assertResponseOk();

        $usermessages = TableRegistry::get('User.Usermessages');
        $query = $usermessages->find()->where(['title' => $data['title']])->first();
        $this->assertEquals(1, $query->user_id);
    }
}
