<?php
namespace User\Model\Entity;

use Cake\ORM\Entity;

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

    protected $_hidden = ['is_active', 'modified', 'created', 'gender_id'];
    /**
     * virtual field full name
     */
    protected function _getFullName()
    {
        return $this->_properties['first_name'] . ' ' .
            $this->_properties['last_name'];
    }
}
