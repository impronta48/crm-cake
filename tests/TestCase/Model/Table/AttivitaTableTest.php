<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AttivitaTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AttivitaTable Test Case
 */
class AttivitaTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AttivitaTable
     */
    protected $Attivita;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Attivita',
        'app.Progettos',
        'app.Clientes',
        'app.Areas',
        'app.Aziendas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Attivita') ? [] : ['className' => AttivitaTable::class];
        $this->Attivita = $this->getTableLocator()->get('Attivita', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Attivita);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
