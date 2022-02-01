<?php

namespace Nathaniel\BikeSimulator\Tests\Command;

/**
 * Unit test for the place bike command
 */
class PlaceCommandTest extends \PHPUnit\Framework\TestCase {

    private $simulation;

    protected function setUp(): void {
        $this->simulation = new \Nathaniel\BikeSimulator\Simulation(7,7);
    }

    /**
     * Test param extracts
     */
    public function testParamExtraction() {
        $command = new \Nathaniel\BikeSimulator\Command\PlaceCommand($this->simulation, 'PLACE 0,5,NORTH');
        $output = ['0', '5', 'NORTH'];
        $this->assertEquals($output, $this->command->getParams(), "Failed extracting parameters");
    }

}