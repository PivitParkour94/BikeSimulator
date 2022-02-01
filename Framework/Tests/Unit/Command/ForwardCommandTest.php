<?php

namespace Nathaniel\BikeSimulator\Tests\Command;

/**
 * Unit test for moving the bike forward command
 */
class ForwardCommandTest extends \PHPUnit\Framework\TestCase {

    private $simulation;

    protected function setUp(): void {
        $this->simulation = new \Nathaniel\BikeSimulator\Simulation(7,7);
    }

    /**
     * Test validation
     */
    public function testBasicValidation() {
        $command = new \Nathaniel\BikeSimulator\Command\ForwardCommand($this->simulation, 'FORWARD');
        $command->_bike = new \Nathaniel\BikeSimulator\Bike();
        $this->assertEquals(true, $command->validate(), "Basic Validation failed");
    }

    public function testBikeCanMoveForward() {
        $this->markTestIncomplete('Bike positioning moving not supported yet');
    }

}