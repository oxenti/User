<?php
namespace User\Model\Entity;

use Cake\ORM\Entity;

/**
 * Usersocialdata Entity.
 *
 * @property int $id
 * @property int $user_id
 * @property \User\Model\Entity\User $user
 * @property string $linkedin_id
 * @property \User\Model\Entity\Linkedin $linkedin
 * @property string $linkedin_token
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Usersocialdata extends Entity
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
}
