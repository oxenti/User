<?php
namespace User\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use User\Model\Table\PersonalinformationsTable;

/**
 * oxenti\User\Model\Table\PersonalinformationsTable Test Case
 */
class PersonalinformationsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.user.personalinformations',
        'plugin.user.users',
        'plugin.user.genders'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('User.Personalinformations') ? [] : ['className' => 'User\Model\Table\PersonalinformationsTable'];
        $this->Personalinformations = TableRegistry::get('User.Personalinformations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Personalinformations);

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
                'gender_id' => 1,
                'first_name' => 'Lorem ipsum dolor sit amet',
                'last_name' => 'Lorem ipsum dolor sit amet',
                'birth' => '2015-09-14',
                'phone1' => '554554454545',
                'phone2' => '665565656565',
                'created' => '2015-09-14 19:57:29',
                'modified' => '2015-09-14 19:57:29'
            ],
            [
                //Invalid data
                'user_id' => '',
                'gender_id' => '',
                'first_name' => '',
                'last_name' => '',
                'birth' => '',
                'phone1' => '',
                'phone2' => '',
                'created' => '',
                'modified' => ''
            ],
            [
                // duplicated user
                'user_id' => 78,
                'gender_id' => 1,
                'first_name' => 'Lorem ipsum dolor sit amet',
                'last_name' => 'Lorem ipsum dolor sit amet',
                'birth' => '2015-09-14',
                'phone1' => '554554454545',
                'phone2' => '665565656565',
                'created' => '2015-09-14 19:57:29',
                'modified' => '2015-09-14 19:57:29'
            ],
            [
                // invalid gender
                'user_id' => 2,
                'gender_id' => 5,
                'first_name' => 'Lorem ipsum dolor sit amet',
                'last_name' => 'Lorem ipsum dolor sit amet',
                'birth' => '2015-09-14',
                'phone1' => '554554454545',
                'phone2' => '665565656565',
                'created' => '2015-09-14 19:57:29',
                'modified' => '2015-09-14 19:57:29'
            ],
            [
                // invalid phone
                'user_id' => 3,
                'gender_id' => 2,
                'first_name' => 'Lorem ipsum dolor sit amet',
                'last_name' => 'Lorem ipsum dolor sit amet',
                'birth' => '2015-09-14',
                'phone1' => '',
                'phone2' => '665565656565',
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
        $this->assertNotEmpty($this->Personalinformations);
        $this->assertNotEmpty($this->Personalinformations->Associations(), "No Associations found.");
        $this->assertNotEmpty($this->Personalinformations->Users, "Users Association not found.");
        $this->assertNotEmpty($this->Personalinformations->Genders, "Genders Association not found.");
        $this->assertNotEmpty($this->Personalinformations->Behaviors());
    }

    /**
     * Test validationDefault method
     * @dataProvider additionProvider
     * @return void
     */
    public function testValidationDefault($cases)
    {
        $errors = $this->Personalinformations->validator()->errors($cases[0]);
        $this->assertEmpty(
            $errors,
            'Errors found on basic insert: ' . json_encode($errors)
        );

        $errors = $this->Personalinformations->validator()->errors($cases[1]);
        // debug($cases[1]);
        $this->assertNotEmpty($errors['gender_id'], 'gender_id notEmpty validation not working');
        $this->assertNotEmpty($errors['first_name'], 'first_name notEmpty validation not working');
        $this->assertNotEmpty($errors['last_name'], 'last_name notEmpty validation not working');
        $this->assertNotEmpty($errors['phone1'], 'phone1 notEmpty validation not working');

        $errors = $this->Personalinformations->validator()->errors($cases[4]);
        $expected = ['_empty' => 'This field cannot be left empty'];
        $this->assertEquals($expected, $errors['phone1'], 'Not empty phone1 validation not working');
    }

    /**
     * Test buildRules method
     * @dataProvider additionProvider
     * @param array $cases
     * @return void
     */
    public function testBuildRules($cases)
    {
        $personalInfo = $this->Personalinformations->newEntity($cases[0]);
        $this->Personalinformations->save($personalInfo);
        $errors = $personalInfo->errors();
        // debug($cases[0]);
        $this->assertNotEmpty($errors['user_id']['_isUnique'], 'IsUnique rule for User_id is not working');

        $personalInfo = $this->Personalinformations->newEntity($cases[2]);
        $this->Personalinformations->save($personalInfo);
        $errors = $personalInfo->errors();
        $this->assertNotEmpty($errors['user_id']['_existsIn'], 'ExistsIn User_id validation not working');

        $personalInfo = $this->Personalinformations->newEntity($cases[3]);
        $this->Personalinformations->save($personalInfo);
        $errors = $personalInfo->errors();
        $this->assertNotEmpty($errors['gender_id']['_existsIn'], 'ExistsIn Genders validation not working');
    }
}
