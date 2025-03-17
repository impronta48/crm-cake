<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * WaQueue Entity
 *
 * @property int $id
 * @property string $phone
 * @property int|null $persona_id
 * @property string $wa_session
 * @property string|null $from_name
 * @property string|null $config
 * @property string|null $template
 * @property string|null $layout
 * @property string|null $body
 * @property bool $sent
 * @property bool $locked
 * @property int $send_tries
 * @property \Cake\I18n\DateTime|null $send_at
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 * @property string|null $error
 * @property int|null $campaign_id
 *
 * @property \App\Model\Entity\Campaign $campaign
 */
class WaQueue extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'phone' => true,
        'persona_id' => true,
        'wa_session' => true,
        'from_name' => true,
        'config' => true,
        'template' => true,
        'layout' => true,
        'body' => true,
        'sent' => true,
        'locked' => true,
        'send_tries' => true,
        'send_at' => true,
        'created' => true,
        'modified' => true,
        'error' => true,
        'campaign_id' => true,
        'campaign' => true,
    ];
}
