<?php

namespace Nathaniel\BikeSimulator\Tests\Unit\Command;

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
        $this->assertEquals($output, $command->getParams(), "Failed extracting parameters");
    }

    public function validationDataProvider() {
        return [
            'valid' => ['PLACE 0,5,NORTH', true],
            'edge valid' => ['PLACE 7,7,WEST', true],
            'too many args' => ['PLACE 0,5,NORTH,invalid', false],
            'too much x' => ['PLACE 19,5,NORTH', false],
            'too small x' => ['PLACE -2,5,NORTH', false],
            'too much y' => ['PLACE 2,8,NORTH', false],
            'too small y' => ['PLACE 2,-1,NORTH', false],
            'invalid direction' => ['PLACE 2,2,NWUAST', false],
        ];
    }

    /**
     * Test validation
     * @dataProvider validationDataProvider
     */
    public function testValidation($input, $output) {
        $command = new \Nathaniel\BikeSimulator\Command\PlaceCommand($this->simulation, $input);
        $this->assertEquals($output, $command->validate(), "Validation failed");
    }

}