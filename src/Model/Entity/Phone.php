<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Phone Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $phone_code
 * @property string $phone_number
 * @property string $full_phone
 * @property bool $is_phone_verified
 * @property string $phone_verification_code
 * @property bool $is_default
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $updated_at
 * @property \Cake\I18n\FrozenTime|null $deleted_at
 *
 * @property \App\Model\Entity\User $user
 */
class Phone extends Entity
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
        'phone_code' => true,
        'phone_number' => true,
        'full_phone' => true,
        'is_phone_verified' => true,
        'phone_verification_code' => true,
        'is_default' => true,
        'created_at' => true,
        'updated_at' => true,
        'deleted_at' => true,
        'user' => true,
    ];
}
