<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\Utility\Inflector;

/**
 * Associatable behavior
 */
class AssociatableBehavior extends Behavior
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function getValidAssociations(array $associations)
    {
        foreach ($associations as $key => $assoc) {
            $association = explode('.', $assoc);
            $model = $this->_table;
            foreach ($association as $table) {
                if (! $model->association($table)) {
                    unset($associations[$key]);
                    break;
                }

                $model = $model->$table;
            }
        }

        return $associations;
    }

    /**
     * getRequestAssociations method Extracts from the data array the relations with the User Model
     * @param array $requestData Request Data
     * @return array
     */
    public function getRequestAssociations(array $requestData)
    {
        $requestAssoc = [];
        foreach ($requestData as $key => $info) {
            $requestAssoc = array_merge(
                $requestAssoc,
                $this->_checkAssocitation($info, $key, $this->_table)
            );
        }
        return $requestAssoc;
    }

    /**
     * _checkAssociation method
     * @param array $data Candidate relation's data
     * @param string $candidate Candidate key
     * @param array $table Associated table
     * @param string $parentAssociation Name of the parent association
     * @return array
     */
    protected function _checkAssocitation($data, $candidate, $table, $parentAssociation = '')
    {
        if (! is_array($data)) {
            return [];
        }

        $associations = [];
        $pluralSec = Inflector::pluralize($candidate);

        if ($table->association($pluralSec)) {
            $association = ucwords($pluralSec);
            $assocString = ($parentAssociation != '') ? $parentAssociation . '.' . $association : $association;
            $associations[] = $assocString;

            foreach ($data as $key => $info) {
                $associations = array_merge(
                    $associations,
                    $this->_checkAssocitation($info, $key, $table->$association, $assocString)
                );
            }
        }

        return $associations;
    }
}
