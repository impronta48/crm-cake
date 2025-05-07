<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PercorsiTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PercorsiTable Test Case
 */
class PercorsiTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PercorsiTable
     */
    protected $Percorsi;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Percorsi') ? [] : ['className' => PercorsiTable::class];
        $this->Percorsi = $this->getTableLocator()->get('Percorsi', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Percorsi);

        parent::tearDown();
    }
}
