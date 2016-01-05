<?php
use Migrations\AbstractMigration;

class AddUsersocialdataTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('usersocialdata');
        $table
            ->addColumn('user_id', 'integer', [
                'limit' => 11
            ])
            ->addColumn('linkedin_id', 'string', [
                'limit' => 45,
                'null' => false,
            ])
            ->addColumn('linkedin_token', 'string', [
               'default' => null,
               'limit' => 255,
               'null' => false,
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
        $this->dropTable('usersocialdata');
    }
}
