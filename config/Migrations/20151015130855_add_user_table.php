<?php
use Migrations\AbstractMigration;

class AddUserTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('users');
        $table
            ->addColumn('usertype_id', 'integer', [
                'limit' => 11
            ])
            ->addColumn('personalinformation_id', 'integer', [
                'limit' => 11
            ])
            ->addColumn('avatar_path', 'text', [
               'default' => null,
               'null' => true,
            ])
            ->addColumn('password', 'string', [
               'default' => null,
               'limit' => 255,
               'null' => false,
            ])
            ->addColumn('email', 'string', [
               'default' => null,
               'limit' => 128,
               'null' => false,
            ])
            ->addColumn('emailcheckcode', 'string', [
               'default' => null,
               'limit' => 128,
               'null' => true,
            ])
            ->addColumn('passwordchangecode', 'string', [
               'default' => null,
               'limit' => 128,
               'null' => true,
            ])
            ->addColumn('expire_account', 'date', [
               'default' => null,
               'null' => true,
            ])
            ->addColumn('complete_setup', 'boolean', [
                'default' => 1,
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
                    'usertype_id',
                ]
            )
            ->addIndex(
                [
                    'personalinformation_id',
                ]
            )
            ->create();
            $table
            ->addForeignKey(
                'usertype_id',
                'usertypes',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->addForeignKey(
                'personalinformation_id',
                'personalinformations',
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
        $this->dropTable('users');
    }
}
