<?php
namespace User\Model\Table;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use User\Model\Entity\User;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Usertypes
 * @property \Cake\ORM\Association\BelongsTo $Genders
 * @property \Cake\ORM\Association\HasMany $Addresses
 * @property \Cake\ORM\Association\HasMany $Institutions
 * @property \Cake\ORM\Association\HasMany $Resources
 * @property \Cake\ORM\Association\HasMany $Students
 * @property \Cake\ORM\Association\HasMany $Teachers
 * @property \Cake\ORM\Association\HasMany $Tutors
 * @property \Cake\ORM\Association\HasMany $Usermessages
 * @property \Cake\ORM\Association\HasMany $Usersocialdata
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        Configure::load('User.user_relations');

        parent::initialize($config);

        $this->table('users');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Usertypes', [
            'foreignKey' => 'usertype_id',
            'joinType' => 'INNER',
            'className' => 'User.Usertypes'
        ]);
        $this->belongsTo('Genders', [
            'foreignKey' => 'gender_id',
            'className' => 'User.Genders'
        ]);
        $this->hasMany('Usermessages', [
            'foreignKey' => 'user_id',
            'className' => 'User.Usermessages'
        ]);

        foreach (Configure::read('relations') as $type => $relations) {
            foreach ($relations as $relationName => $relationProprities) {
                $this->$type($relationName, $relationProprities);
            }
        }

        // $this->hasMany('Addresses', [
        //     'foreignKey' => 'user_id',
        //     'className' => 'User.Addresses'
        // ]);
        // $this->hasMany('Usersocialdata', [
        //     'foreignKey' => 'user_id',
        //     'className' => 'User.Usersocialdata'
        // ]);
        // $this->hasMany('Institutions', [
        //     'foreignKey' => 'user_id',
        //     'className' => 'User.Institutions'
        // ]);
        // $this->hasMany('Resources', [
        //     'foreignKey' => 'user_id',
        //     'className' => 'User.Resources'
        // ]);
        // $this->hasMany('Students', [
        //     'foreignKey' => 'user_id',
        //     'className' => 'User.Students'
        // ]);
        // $this->hasMany('Teachers', [
        //     'foreignKey' => 'user_id',
        //     'className' => 'User.Teachers'
        // ]);
        // $this->hasMany('Tutors', [
        //     'foreignKey' => 'user_id',
        //     'className' => 'User.Tutors'
        // ]);
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
           ->add('first_name', 'maxLength', [
                'rule' => ['maxLength', 45]
            ])
            ->requirePresence('first_name', 'create')
            ->notEmpty('first_name');

        $validator
            ->add('last_name', 'maxLength', [
                'rule' => ['maxLength', 45]
            ])
            ->requirePresence('last_name', 'create')
            ->notEmpty('last_name');

        $validator
            ->add('birth', 'valid', ['rule' => 'date'])
            ->allowEmpty('birth');

        $validator
            ->allowEmpty('avatar_path');

        $validator
            ->add('phone1', [
                'valid' => [
                    'rule' => 'numeric',
                    'last' => true
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 14]
                ]
            ])
            ->allowEmpty('phone1');
            

        $validator
            ->add('phone2', [
                'valid' => [
                    'rule' => 'numeric',
                    'last' => true
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 14]
                ]
            ])
            ->allowEmpty('phone2');
            

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->add('email', [
                'valid' => [
                    'rule' => 'email',
                    'last' => true
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 128]
                ]
            ])
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->allowEmpty('emailcheckcode');

        $validator
            ->allowEmpty('passwordchangecode');

        $validator
            ->add('is_active', 'valid', ['rule' => 'boolean']);

        $validator
            ->add('expire_account', 'valid', ['rule' => 'date'])
            ->allowEmpty('expire_account');

        $validator
            ->add('usertype_id', 'valid', ['rule' => 'numeric'])
            ->requirePresence('usertype_id', 'create')
            ->notEmpty('usertype_id');

        $validator
            ->add('gender_id', 'valid', ['rule' => 'numeric'])
            ->requirePresence('gender_id', 'create')
            ->notEmpty('gender_id');
            
        return $validator;
    }

    /**
     * generate emailcheckcode before save entity
     */
    public function beforeSave($event, $entity, $options)
    {
        if ($entity->isNew()) {
            $entity->emailcheckcode = md5(time() * rand());
        }
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
        // $rules->add($rules->isUnique(['login']));
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['usertype_id'], 'Usertypes'));
        $rules->add($rules->existsIn(['gender_id'], 'Genders'));
        return $rules;
    }

    /**
     * sendEmail method
     *
     * @param User $user the User info.
     * @return bool
     */
    public function sendVerificationEmail($user)
    {
        $email = new Email('default');
        $code = $user->emailcheckcode;

        $email->from(['me@example.com' => 'Acadios'])
            ->emailFormat('html')
            ->template('register', 'default')
            ->viewVars(['code' => $code])
            ->to($user->email)
            ->subject('Acadios registration');

        if ($email->send()) {
            return true;
        }
        return false;
    }

    /**
     * Checks the token for a password change
     *
     * @param string $passwordchangecode code to confirm password change
     * @return mixed False or user entity
     */
    public function checkPasswordToken($passwordChangeCode = null)
    {
        $user = $this->find()
        ->where(['Users.passwordchangecode' => $passwordChangeCode])
        ->first();
        if (empty($user)) {
            return false;
        }
        return $user;
    }
    /**
     *
     */
    public function hash($value)
    {
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($value);
    }

    /**
     * Resets the password
     *
     * @param user $$user user entity
     * @param array $passwordData Post data from controller
     * @return boolean True on success
     */
    public function resetPassword($user, $passwordData)
    {
        if ($passwordData['new_password'] === $passwordData['confirm_password']) {
            $user->password = $this->hash($passwordData['new_password']);
            $user->passwordchangecode = false;
            if ($this->save($user)) {
                return true;
            }
        }
        return false;
    }


    /**
     * Checks if an email is in the system, validated and if the user is active so that the user is allowed to reste his password
     *
     * @param array $postData post data from controller
     * @return mixed False or user data as array on success
     */
    public function passwordResetCode($postData = [])
    {
        $user = $this->find()
            ->where(['Users.email' => $postData['email']])
            ->first();
        if (!empty($user)) {
            $user->passwordchangecode = md5(time() * rand());
            $user = $this->save($user);
            return $user;
        } else {
            $this->invalidate('email', __d('users', 'This Email Address does not exist in the system.'));
        }
        return false;
    }
}
