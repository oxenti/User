<?php
use Migrations\AbstractMigration;

class AddUsersaddressTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('addresses_users');
        $table
            ->addColumn('user_id', 'integer', [
                'limit' => 11
            ])
            ->addColumn('address_id', 'integer', [
                'limit' => 11
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
            ->addIndex(
                [
                    'address_id',
                ]
            )
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('addresses_users');
    }
}
