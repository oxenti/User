<?php
namespace User\Test\Fixture;

use User\Test\Fixture\AppFixture;

/**
 * PersonalinformationsFixture
 *
 */
class PersonalinformationsFixture extends AppFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'first_name' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'last_name' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'birth' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'gender_id' => ['type' => 'integer', 'length' => 11, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'phone1' => ['type' => 'string', 'length' => 13, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'phone2' => ['type' => 'string', 'length' => 13, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'is_active' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => 'Disable/enable account', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
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
            'first_name' => 'Lorem ipsum dolor sit amet',
            'last_name' => 'Lorem ipsum dolor sit amet',
            'birth' => '2015-10-07',
            'gender_id' => 1,
            'phone1' => 'Lorem ipsum',
            'phone2' => 'Lorem ipsum',
            'is_active' => 1,
            'created' => '2015-10-07 02:42:20',
            'modified' => '2015-10-07 02:42:20'
        ],
    ];
}
