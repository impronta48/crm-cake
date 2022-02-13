<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PersoneTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PersoneTable Test Case
 */
class PersoneTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PersoneTable
     */
    protected $Persone;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Persone',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Persone') ? [] : ['className' => PersoneTable::class];
        $this->Persone = $this->getTableLocator()->get('Persone', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Persone);

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
}
