<?php

namespace Nathaniel\BikeSimulator\Tests\Unit\Command;

use Nathaniel\BikeSimulator\Bike;

/**
 * Unit test for reporting bike GPS coordinates
 */
class GpsReportCommandTest extends \PHPUnit\Framework\TestCase {

    private $simulation;
    private $command;

    protected function setUp(): void {
        $this->simulation = new \Nathaniel\BikeSimulator\Simulation(7,7);
        $this->simulation->setIsBikePlaced(true);
        $this->command = new \Nathaniel\BikeSimulator\Command\GpsReportCommand($this->simulation, 'GPS_REPORT');
    }

    /**
     * Test validation
     */
    public function testBasicValidation() {
        $this->assertEquals(true, $this->command->validate(), "Basic Validation failed");
    }

    public function testApply() {
        $this->simulation->setBikePosition([0, 0]);
        $this->simulation->setBikeDirection('NORTH');
        $this->assertEquals('(0,0), NORTH', $this->command->apply(), "GPS Reporting failed");
        $this->simulation->setBikePosition([1, 3]);
        $this->simulation->setBikeDirection('WEST');
        $this->assertEquals('(1,3), WEST', $this->command->apply(), "GPS Reporting failed");
    }

}