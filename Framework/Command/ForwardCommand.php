<?php

namespace Nathaniel\BikeSimulator\Command;

use Nathaniel\BikeSimulator\Directions;

/**
 * Command used to place the bike somewhere on the grid
 */
class ForwardCommand implements \Nathaniel\BikeSimulator\ComamndInterface {

    /**
     * @var \Nathaniel\BikeSimulator\Simulation
     */
    private $_simulation;
    private $_input;
    private $_params;
    
    /**
     * @var \Nathaniel\BikeSimulator\Bike 
     */
    public $_bike;

    const NAME = 'FORWARD';

    /**
     * Setup command with unvalidated input
     */
    public function __construct($simulation, $input) {
        $this->_simulation = $simulation;
        $this->_input = $input;
        $this->_params = [];
    }

    /**
     * Get description for the command
     */
    static public function getDescription() {
        return 'Move the bike one block in the direction it is facing';
    }

    /**
     * Get Usage for the command
     */
    static public function getUsage() {
        return 'FORWARD';
    }

    /**
     * Get parameters for the function
     */
    public function getParams() {
        if (!$this->_params) {
            $namelessInputs = str_replace(self::NAME, '', $this->_input);
            if (!strstr($namelessInputs, ',')) {
                $this->_params = [];
                return $this->_params;
            }
            $params = explode(',', $namelessInputs);
            die(var_dump($params));
            $params = array_map('trim', $params);
            $this->_params = $params;
        }
        return $this->_params;
    }

    /**
     * Validate the command meets simulation requirements
     */
    public function validate() {
        if (count($this->getParams()) > 0) {
            return false;
            // throw new \Exception('Too many parameters');
        }
        $direction = $this->_simulation->getBikeDirection();
        $position = $this->_simulation->getBikePosition();

        switch ($direction) {
            case Directions::NORTH:
                // check if block north is accessible
                if ($position[1]+1 > $this->_simulation->getSize()[1]) {
                    return false;
                }
                break;
            case Directions::EAST:
                // check if block east is accessible
                if ($position[0]+1 > $this->_simulation->getSize()[0]) {
                    return false;
                }
                break;
            case Directions::SOUTH:
                // check if block south is accessible
                if ($position[1]-1 < 0) {
                    return false;
                }
                break;
            case Directions::WEST:
                // check if block west is accessible
                if ($position[0]-1 < 0) {
                    return false;
                }
                break;
        }
                    
        return true;
    }

    /**
     * Move the bike on the simulation
     */
    public function apply() {
        try {
            if (!$this->validate()) {
                return;
            }
            $oldPosition = $this->_simulation->getBikePosition();
            $newPosition = [];
            switch ($this->_simulation->getBikeDirection()) {
                case Directions::NORTH:
                    // check if block north is accessible
                    $newPosition = [$oldPosition[0], $oldPosition[1]+1];
                    break;
                case Directions::EAST:
                    // check if block east is accessible
                    $newPosition = [$oldPosition[0]+1, $oldPosition[1]];
                    break;
                case Directions::SOUTH:
                    // check if block south is accessible
                    $newPosition = [$oldPosition[0], $oldPosition[1]-1];
                    break;
                case Directions::WEST:
                    // check if block west is accessible
                    $newPosition = [$oldPosition[0]-1, $oldPosition[1]];
                    break;
            }
            $this->_simulation->addDebug(sprintf(
                "Old Position: (%s,%s) - New Position: (%s, %s)",
                $oldPosition[0],
                $oldPosition[1],
                $newPosition[0],
                $newPosition[1],
                )
            );
            $this->_simulation->setBikePosition($newPosition);
        } catch (\Exception $e) {
            // handle failed command
        }
        
    }


}