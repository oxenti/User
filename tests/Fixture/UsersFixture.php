<?php
namespace User\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 *
 */
class UsersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'usertype_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'gender_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'first_name' => ['type' => 'string', 'length' => 45, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'last_name' => ['type' => 'string', 'length' => 45, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'birth' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'avatar_path' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'phone1' => ['type' => 'string', 'length' => 45, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'phone2' => ['type' => 'string', 'length' => 45, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'login' => ['type' => 'string', 'length' => 32, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'password' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'email' => ['type' => 'string', 'length' => 128, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'emailcheckcode' => ['type' => 'string', 'length' => 128, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'passwordchangecode' => ['type' => 'string', 'length' => 128, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'is_active' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => 'Disable/enable account', 'precision' => null],
        'expire_account' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_users_usertypes1_idx' => ['type' => 'index', 'columns' => ['usertype_id'], 'length' => []],
            'fk_users_genders1_idx' => ['type' => 'index', 'columns' => ['gender_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_users_usertypes1' => ['type' => 'foreign', 'columns' => ['usertype_id'], 'references' => ['usertypes', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'usertype_id' => 100,
            'gender_id' => 1,
            'first_name' => 'Lorem ipsum dolor sit amet',
            'last_name' => 'Lorem ipsum dolor sit amet',
            'birth' => '2015-09-14',
            'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'phone1' => 'Lorem ipsum dolor sit amet',
            'phone2' => 'Lorem ipsum dolor sit amet',
            'login' => 'Lorem ipsum dolor sit amet',
            'password' => '$2y$10$W8cHelHWOsN/uOoJlexrv.gMQiJ8LBq4hE8CAPI6.qYJpQQfn6i9u',
            'email' => 'root@root.com',
            'emailcheckcode' => 'Lorem ipsum dolor sit amet',
            'passwordchangecode' => 'Lorem ipsum dolor sit amet',
            'is_active' => 1,
        ],
        [
            'id' => 2,
            'usertype_id' => 2,
            'gender_id' => 1,
            'first_name' => 'Instituição',
            'last_name' => 'primeira',
            'birth' => '2015-09-14',
            'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'phone1' => 'Lorem ipsum dolor sit amet',
            'phone2' => 'Lorem ipsum dolor sit amet',
            'login' => '',
            'password' => '$2y$10$W8cHelHWOsN/uOoJlexrv.gMQiJ8LBq4hE8CAPI6.qYJpQQfn6i9u',
            'email' => 'institution@institution.com',
            'emailcheckcode' => '11111111111111111111111111111111111',
            'passwordchangecode' => 'Lorem ipsum dolor sit amet',
            'is_active' => 1,
        ],
        [
            'id' => 3,
            'usertype_id' => 1,
            'gender_id' => 1,
            'first_name' => 'Alunos',
            'last_name' => 'primeiro',
            'birth' => '2015-09-14',
            'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'phone1' => 'Lorem ipsum dolor sit amet',
            'phone2' => 'Lorem ipsum dolor sit amet',
            'login' => '',
            'password' => '$2y$10$W8cHelHWOsN/uOoJlexrv.gMQiJ8LBq4hE8CAPI6.qYJpQQfn6i9u',
            'email' => 'aluno@aluno.com',
            'emailcheckcode' => '11111111111111111111111111111111111',
            'passwordchangecode' => 'Lorem ipsum dolor sit amet',
            'is_active' => 1,
        ],
        [
            'id' => 4,
            'usertype_id' => 1,
            'gender_id' => 1,
            'first_name' => 'Aluno',
            'last_name' => 'Segundo',
            'birth' => '2015-09-14',
            'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'phone1' => 'Lorem ipsum dolor sit amet',
            'phone2' => 'Lorem ipsum dolor sit amet',
            'login' => '',
            'password' => '$2y$10$W8cHelHWOsN/uOoJlexrv.gMQiJ8LBq4hE8CAPI6.qYJpQQfn6i9u',
            'email' => 'aluno2@aluno.com',
            'emailcheckcode' => '11111111111111111111111111111111111',
            'passwordchangecode' => 'Lorem ipsum dolor sit amet',
            'is_active' => 1,
        ],
        [
            'id' => 5,
            'usertype_id' => 3,
            'gender_id' => 1,
            'first_name' => 'Professor',
            'last_name' => 'primeiro',
            'birth' => '2015-09-14',
            'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'phone1' => 'Lorem ipsum dolor sit amet',
            'phone2' => 'Lorem ipsum dolor sit amet',
            'login' => '',
            'password' => '$2y$10$W8cHelHWOsN/uOoJlexrv.gMQiJ8LBq4hE8CAPI6.qYJpQQfn6i9u',
            'email' => 'professor@professor.com',
            'emailcheckcode' => '11111111111111111111111111111111111',
            'passwordchangecode' => 'Lorem ipsum dolor sit amet',
            'is_active' => 1,
        ],
        [
            'id' => 6,
            'usertype_id' => 3,
            'gender_id' => 1,
            'first_name' => 'Professor',
            'last_name' => 'Segundo',
            'birth' => '2015-09-14',
            'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'phone1' => 'Lorem ipsum dolor sit amet',
            'phone2' => 'Lorem ipsum dolor sit amet',
            'login' => '',
            'password' => '$2y$10$W8cHelHWOsN/uOoJlexrv.gMQiJ8LBq4hE8CAPI6.qYJpQQfn6i9u',
            'email' => 'professor2@professor.com',
            'emailcheckcode' => '11111111111111111111111111111111111',
            'passwordchangecode' => 'Lorem ipsum dolor sit amet',
            'is_active' => 1,
        ],
        [
            'id' => 7,
            'usertype_id' => 2,
            'gender_id' => 1,
            'first_name' => 'Ins',
            'last_name' => 'Segundo',
            'birth' => '2015-09-14',
            'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'phone1' => 'Lorem ipsum dolor sit amet',
            'phone2' => 'Lorem ipsum dolor sit amet',
            'login' => '',
            'password' => '$2y$10$W8cHelHWOsN/uOoJlexrv.gMQiJ8LBq4hE8CAPI6.qYJpQQfn6i9u',
            'email' => 'institution2@institution.com',
            'emailcheckcode' => '11111111111111111111111111111111111',
            'passwordchangecode' => 'Lorem ipsum dolor sit amet',
            'is_active' => 1,
        ],
    ];
}
