<?php
namespace User\Model\Table;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\Network\Email\Email;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use User\Model\Entity\User;
use User\Model\Table\AppTable;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\BelongsTo    $Usertypes
 * @property \Cake\ORM\Association\HasOne       $Personalinformations
 * @property \Cake\ORM\Association\HasOne       $Usersocialdata
 */
class UsersTable extends AppTable
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

        $this->table('users');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Utils.Uploadable', [
            'avatar_path' => [
                'field' => 'avatar_path',
                'path' => '{ROOT}{DS}{WEBROOT}{DS}uploads{DS}{model}{DS}avatar{DS}{primaryKey}{DS}',
                'fileName' => '{primaryKey}_avatar.{extension}',
                'entityReplacements' => [
                    '{primaryKey}' => 'id',
                ]
            ],
        ]);

        $this->belongsTo('Usertypes', [
            'foreignKey' => 'usertype_id',
            'joinType' => 'INNER',
            'className' => 'User.Usertypes'
        ]);

        $this->hasOne('Personalinformations', [
            'foreignKey' => 'user_id',
            'className' => 'User.Personalinformations'
        ]);

        $this->hasOne('Usersocialdata', [
            'foreignKey' => 'user_id',
            'className' => 'User.Usersocialdata'
        ]);

        $this->_setAppRelations(Configure::read('user_plugin.relations'));
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
            ->allowEmpty('avatar_path');

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

    
    public function saveUser(Array $data)
    {
        $user = $this->newEntity($this->formatRequestData($data));
        if (! $this->save($user)) {
            return false;
        }
        if (! $this->sendVerificationEmail($user)) {
            return false;
        }
        return $user;
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
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['usertype_id'], 'Usertypes'));
        return $this->_setExtraBuildRules($rules, Configure::read('user_plugin.rules'));
    }

    /**
     * sendVerificationEmail method
     *
     * @param User $user the User info.
     * @return bool
     */
    public function sendVerificationEmail($user)
    {
        $email = new Email('default');
        $code = $user->emailcheckcode;

        $email->from(['me@example.com' => 'Your System'])
            ->emailFormat('html')
            ->template('register', 'default')
            ->viewVars(['code' => $code])
            ->to($user->email)
            ->subject('Your System registration');
        if ($email->send()) {
            return true;
        }

        debug($email);
        die();
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
     * hash methdo
     * Creates a hashed password using the DefaultPasswordHaser class
     * @param string $value Plani text password
     * @return string Hashed passowrd
     */
    public function hash($value)
    {
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($value);
    }

    /**
     * formatRequestData method
     * Formats user request data extracting Personal information
     * and adding it to its own model
     * @param array $data  Request Data
     * @return array  Formated data
     */
    public function formatRequestData(Array $data)
    {
        $fields = ['gender_id', 'first_name', 'last_name', 'birth', 'phone1', 'phone2'];
        if (isset($data['birth'])) {
            $data['birth'] = new Time($data['birth']);
        }

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $data['personalinformation'][$field] = $data[$field];
                unset($data[$field]);
            }

            if (isset($data['User'][$field])) {
                $data['personalinformation'][$field] = $data['User'][$field];
                unset($data['User'][$field]);
            }
        }
        return $data;
    }

    /**
     * Resets the password
     *
     * @param user $user user entity
     * @param array $passwordData Post data from controller
     * @return boolean True on success
     */
    public function resetPassword($code, $email, $newPassword)
    {
        $user = $this->find()
            ->where(['passwordchangecode' => $code, 'email' => $email])
            ->first();
    
        if (empty($user)) {
            return false;
        }

        $user->password = $this->hash($newPassword);
        $user->passwordchangecode = null;
        if (! $this->save($user)) {
            return false;
        }

        return true;
    }

    /**
     * Checks if an email is in the system, validated and if the user is active so that the user is allowed to reset his password
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
        }
        return false;
    }

    /**
     * isUserResourceOwner method Checks if the user owns a Resource
     * @param int $userId Id of the User
     * @param int $resourceId Id of the Resourece
     * @param string $resourceModelName Name of the resource's table class
     * @param string $userFK Name of the foreign key of the User table on the Resource class
     * @return bool
     */
    public function isUserResourceOwner($userId, $resourceId, $resourceModelName, $userFK = 'user_id')
    {
        if (($resourceModelName == '') || (! $resourceId) || (! $userId)) {
            return false;
        }

        $resource = $this->$resourceModelName->get($resourceId);
        if (! $resource) {
            return false;
        }

        if ($resource[$userFK] != $userId) {
            return false;
        }

        return true;
    }
}
