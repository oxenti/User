<?php
namespace User\Model\Table;

use User\Model\Entity\Usermessage;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use User\Model\Table\AppTable;

/**
 * Usermessages Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsTo $Usermessages
 */
class UsermessagesTable extends AppTable
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

        $this->table('usermessages');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        
        $this->belongsTo('Sender', [
            'className' => 'User.Users',
            'foreignKey' => 'user_id',
            'propertyName' => 'Sender',
        ]);
        $this->belongsTo('Receiver', [
            'className' => 'User.Users',
            'foreignKey' => 'target_id',
            'propertyName' => 'Receiver'
        ]);
        $this->belongsTo('OriginalMessage', [
            'className' => 'User.Usermessages',
            'foreignKey' => 'original_message_id',
            'propertyName' => 'OriginalMessage'
        ]);
    }

    /**
     * After Save method
     * Setting chatcode on a usermessage.
     *
     */
    public function afterSave($event, $entity, $options)
    {
        if ($entity->isNew() && is_null($entity->chatcode)) {
            $entity->chatcode = $entity->id;
            $this->save($entity);
        }
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
            ->add('title', 'maxLength',['rule' => ['maxLength', 45]])
            ->requirePresence('title', 'create')
            ->notEmpty('title');

        $validator
            ->requirePresence('message', 'create')
            ->notEmpty('message');

        $validator
            ->add('unread', 'valid', ['rule' => 'boolean']);

        $validator
            ->add('chatcode', 'valid', ['rule' => 'numeric']);

        $validator
            ->add('is_active', 'valid', ['rule' => 'boolean']);

        $validator
            ->add('user_id', 'valid', ['rule' => 'numeric'])
            ->requirePresence('user_id', 'create')
            ->notEmpty('user_id');

        $validator
            ->add('target_id', 'valid', ['rule' => 'numeric'])
            ->requirePresence('target_id', 'create')
            ->notEmpty('target_id');

        $validator
            ->add('original_message_id', 'valid', ['rule' => 'numeric'])
            ->notEmpty('original_message_id');

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
        $rules->add($rules->existsIn(['user_id'], 'Sender'));
        $rules->add($rules->existsIn(['target_id'], 'Receiver'));
        $rules->add($rules->existsIn(['original_message_id'], 'OriginalMessage'));
        return $rules;
    }
}
