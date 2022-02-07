<?php

namespace Nathaniel\BikeSimulator\Tests\Unit;


/**
 * Unit test the examples provided
 */
class ExamplesTest extends \PHPUnit\Framework\TestCase {

    private $simulation;

    protected function setUp(): void {
        $this->simulation = new \Nathaniel\BikeSimulator\Simulation(7,7);
    }

    /**
     * Test example one
     */
    public function testExampleOne() {
        // $this->markTestIncomplete('Need to build in forward and GPS report commands');
        $inputs = <<<COMMAND
PLACE 0,5,NORTH
FORWARD
GPS_REPORT
COMMAND;
        $output = '(0,6), NORTH';
        $this->simulation->run($inputs);
        $modifiedOutput = str_replace(
            'GPS Output: ', 
            '', 
            $this->simulation->getSimulationMessages()[0]
        );

        $this->assertEquals(
            $output, 
            $modifiedOutput,
            "Failed first example"
        );
    }

    public function testExampleTwo() {
        $inputs = <<<COMMAND
PLACE 0,0,NORTH
TURN_LEFT
GPS_REPORT
COMMAND;

        $output = '(0,0), WEST';
        $this->simulation->run($inputs);
        $modifiedOutput = str_replace(
            'GPS Output: ', 
            '', 
            $this->simulation->getSimulationMessages()[0]
        );

        $this->assertEquals(
            $output, 
            $modifiedOutput,
            "Failed second example"
        );
    }



    public function testExampleThree() {
        $inputs = <<<COMMAND
PLACE 1,2,EAST
FORWARD
FORWARD
TURN_LEFT
FORWARD
GPS_REPORT
COMMAND;

        $output = '(3,3), NORTH';
        $this->simulation->run($inputs);
        $modifiedOutput = str_replace(
            'GPS Output: ', 
            '', 
            $this->simulation->getSimulationMessages()[0]
        );

        $this->assertEquals(
            $output, 
            $modifiedOutput,
            "Failed third example"
        );

    }

}