<?php
use Migrations\AbstractMigration;

class AddUsertypeTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('usertypes');
        $table
            ->addColumn('userjuridicaltype_id', 'integer', [
                'limit' => 11
            ])
            ->addColumn('name', 'string', [
               'default' => null,
               'limit' => 45,
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
                    'userjuridicaltype_id',
                ]
            )
            ->create();
            $table
            ->addForeignKey(
                'userjuridicaltype_id',
                'userjuridicaltypes',
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
        $this->dropTable('usertypes');
    }
}
