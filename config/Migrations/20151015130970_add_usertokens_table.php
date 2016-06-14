<?php
use Migrations\AbstractMigration;

class AddUsertokensTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('usertokens');
        $table
            ->addColumn('user_id', 'integer', [
                'limit' => 11
            ])
            ->addColumn('access_token', 'string', [
               'default' => null,
               'limit' => 256,
               'null' => false,
            ])
            ->addColumn('refresh_token', 'string', [
               'default' => null,
               'limit' => 256,
               'null' => false,
            ])
            ->addColumn('user_agent', 'string', [
               'default' => null,
               'limit' => 300,
               'null' => false,
            ])
            ->addColumn('refresh_expires_in', 'datetime', [
               'default' => null,
               'limit' => null,
               'null' => false,
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
                    'user_id',
                ]
            )
            ->create();
            $table
            ->addForeignKey(
                'user_id',
                'users',
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
        $this->dropTable('addresses_users');
    }
}
