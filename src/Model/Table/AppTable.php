<?php
namespace User\Model\Table;

use App\Model\Table\AppTable as BaseAppTable;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use SoftDelete\Model\Table\SoftDeleteTrait;

/**
 * App Table class
 */
class AppTable extends BaseAppTable
{

    use SoftDeleteTrait;

    /**
     * Set the plugin's custom database connection
     *
     */
    public static function defaultConnectionName()
    {
        return 'oxenti_user';
    }

    /**
     * _setAppRelations method  sets the all the relations outside the plugin
     * @param array $config Array with the relation data
     */
    protected function _setAppRelations($config)
    {
        foreach ($config as $relationType => $relations) {
            if (! empty($relations)) {
                // $setupMethod = '_set' . $relationType;
                // $this->$setupMethod($relations);
                foreach ($relations as $name => $data) {
                    $this->$relationType($name, $data);
                }
            }
        }
    }

    /**
     * _setExtraBuildRules method  sets the all the rules to relations outside the plugin
     * @param RulesChecker $rules Table rules
     * @param array $config Array with the relation data
     */
    protected function _setExtraBuildRules($rules, $config)
    {
        foreach ($config as $ruleName => $data) {
            if (isset($data['tableName'])) {
                $rules->add($rules->$ruleName($data['keys'], $data['tableName']));
            } else {
                $rules->add($rules->$ruleName($data['keys'], $data['tableName']));
            }
        }
        return $rules;
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
                $this->_checkAssocitation($info, $key, $this)
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
