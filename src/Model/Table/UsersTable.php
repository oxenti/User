<?php
namespace User\Model\Table;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Network\Email\Email;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use User\Event\UserListener;
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

        $now = strtotime("now");
        $this->addBehavior('User.Uploadable', [
            'avatar_path' => [
                'field' => 'avatar_path',
                'path' => '{ROOT}{DS}{WEBROOT}{DS}uploads{DS}{model}{DS}avatar{DS}{primaryKey}{DS}',
                'fileName' => '{primaryKey}_avatar_' . $now . '.{extension}',
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

        $this->belongsTo('Personalinformations', [
            'foreignKey' => 'personalinformation_id',
            'className' => 'User.Personalinformations'
        ]);

        $this->hasOne('Usersocialdata', [
            'foreignKey' => 'user_id',
            'className' => 'User.Usersocialdata'
        ]);

        $this->_setAppRelations(Configure::read('user_plugin.relations'));

        // Register event listeners
        $UserListener = new UserListener();
        $this->eventManager()->on($UserListener);
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
            ->add('personalinformation_id', 'valid', ['rule' => 'numeric'])
            ->notEmpty('personalinformation_id');

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
        if ($entity->isNew() || ($entity->getOriginal('email') != $entity->email)) {
            $entity->emailcheckcode = md5(time() * rand());
        }
    }

    public function afterSave($event, $entity, $options)
    {
        if ($entity->isNew()) {
            $event = new Event('Model.User.requireVerification', $this, ['entity' => $entity, 'action' => 'register']);
            $this->eventManager()->dispatch($event);
        }

        if (($entity->getOriginal('email') != $entity->email)) {
            $event = new Event('Model.User.requireVerification', $this, ['entity' => $entity, 'action' => 'email_change']);
            $this->eventManager()->dispatch($event);
        }

        if (($entity->getOriginal('password') != $entity->password)) {
            $event = new Event('Model.User.requireVerification', $this, ['entity' => $entity, 'action' => 'password_change']);
            $this->eventManager()->dispatch($event);
        }
    }


    /**
     * Save a new User
     */
    public function saveUser(Array $data)
    {
        // $url = $data['urlVerify'];
        // unset($data['urlVerify']);
        $user = $this->newEntity($this->formatRequestData($data));

        if ($this->save($user)) {
            if (! $this->sendVerificationEmail($user)) {
                $this->delete($user);
                return false;
            }
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
    public function sendVerificationEmail($user, $url = null)
    {
        $email = new Email(Configure::read('auth_plugin.email_settings.transport'));
        $code = $user->emailcheckcode;

        $verificationUrl = Configure::read('debug') ? Configure::read('auth_plugin.verify_url.dev') : Configure::read('auth_plugin.verify_url.production');
        $email->from(Configure::read('auth_plugin.email_settings.from'))
            ->emailFormat('html')
            ->template('register', 'default')
            ->viewVars(['name' => $user->personalinformation->first_name, 'serviceName' => Configure::read('auth_plugin.service_name'), 'code' => $code, 'url' => $verificationUrl])
            ->to($user->email)
            ->subject(Configure::read('auth_plugin.email_settings.register_subject'));

        return $email->send();
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

    public function checkPassword($password, $currentPass)
    {
        $hasher = new DefaultPasswordHasher();
        return $hasher->check($password, $currentPass);
    }

    /**
     * formatRequestData method
     * Formats user request data extracting Personal information
     * and adding it to its own model
     * @param array $data  Request Data
     * @return array  Formated data
     */
    public function formatRequestData(array $data, $entity = null)
    {
        if (isset($data['user'])) {
            $data = $data['user'];
        }
     
        $personalFields = ['gender_id', 'first_name', 'last_name', 'birth', 'birthday', 'phone1', 'phone2'];

        foreach ($personalFields as $field) {
            if (isset($data[$field])) {
                $data['personalinformation'][$field] = $data[$field];
                unset($data[$field]);
            }

            if (isset($data['User'][$field])) {
                $data['personalinformation'][$field] = $data['User'][$field];
                unset($data['User'][$field]);
            }
        }

        if (isset($data['personalinformation']['birthday'])) {
            $data['personalinformation']['birth'] = $data['personalinformation']['birthday'];
        }
        
        return $entity ? $this->setEntityUserIds($data, $entity) : $data;
    }

    /**
     * setEntityUserIds method
     * @param array $userData User data array
     * @param Entity $entity Business entity
     * @return array
     */
    public function setEntityUserIds(array $userData, $entity)
    {
        if (isset($entity->id)) {
            $userData['id'] = $entity->id;
        }

        if (isset($entity->personalinformation_id) && isset($userData['personalinformation'])) {
            $userData['personalinformation']['id'] = $entity->personalinformation_id;
        }

        if (isset($entity->addresses) && isset($userData['addresses'])) {
            foreach ($entity->addresses as $address) {
                if ($address->is_active) {
                    $userData['addresses'][0]['id'] = $address->id;
                    break;
                }
            }
        }
        return $userData;
    }

    /**
     * setUsertype method
     * @param array $data User data
     * @param string $tableName Table class name
     * @return array
     */
    public function setUsertype(array $data, $tableName)
    {
        $data['usertype_id'] = $this->getUserType($tableName);
        return $data;
    }

    /**
     * getUsertype method Returns the user type id for a table class
     * @param string $tableName Table class name
     * @return string|false
     */
    public function getUserType($tableName)
    {
        return Configure::read('user_plugin.usertypes.' . $tableName);
    }

    /**
     * Resets the password
     *
     * @param user $user user entity
     * @param array $passwordData Post data from controller
     * @return boolean True on success
     */
    public function resetPassword($user, $data)
    {
        if (empty($user) || empty($data) || !isset($data['password'])) {
            return false;
        }

        // $user->password = $this->hash($data['password']);
        $user->password = $data['password']; // the entity automatically hashes the password.
        $user->passwordchangecode = null;

        return $this->save($user);
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
