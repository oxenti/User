<?php
namespace User\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UserjuridicaltypesFixture
 *
 */
class UserjuridicaltypesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'name' => ['type' => 'string', 'length' => 45, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'is_active' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
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
            'name' => 'Pessoa Fisica',
            'created' => '2015-09-15 14:22:29',
            'modified' => '2015-09-15 14:22:29',
            'is_active' => 1
        ],
        [
            'name' => 'Pessoa Juridica',
            'created' => '2015-09-15 14:22:29',
            'modified' => '2015-09-15 14:22:29',
            'is_active' => 1
        ],
    ];
}
