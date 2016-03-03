<?php
namespace User\Auth;

use ADmad\JwtAuth\Auth\JwtAuthenticate as BaseAuthanticate;

use Cake\Core\Configure;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use Exception;
use Firebase\JWT\JWT;

/**
 * An authentication adapter for authenticating using JSON Web Tokens.
 *
 * ```
 *  $this->Auth->config('authenticate', [
 *      'ADmad/JwtAuth.Jwt' => [
 *          'parameter' => '_token',
 *          'userModel' => 'Users',
 *          'scope' => ['User.active' => 1]
 *          'fields' => [
 *              'id' => 'id'
 *          ],
 *      ]
 *  ]);
 * ```
 *
 * @copyright 2014 A. Sarela aka ADmad
 * @license MIT
 * @see http://jwt.io
 * @see http://tools.ietf.org/html/draft-ietf-oauth-json-web-token
 */
class OxentiJwtAuthenticate extends BaseAuthanticate
{
    /**
     * Get user record based on info available in JWT.
     *
     * @param \Cake\Network\Request $request Request object.
     *
     * @return bool|array User record array or false on failure.
     */
    public function getUser(Request $request)
    {
        $payload = $this->getPayload($request);

        $user = $this->_findUser($payload->id);
        if (!$user) {
            return false;
        }

        unset($user[$this->_config['fields']['password']]);

        return $user;
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
        $user = parent::_findUser($token, $password);
        return $user;
    }

    /**
     * Get token from header or query string.
     *
     * @param \Cake\Network\Request|null $request Request object.
     *
     * @return string|null Token string if found else null.
     */
    public function getToken($request = null)
    {
        $config = $this->_config;

        if (!$request) {
            return $this->_token;
        }

        // $header = $request->header($config['header']);
        $header = getallheaders()['Authorization'];
        if ($header) {
            return $this->_token = str_ireplace($config['prefix'] . ' ', '', $header);
        }


        if (!empty($this->_config['parameter'])) {
            $token = $request->query($this->_config['parameter']);
        }

        return $this->_token = $token;
    }
}
