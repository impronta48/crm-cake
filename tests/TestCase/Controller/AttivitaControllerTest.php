<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\AttivitaController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\AttivitaController Test Case
 *
 * @uses \App\Controller\AttivitaController
 */
class AttivitaControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Attivita',
        'app.Progetti',
        'app.Clienti',
        'app.Aree',
        'app.Aziende',
        'app.Aliases',
        'app.Cespiticalendario',
        'app.Ddt',
        'app.Faseattivita',
        'app.Fattureemesse',
        'app.Fatturericevute',
        'app.Ordini',
        'app.Primanota',
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
