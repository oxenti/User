<?php
namespace User\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * User Entity.
 *
 * @property int $id
 * @property int $usertype_id
 * @property \User\Model\Entity\Usertype $usertype
 * @property string $avatar_path
 * @property string $login
 * @property string $password
 * @property string $email
 * @property string $emailcheckcode
 * @property string $passwordchangecode
 * @property bool $is_active
 * @property \Cake\I18n\Time $expire_account
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \User\Model\Entity\Usersocialdata[] $usersocialdata
 */
class User extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    // protected $_virtual = ['full_name'];

    protected $_hidden = ['avatar_path', 'personalinformation_id', 'password', 'login', 'emailcheckcode', 'passwordchangecode', 'expire_account', 'token', 'created', 'is_active', 'modified'];
    protected $_virtual = ['avatar_url'];

    /**
     * Set Hashed password, before save
     */
    protected function _setPassword($value)
    {
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($value);
    }

    protected function _getAvatarUrl()
    {
        $path = '';
        if (isset($this->_properties['avatar_path'])) {
            if ($this->_properties['avatar_path']) {
                $path = (strpos('http://', $this->_properties['avatar_path']) || strpos('https://', $this->_properties['avatar_path'])) ? $this->_properties['avatar_path'] : Router::url('/', true) . $this->_properties['avatar_path'];
            }
        }
        
        return $path;
    }
 
}
