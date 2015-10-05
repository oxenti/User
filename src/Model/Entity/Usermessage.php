<?php
namespace User\Model\Entity;

use Cake\ORM\Entity;

/**
 * Usermessage Entity.
 *
 * @property int $id
 * @property int $user_id
 * @property \App\Model\Entity\User $Sender
 * @property int $target_id
 * @property \App\Model\Entity\User $Receiver
 * @property int $original_message_id
 * @property \App\Model\Entity\Usermessage $usermessage
 * @property string $title
 * @property string $message
 * @property bool $unread
 * @property int $chatcode
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property bool $is_active
 */
class Usermessage extends Entity
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

    protected $_hidden = ['created', 'is_active', 'modified'];
}
