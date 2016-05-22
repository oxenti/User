<?php
namespace User\Controller\Component;

use App\Network\Exception\BadRequestException;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use Exception;
use Firebase\JWT\JWT;


if (!function_exists('getallheaders'))
{
   function getallheaders()
   {
          $headers = '';
      foreach ($_SERVER as $name => $value)
      {
          if (substr($name, 0, 5) == 'HTTP_')
          {
              $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
          }
      }
      return $headers;
   }
}

/**
 * Jwt component
 */
class JwtComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'parameter' => '_token',
        'userModel' => 'User.Users',
        'userTokenModel' => 'User.Usertokens',
        'fields' => ['id' => 'id'],
        'unauthenticatedException' => '\Cake\Network\Exception\UnauthorizedException',
        'allowedAlgs' => ['HS256']
    ];

    /**
     * initialize.
     *
     * Settings for this object.
     *
     * - `parameter` - The url parameter name of the token. Defaults to `_token`.
     *   First $_SERVER['HTTP_AUTHORIZATION'] is checked for token value.
     *   Its value should be of form "Bearer <token>". If empty this query string
     *   paramater is checked.
     * - `userModel` - The model name of the User, defaults to `Users`.
     * - `fields` - Has key `id` whose value contains primary key field name.
     *   Defaults to ['id' => 'id'].
     * - `scope` - Additional conditions to use when looking up and authenticating
     *   users, i.e. `['Users.is_active' => 1].`
     * - `contain` - Extra models to contain.
     * - `unauthenticatedException` - Fully namespaced exception name. Exception to
     *   throw if authentication fails. Set to false to do nothing.
     *   Defaults to '\Cake\Network\Exception\UnauthorizedException'.
     * - `allowedAlgs` - List of supported verification algorithms.
     *   Defaults to ['HS256']. See API of JWT::decode() for more info.
     *
     * @param array $config Array of config to use.
     */
    public function initialize(array $config)
    {
        $this->config([
            'parameter' => '_token',
            'fields' => ['id' => 'id', 'password' => 'password'],
            'unauthenticatedException' => '\Cake\Network\Exception\UnauthorizedException',
            'allowedAlgs' => ['HS256']
        ]);
    }

    /**
     * Get user record based on info available in JWT.
     *
     * @return bool|array User record array or false on failure.
     */
    public function getUser()
    {
        $token = $this->_getToken($this->request);
        if ($token) {
            return $this->_findUser($token);
        }

        return false;
    }

    /**
     * Get token from header or query string.
     *
     * @param \Cake\Network\Request $request Request object.
     * @return string|bool Token string if found else false.
     */
    protected function _getToken($request)
    {
        $token = $request->env('HTTP_AUTHORIZATION');
        // @codeCoverageIgnoreStart
        if (!$token && function_exists('getallheaders')) {
            $headers = array_change_key_case(getallheaders());
            if (isset($headers['authorization']) &&
                substr($headers['authorization'], 0, 7) === 'Bearer '
            ) {
                $token = $headers['authorization'];
            }
        }
        // @codeCoverageIgnoreEnd
        if ($token) {
            return substr($token, 7);
        }

        if (!empty($this->_config['parameter']) &&
            isset($request->query[$this->_config['parameter']])
        ) {
            $token = $request->query($this->_config['parameter']);
        }

        return $token ? $token : false;
    }

    /**
     * Find a user record.
     *
     * @param string $token The token identifier.
     * @param string $password Unused password.
     * @return bool|array Either false on failure, or an array of user data.
     */
    protected function _findUser($token, $password = null)
    {
        $userTokenTable = TableRegistry::get($this->_config['userTokenModel']);

        try {
            $token = $userTokenTable->decode($token, 'access_token', $this->_config['allowedAlgs']);
        } catch (Exception $e) {
            throw new BadRequestException("Expired Token", 401);
        }

        // Token has full user record.
        if (isset($token->record)) {
            // Trick to convert object of stdClass to array. Typecasting to
            // array doesn't convert property values which are themselves objects.
            return json_decode(json_encode($token->record), true);
        }

        $fields = $this->_config['fields'];

        $table = TableRegistry::get($this->_config['userModel']);
        $conditions = [$table->aliasField($fields['id']) => $token->id];
        if (!empty($this->_config['scope'])) {
            $conditions = array_merge($conditions, $this->_config['scope']);
        }

        $query = $table->find('all')
            ->where($conditions);

        if ($this->_config['contain']) {
            $query = $query->contain($this->_config['contain']);
        }

        $result = $query->first();
        if (empty($result)) {
            return false;
        }

        unset($result[$fields['password']]);
        return $result->toArray();
    }
}
