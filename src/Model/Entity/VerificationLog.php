<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VerificationLog Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $communication_channel_id
 * @property string $communication_channel_type
 * @property int $attempts
 * @property string $session_id
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $updated_at
 * @property \Cake\I18n\FrozenTime|null $deleted_at
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\CommunicationChannel $communication_channel
 * @property \App\Model\Entity\Session $session
 */
class VerificationLog extends Entity
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
        'user_id' => true,
        'communication_channel_id' => true,
        'communication_channel_type' => true,
        'attempts' => true,
        'device' => true,
        'session_id' => true,
        'created_at' => true,
        'updated_at' => true,
        'deleted_at' => true,
        'user' => true,
        'communication_channel' => true,
        'session' => true,
    ];
}
