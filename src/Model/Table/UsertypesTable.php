<?php
namespace User\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use User\Model\Entity\Usertype;

/**
 * Usertypes Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Userjuridicaltypes
 * @property \Cake\ORM\Association\HasMany $Users
 */
class UsertypesTable extends Table
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

        $this->table('usertypes');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Userjuridicaltypes', [
            'foreignKey' => 'userjuridicaltype_id',
            'joinType' => 'INNER',
            'className' => 'User.Userjuridicaltypes'
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'usertype_id',
            'className' => 'User.Users'
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
            ->add('userjuridicaltype_id', 'valid', ['rule' => 'numeric'])
            ->requirePresence('userjuridicaltype_id', 'create')
            ->notEmpty('userjuridicaltype_id');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');
        $validator
            ->add('is_active', 'valid', ['rule' => 'Boolean'])
            ->notEmpty('is_active');
            
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['userjuridicaltype_id'], 'Userjuridicaltypes'));
        return $rules;
    }
}
