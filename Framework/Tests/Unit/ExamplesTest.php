<?php

namespace Nathaniel\BikeSimulator;


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
        $this->markTestIncomplete('Need to build in forward and GPS report commands');
        $inputs = [
            'PLACE 0,5,NORTH',
            'FORWARD',
            'GPS_REPORT'
        ];
        $output = '(0,6), NORTH';
        $this->assertEquals($output, $this->simulation->run($inputs), "Failed first example");
    }

}