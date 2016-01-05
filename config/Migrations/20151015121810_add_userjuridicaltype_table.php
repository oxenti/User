<?php
use Migrations\AbstractMigration;

class AddUserjuridicaltypeTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('userjuridicaltypes');
        $table
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
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('userjuridicaltypes');
    }
}
