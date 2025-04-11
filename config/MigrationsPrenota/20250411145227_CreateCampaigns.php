<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateCampaigns extends BaseMigration
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
        $this->table('campaigns')
        ->addColumn('subject', 'string', [
            'collation' => 'utf8mb3_general_ci',
            'default' => null,
            'limit' => 255,
            'null' => true,
        ])
        ->addColumn('body', 'text', [
            'collation' => 'utf8mb3_general_ci',
            'default' => null,
            'limit' => null,
            'null' => true,
        ])
        ->addColumn('querystring', 'string', [
            'collation' => 'utf8mb3_general_ci',
            'default' => null,
            'limit' => 255,
            'null' => true,
        ])
        ->addColumn('sender_email', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ])
        ->addColumn('sender_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ])
        ->addColumn('test_email', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ])
        ->addColumn('layout', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ])
        ->addColumn('sent', 'datetime', [
            'default' => null,
            'limit' => null,
            'null' => true,
        ])
        ->addColumn('created', 'datetime', [
            'default' => null,
            'limit' => null,
            'null' => true,
        ])
        ->addColumn('modified', 'datetime', [
            'default' => null,
            'limit' => null,
            'null' => true,
        ])
        ->addColumn('user_id', 'integer', [
            'default' => null,
            'limit' => null,
            'null' => true,
            'signed' => true,
        ])
        ->addColumn('type', 'string', [
            'default' => 'email',
            'limit' => 255,
            'null' => false,
        ])
        ->addColumn('wa_session', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ])
        ->create();
    }
}
