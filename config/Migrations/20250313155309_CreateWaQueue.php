<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateWaQueue extends BaseMigration
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
        $table = $this->table('wa_queue');

        $table->addColumn('phone', 'string', [
            'null' => false,
        ]);
        $table->addColumn('wa_session', 'string', [
            'null' => false,
        ]);
        $table->addColumn('from_name', 'string', [
            'null' => true,
        ]);
        $table->addColumn('config', 'string', [
            'null' => true,
        ]);
        $table->addColumn('template', 'string', [
            'null' => true,
        ]);
        $table->addColumn('layout', 'string', [
            'null' => true,
        ]);
        $table->addColumn('body', 'string', [
            'null' => true,
        ]);
        $table->addColumn('sent', 'boolean', [
            'null' => false,
            'default' => false
        ]);
        $table->addColumn('locked', 'boolean', [
            'null' => false,
            'default' => false
        ]);
        $table->addColumn('send_tries', 'integer', [
            'null' => false,
            'default' => 0
        ]);
        $table->addColumn('send_at', 'datetime', [
            'null' => true
        ]);
        $table->addColumn('created', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);
        $table->addColumn('modified', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP'
        ]);
        $table->addColumn('error', 'string', [
            'null' => true,
        ]);
        $table->addColumn('campaign_id', 'integer', [
            'null' => true,
        ]);

        $table->create();
    }
    
}
