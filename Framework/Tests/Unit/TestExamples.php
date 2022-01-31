<?php

namespace Nathaniel\BikeSimulator;


/**
 * Unit test the examples provided
 */
class TestExamples extends \PHPUnit\Framework\TestCase {

    /**
     * Test example one
     */
    public function testExampleOne() {
        $inputs = [
            'PLACE 0,5,NORTH',
            'FORWARD',
            'GPS_REPORT'
        ];
        $output = '(0,6), NORTH';
        $this->assertEquals($output, $this->simulation->simulate($inputs), "Failed first example");

    }

}