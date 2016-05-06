<?php
namespace User\Model\Table;

use Cake\Localized\Validation\BrValidation;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use User\Model\Entity\Personalinformation;
use User\Model\Table\AppTable;

/**
 * Personalinformations Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Genders
 */
class PersonalinformationsTable extends AppTable
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

        $this->table('personalinformations');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasOne('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            'className' => 'User.Users'
        ]);
        $this->belongsTo('Genders', [
            'foreignKey' => 'gender_id',
            'joinType' => 'INNER',
            'className' => 'User.Genders'
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

        // $validator
        //     ->add('user_id', 'valid', ['rule' => 'numeric'])
        //     ->allowEmpty('user_id');

        $validator
            ->requirePresence('gender_id', 'create')
            ->notEmpty('gender_id');

        $validator
            ->requirePresence('first_name', 'create')
            ->notEmpty('first_name');

        $validator
            ->requirePresence('last_name', 'create')
            ->notEmpty('last_name');

        $validator
            ->add('birth', 'valid', ['rule' => ['date', 'dmy']])
            ->requirePresence('birth', 'create')
            ->notEmpty('birth');

        $validator
            ->requirePresence('phone1', 'create')
            ->notEmpty('phone1');

        $validator
            ->allowEmpty('phone2');

        $validator->provider('br', BrValidation::class);
        
        $validator->add('uid', 'idVerification', [
                'rule' => 'personId',
                'provider' => 'br'
            ])
            ->allowEmpty('uid');


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
        $rules->add($rules->existsIn(['gender_id'], 'Genders'));
        return $rules;
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName()
    {
        return 'oxenti_user';
    }
}
