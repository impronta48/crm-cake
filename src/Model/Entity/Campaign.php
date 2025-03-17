<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Campaign Entity
 *
 * @property int $id
 * @property string|null $subject
 * @property string|null $body
 * @property string|null $querystring
 * @property string|null $sender_email
 * @property string|null $sender_name
 * @property string|null $test_email
 * @property string|null $layout
 * @property \Cake\I18n\DateTime|null $sent
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 * @property int|null $user_id
 *
 * @property \App\Model\Entity\User $user
 */
class Campaign extends Entity
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
    protected array $_accessible = [
        'subject' => true,
        'body' => true,
        'querystring' => true,
        'sender_email' => true,
        'sender_name' => true,
        'test_email' => true,
        'layout' => true,
        'sent' => true,
        'created' => true,
        'modified' => true,
        'user_id' => true,
        'user' => true,
        'type' => true,
        'wa_session' => true,
    ];
}
