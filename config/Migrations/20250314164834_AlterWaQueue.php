<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AlterWaQueue extends BaseMigration
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
        $table->addColumn('persona_id', 'integer', [
            'null' => true,
            'after' => 'phone'
        ]);
        $table->update();
    }
}
