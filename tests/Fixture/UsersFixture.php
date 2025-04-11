<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
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
                'username' => 'Lorem ipsum dolor sit amet',
                'password' => '',
                'telegram_chat_id' => 'Lorem ipsum dolor sit amet',
                'group_id' => 1,
                'created' => '2025-04-04 14:23:30',
                'modified' => '2025-04-04 14:23:30',
                'persona_id' => 1,
            ],
        ];
        parent::init();
    }
}
