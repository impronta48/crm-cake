<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * WaQueueFixture
 */
class WaQueueFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'wa_queue';
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'phone' => 'Lorem ipsum dolor sit amet',
                'persona_id' => 1,
                'wa_session' => 'Lorem ipsum dolor sit amet',
                'from_name' => 'Lorem ipsum dolor sit amet',
                'config' => 'Lorem ipsum dolor sit amet',
                'template' => 'Lorem ipsum dolor sit amet',
                'layout' => 'Lorem ipsum dolor sit amet',
                'body' => 'Lorem ipsum dolor sit amet',
                'sent' => 1,
                'locked' => 1,
                'send_tries' => 1,
                'send_at' => '2025-03-14 16:50:17',
                'created' => 1741971017,
                'modified' => 1741971017,
                'error' => 'Lorem ipsum dolor sit amet',
                'campaign_id' => 1,
            ],
        ];
        parent::init();
    }
}
