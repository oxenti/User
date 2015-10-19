<?php
namespace User\Auth;

use ADmad\JwtAuth\Auth\JwtAuthenticate as BaseAuthanticate;
use Cake\ORM\TableRegistry;

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
class JwtAuthenticate extends BaseAuthanticate
{
   
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
}
