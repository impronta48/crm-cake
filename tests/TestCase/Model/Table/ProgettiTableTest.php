<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProgettiTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProgettiTable Test Case
 */
class ProgettiTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ProgettiTable
     */
    protected $Progetti;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Progetti',
        'app.Aree',
        'app.Attivita',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Progetti') ? [] : ['className' => ProgettiTable::class];
        $this->Progetti = $this->getTableLocator()->get('Progetti', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Progetti);

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
