<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AlterUsersTelegram extends BaseMigration
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
        $table = $this->table('users');
        $table->addColumn('telegram_id', 'string', [
            'null' => true,
            'after' => 'password'
        ]);
        $table->update();
    }
}
