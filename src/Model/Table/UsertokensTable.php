<?php
namespace User\Model\Table;

use App\Model\Entity\Usertoken;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use User\Model\Table\AppTable;

/**
 * Usertokens Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 */
class UsertokensTable extends AppTable
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

        $this->table('usertokens');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'className' => 'User.Users',
            'joinType' => 'INNER'
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('access_token', 'create')
            ->notEmpty('access_token');

        $validator
            ->allowEmpty('refresh_token');

        $validator
            ->allowEmpty('user_agent');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }

    /**
     * generate method
     * Generates and save the user's access_token and refresh_token
     * @param int $userId User's id
     * @param bool $userAgent user agent
     * @return array
     */
    public function generate($userId, $userAgent = null)
    {
        $data = $this->_generate($userId, $userAgent);

        $userToken = $this->newEntity($data);

        if (!$this->save($userToken)) {
            return false;
        }

        return $data;
    }

    /**
     * _generate method
     * Generates the user's access_token based on the id and refresh_token if is setted the user agent.
     * @param int $userId User's id
     * @param bool $userAgent user agent
     * @return array
     */
    protected function _generate($userId, $userAgent = null)
    {
        $data = [ 'user_id' => $userId ];

        $token = $this->_makeToken($userId, false);

        $data['access_token'] = $token['token'];
        $data['refresh_token'] = $token['token'];
        $data['refresh_expires_in'] = $token['expires_in'];

        /*
         * se não houver userAgent refreshToken tem a mesma validade do access token
         */
        if (empty($userAgent)) {
            return $data;
        }

        $data['user_agent'] = $userAgent;

        $token = $this->_makeToken($userId, true);

        $data['refresh_token'] = $token['token'];
        $data['refresh_expires_in'] = $token['expires_in'];

        return $data;
    }

    /**
     * _makeToken method
     * Generates the user's token based on the id
     * @param int $userId User's id
     * @param bool $isRefresh if is to make refresh token
     * @return array
     */
    protected function _makeToken($userId, $isRefresh = false)
    {
        $expiresIn = time() + ($isRefresh ? 4838400 : 604800); /* refresh 8 weeks, access 1 week */

        $token = JWT::encode(
            [
                'id' => $userId,
                'exp' => $expiresIn
            ],
            Security::salt()
        );

        return [
            'token' => $token,
            'expires_in' => $expiresIn
        ];
    }
}
