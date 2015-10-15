<?php
namespace User\Test\Fixture;

use User\Test\Fixture\AppFixture;

/**
 * UsertypesFixture
 *
 */
class UsertypesFixture extends AppFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'userjuridicaltype_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'name' => ['type' => 'string', 'length' => 45, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'fk_usertypes_userjuridicaltypes1_idx' => ['type' => 'index', 'columns' => ['userjuridicaltype_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_usertypes_userjuridicaltypes1' => ['type' => 'foreign', 'columns' => ['userjuridicaltype_id'], 'references' => ['userjuridicaltypes', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
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
            'userjuridicaltype_id' => 1,
            'name' => 'Aluno',
            'created' => '2015-09-15 14:22:14',
            'modified' => '2015-09-15 14:22:14'
        ],
        [
            'id' => 2,
            'userjuridicaltype_id' => 2,
            'name' => 'Instituicao',
            'created' => '2015-09-15 14:22:14',
            'modified' => '2015-09-15 14:22:14'
        ],
        [
            'id' => 3,
            'userjuridicaltype_id' => 1,
            'name' => 'Professor',
            'created' => '2015-09-15 14:22:14',
            'modified' => '2015-09-15 14:22:14'
        ],
        [
            'id' => 4,
            'userjuridicaltype_id' => 1,
            'name' => 'Tutor',
            'created' => '2015-09-15 14:22:14',
            'modified' => '2015-09-15 14:22:14'
        ],
        [
            'id' => 100,
            'userjuridicaltype_id' => 2,
            'name' => 'Administrador',
            'created' => '2015-09-15 14:22:14',
            'modified' => '2015-09-15 14:22:14'
        ]
    ];
}
