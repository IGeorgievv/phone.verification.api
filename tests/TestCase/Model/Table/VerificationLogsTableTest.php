<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VerificationLogsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VerificationLogsTable Test Case
 */
class VerificationLogsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\VerificationLogsTable
     */
    protected $VerificationLogs;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.VerificationLogs',
        'app.Users',
        'app.CommunicationChannels',
        'app.Sessions',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('VerificationLogs') ? [] : ['className' => VerificationLogsTable::class];
        $this->VerificationLogs = $this->getTableLocator()->get('VerificationLogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->VerificationLogs);

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
