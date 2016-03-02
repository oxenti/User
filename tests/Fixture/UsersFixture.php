<?php
namespace User\Test\Fixture;

use User\Test\Fixture\AppFixture;

/**
 * UsersFixture
 *
 */
class UsersFixture extends AppFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'usertype_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'personalinformation_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'avatar_path' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
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
            'personalinformation_id' => 1,
            'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'password' => '$2y$10$PFZqzrrtAon2wDUcCvctRu4VtRpnqWqmQotmLG6s880G.lR7PFCwe',
            'email' => 'root@root.com',
            'emailcheckcode' => '',
            'passwordchangecode' => 'f49513f2ad336f93149f8ace1f93c079',
            'is_active' => 1,
        ],
        [
            'id' => 2,
            'usertype_id' => 2,
            'personalinformation_id' => 1,
            'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'password' => '$2y$10$PFZqzrrtAon2wDUcCvctRu4VtRpnqWqmQotmLG6s880G.lR7PFCwe',
            'email' => 'institution@institution.com',
            'emailcheckcode' => '',
            'passwordchangecode' => '321s3s5d4s3d4sc321sc6as',
            'is_active' => 1,
        ],
        [
            'id' => 3,
            'usertype_id' => 1,
            'personalinformation_id' => 1,
            'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'password' => '$2y$10$PFZqzrrtAon2wDUcCvctRu4VtRpnqWqmQotmLG6s880G.lR7PFCwe',
            'email' => 'aluno@aluno.com',
            'emailcheckcode' => '321s3s5d4s3d4sc321sc6as',
            'passwordchangecode' => '',
            'is_active' => 1,
        ],
        [
            'id' => 4,
            'usertype_id' => 1,
            'personalinformation_id' => 1,
            'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'password' => '$2y$10$PFZqzrrtAon2wDUcCvctRu4VtRpnqWqmQotmLG6s880G.lR7PFCwe',
            'email' => 'alunox@aluno.com',
            'emailcheckcode' => '11111111111111111111111111111111111',
            'passwordchangecode' => 'Lorem ipsum dolor sit amet',
            'is_active' => 1,
        ],
        [
            'id' => 5,
            'usertype_id' => 3,
            'personalinformation_id' => 1,
            'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'password' => '$2y$10$PFZqzrrtAon2wDUcCvctRu4VtRpnqWqmQotmLG6s880G.lR7PFCwe',
            'email' => 'professor@professor.com',
            'emailcheckcode' => '11111111111111111111111111111111111',
            'passwordchangecode' => 'Lorem ipsum dolor sit amet',
            'is_active' => 1,
        ],
        [
            'id' => 6,
            'usertype_id' => 3,
            'personalinformation_id' => 1,
            'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'password' => '$2y$10$PFZqzrrtAon2wDUcCvctRu4VtRpnqWqmQotmLG6s880G.lR7PFCwe',
            'email' => 'professorX@professor.com',
            'emailcheckcode' => 'w4d98c4w6d5c4w9dc6wd5c46w4cd9wdc',
            'passwordchangecode' => 'Lorem ipsum dolor sit amet',
            'is_active' => 1,
        ],
        [
            'id' => 7,
            'usertype_id' => 2,
            'personalinformation_id' => 1,
            'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'password' => '$2y$10$PFZqzrrtAon2wDUcCvctRu4VtRpnqWqmQotmLG6s880G.lR7PFCwe',
            'email' => 'institution2@institution.com',
            'emailcheckcode' => 'wdc3w2dc13wd54cw3d2vb1w6d5w6bd5w4d6',
            'passwordchangecode' => 'Lorem ipsum dolor sit amet',
            'is_active' => 1,
        ],
        [
            'id' => 8,
            'usertype_id' => 4,
            'personalinformation_id' => 1,
            'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'password' => '$2y$10$W8cHelHWOsN/uOoJlexrv.gMQiJ8LBq4hE8CAPI6.qYJpQQfn6i9u',
            'email' => 'tutor@tutor.com',
            'emailcheckcode' => '11111111111111111111111111111111111',
            'passwordchangecode' => 'Lorem ipsum dolor sit amet',
            'is_active' => 1,
        ],
        [
            'id' => 9,
            'usertype_id' => 4,
            'personalinformation_id' => 1,
            'avatar_path' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'password' => '$2y$10$W8cHelHWOsN/uOoJlexrv.gMQiJ8LBq4hE8CAPI6.qYJpQQfn6i9u',
            'email' => 'tutor2@tutor.com',
            'emailcheckcode' => '11111111111111111111111111111111111',
            'passwordchangecode' => 'Lorem ipsum dolor sit amet',
            'is_active' => 1,
        ],
    ];
}
