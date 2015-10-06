<?php
namespace User\Model\Table;

use Cake\ORM\Table;
use SoftDelete\Model\Table\SoftDeleteTrait;

/**
 * App Table class
 */
class AppTable extends Table
{

    // use SoftDeleteTrait;

    /**
     * Set the plugin's custom database connection
     *
     */
    public static function defaultConnectionName()
    {
        return 'oxenti_user';
    }
}
