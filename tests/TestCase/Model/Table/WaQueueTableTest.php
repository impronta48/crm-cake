<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WaQueueTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WaQueueTable Test Case
 */
class WaQueueTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\WaQueueTable
     */
    protected $WaQueue;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.WaQueue',
        'app.Campaigns',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('WaQueue') ? [] : ['className' => WaQueueTable::class];
        $this->WaQueue = $this->getTableLocator()->get('WaQueue', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->WaQueue);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\WaQueueTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\WaQueueTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test enqueue method
     *
     * @return void
     * @uses \App\Model\Table\WaQueueTable::enqueue()
     */
    public function testEnqueue(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test getBatch method
     *
     * @return void
     * @uses \App\Model\Table\WaQueueTable::getBatch()
     */
    public function testGetBatch(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test releaseLocks method
     *
     * @return void
     * @uses \App\Model\Table\WaQueueTable::releaseLocks()
     */
    public function testReleaseLocks(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test clearLocks method
     *
     * @return void
     * @uses \App\Model\Table\WaQueueTable::clearLocks()
     */
    public function testClearLocks(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test success method
     *
     * @return void
     * @uses \App\Model\Table\WaQueueTable::success()
     */
    public function testSuccess(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test fail method
     *
     * @return void
     * @uses \App\Model\Table\WaQueueTable::fail()
     */
    public function testFail(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test status method
     *
     * @return void
     * @uses \App\Model\Table\WaQueueTable::status()
     */
    public function testStatus(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
