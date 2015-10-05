<?php
namespace User\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity.
 *
 * @property int $id
 * @property int $usertype_id
 * @property \User\Model\Entity\Usertype $usertype
 * @property int $gender_id
 * @property \User\Model\Entity\Gender $gender
 * @property string $first_name
 * @property string $last_name
 * @property \Cake\I18n\Time $birth
 * @property string $avatar_path
 * @property string $phone1
 * @property string $phone2
 * @property string $login
 * @property string $password
 * @property string $email
 * @property string $emailcheckcode
 * @property string $passwordchangecode
 * @property bool $is_active
 * @property \Cake\I18n\Time $expire_account
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \User\Model\Entity\Address[] $addresses
 * @property \User\Model\Entity\Institution[] $institutions
 * @property \User\Model\Entity\Resource[] $resources
 * @property \User\Model\Entity\Student[] $students
 * @property \User\Model\Entity\Teacher[] $teachers
 * @property \User\Model\Entity\Tutor[] $tutors
 * @property \User\Model\Entity\Usermessage[] $usermessages
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
}
