<?php

declare(strict_types=1);

use Migrations\BaseMigration;

class AlterCampaigns extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('campaigns');
        $table->addColumn('type', 'string', [
            'default' => 'email',
            'null' => false,
            'after' => 'user_id'
        ]);
        $table->addColumn('wa_session', 'string', [
            'default' => null,
            'null' => true,
            'after' => 'type'
        ]);
        $table->update();
    }
}
