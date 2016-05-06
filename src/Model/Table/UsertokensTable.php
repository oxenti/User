<?php
namespace User\Model\Table;

use App\Model\Entity\Usertoken;
use Cake\Core\Exception\Exception;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\Utility\Security;
use Firebase\JWT\JWT;
use UAParser\Parser;
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
        if (empty($userId)) {
            throw new Exception("Invalid user", 401);
        }

        $expiresIn = time() + ($isRefresh ? 4838400 : 86400); /* refresh 8 weeks, access 1 day */

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

    public function decode($encodedToken, $tokenType, $algs, $userAgent = null) {
        if (empty($tokenType) || ($tokenType !== "access_token" && $tokenType !== "refresh_token")) {
            throw new Exception("Invalid token type", 401);
        }

        if ($tokenType === "refresh_token" && empty($userAgent)) {
            throw new Exception("Invalid request", 401);
        }

        try {
            $token = JWT::decode($encodedToken, Security::salt(), $algs);
        } catch (Exception $e) {
            throw new Exception("Expired Token", 401);
        }

        $conditions = [
            $this->aliasField('user_id') => $token->id,
            $this->aliasField($tokenType) => $encodedToken
        ];

        $userToken = $this->find('all')
            ->select([
                $this->alias() . '.id',
                $this->alias() . '.access_token',
                $this->alias() . '.user_agent'
            ])
            ->where($conditions)
            ->first();

        if (empty($userToken)) {
            throw new Exception("Invalid token", 401);
        }

        if ($tokenType !== "refresh_token") {
            return $token;
        }

        /*
         * se for refresh token
         * 1 - deletar o token para evitar novo uso
         * 2 - se nao tiver user_agent requisicao invalida
         * 3 - verificar se access token é valido
         * 4 - validar userAgent
         */

        $this->delete($userToken);

        if (empty($userToken['user_agent'])) {
            throw new Exception("Invalid token", 401);
        }

        $userAgentRegistry = $userToken['user_agent'];

        $accessToken = $userToken['access_token'];

        try {
            $accessToken = $this->decode($accessToken, 'access_token', [ 'HS256' ]);
        } catch (Exception $e) {
            $accessToken = null;
        }

        /* se acess token for valido, refresh token nao pode ser usado */
        if (!empty($accessToken)) {
            throw new Exception("Invalid request", 401);
        }

        if ($userAgent === $userAgentRegistry) {
            return $token;
        }

        $parser = Parser::create();
        $userAgent = $parser->parse($userAgent);
        $userAgentRegistry = $parser->parse($userAgentRegistry);

        if ($userAgent->device->family !== $userAgentRegistry->device->family || empty($userAgent->device->family) || empty($userAgentRegistry->device->family)) {
            throw new Exception("Invalid device family", 401);
        }

        if ($userAgent->os->family !== $userAgentRegistry->os->family || empty($userAgent->os->family) || empty($userAgentRegistry->os->family)) {
            throw new Exception("Invalid OS family", 401);
        }

        if ($userAgent->os->toVersion() < $userAgentRegistry->os->toVersion() || empty($userAgent->os->toVersion()) || empty($userAgentRegistry->os->toVersion())) {
            throw new Exception("Invalid OS version", 401);
        }

        if ($userAgent->ua->family !== $userAgentRegistry->ua->family || empty($userAgent->ua->family) || empty($userAgentRegistry->ua->family)) {
            throw new Exception("Invalid UA family", 401);
        }

        if ($userAgent->ua->toVersion() < $userAgentRegistry->ua->toVersion() || empty($userAgent->ua->toVersion()) || empty($userAgentRegistry->ua->toVersion())) {
            throw new Exception("Invalid UA version", 401);
        }

        return $token;
    }
}
