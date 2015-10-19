<?php
namespace oxenti\User\Model\Entity;

use Cake\ORM\Entity;

/**
 * Personalinformation Entity.
 *
 * @property int $id
 * @property int $user_id
 * @property \oxenti\User\Model\Entity\User $user
 * @property int $gender_id
 * @property \oxenti\User\Model\Entity\Gender $gender
 * @property string $first_name
 * @property string $last_name
 * @property \Cake\I18n\Time $birth
 * @property string $phone1
 * @property string $phone2
 * @property bool $is_active
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Personalinformation extends Entity
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

    protected $_virtual = ['full_name'];

    protected $_hidden = ['user_id', 'is_active', 'modified', 'created', 'gender_id'];
    /**
     * virtual field full name
     */
    protected function _getFullName()
    {
        return $this->_properties['personalinformation']['first_name'] . ' ' .
            $this->_properties['personalinformation']['last_name'];
    }
}
