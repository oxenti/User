<?php
namespace User\Model\Entity;

use Cake\ORM\Entity;

/**
 * Usertype Entity.
 *
 * @property int $id
 * @property int $userjuridicaltype_id
 * @property \User\Model\Entity\Userjuridicaltype $userjuridicaltype
 * @property string $name
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \User\Model\Entity\User[] $users
 */
class Usertype extends Entity
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

    protected $_hidden = ['userjuridicaltype_id', 'created', 'is_active', 'modified'];
}
