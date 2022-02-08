<?php

namespace Nathaniel\BikeSimulator\Tests\Unit\Command;

/**
 * Unit test for moving the bike forward command
 */
class ForwardCommandTest extends \PHPUnit\Framework\TestCase {

    private $simulation;

    protected function setUp(): void {
        $this->simulation = new \Nathaniel\BikeSimulator\Simulation(7,7);
        $this->simulation->setIsBikePlaced(true);
    }

    /**
     * Test validation
     */
    public function testBasicValidation() {
        $command = new \Nathaniel\BikeSimulator\Command\ForwardCommand($this->simulation, 'FORWARD');
        $this->assertEquals(true, $command->validate(), "Basic Validation failed");
    }

    public function testBikeCanMoveForward() {
        $this->simulation->setBikePosition([0,6]);
        $this->simulation->setBikeDirection('NORTH');
        $command = new \Nathaniel\BikeSimulator\Command\ForwardCommand($this->simulation, 'FORWARD');
        $command->apply();
        $this->assertEquals([0,7], $this->simulation->getBikePosition(), 'Failed moving bike forward');
    }

}