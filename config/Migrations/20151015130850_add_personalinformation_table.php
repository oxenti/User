<?php
use Migrations\AbstractMigration;

class AddPersonalinformationTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('personalinformations');
        $table
            ->addColumn('gender_id', 'integer', [
                'limit' => 11
            ])
            ->addColumn('first_name', 'string', [
               'default' => null,
               'limit' => 100,
               'null' => false,
            ])
            ->addColumn('last_name', 'string', [
               'default' => null,
               'limit' => 100,
               'null' => false,
            ])
            ->addColumn('birth', 'date', [
               'default' => null,
               'null' => false,
            ])
            ->addColumn('uid', 'string', [
               'default' => null,
               'limit' => 20,
               'null' => true,
            ])
            ->addColumn('phone1', 'string', [
               'default' => null,
               'limit' => 13,
               'null' => true,
            ])
            ->addColumn('phone2', 'string', [
               'default' => null,
               'limit' => 13,
               'null' => true,
            ])
            ->addColumn('is_active', 'boolean', [
                'default' => 1,
            ])
            ->addColumn('created', 'datetime', [
               'default' => 'CURRENT_TIMESTAMP',
               'limit' => null,
               'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
               'default' => null,
               'limit' => null,
               'null' => true,
            ])
            ->addIndex(
                [
                    'gender_id',
                ]
            )
            ->create();
            $table
            ->addForeignKey(
                'gender_id',
                'genders',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('personalinformations');
    }
}
