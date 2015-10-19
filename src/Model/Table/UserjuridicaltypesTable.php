<?php
namespace User\Model\Table;

use App\Model\Entity\Userjuridicaltype;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use User\Model\Table\AppTable;

/**
 * Userjuridicaltypes Model
 *
 * @property \Cake\ORM\Association\HasMany $Usertypes
 */
class UserjuridicaltypesTable extends AppTable
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('userjuridicaltypes');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Usertypes', [
            'className' => 'User.Usertypes',
            'foreignKey' => 'userjuridicaltype_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->add('is_active', 'valid', ['rule' => 'boolean'])
            //->requirePresence('is_active', 'create')
            ->notEmpty('is_active');

        return $validator;
    }
}
