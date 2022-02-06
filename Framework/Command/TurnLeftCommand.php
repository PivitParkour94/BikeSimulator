<?php

namespace Nathaniel\BikeSimulator\Command;

use Nathaniel\BikeSimulator\Directions;

/**
 * Command used to turn bike to the left
 */
class TurnLeftCommand { // implements ComamndInterface {

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

    const NAME = 'TURN_LEFT';

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
    public function getDescription() {
        return 'TURN_LEFT - Rotate the bike anticlockwise without changing its position on the grid.';
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
        }
                    
        return true;
    }

    /**
     * Turn bike left
     */
    public function apply() {
        try {
            if (!$this->validate()) {
                return;
            }
            $oldDirection = $this->_simulation->getBikeDirection();
            switch ($oldDirection) {
                case Directions::NORTH:
                    $newDirection = Directions::WEST;
                    break;
                case Directions::EAST:
                    $newDirection = Directions::NORTH;
                    break;
                case Directions::SOUTH:
                    $newDirection = Directions::EAST;
                    break;
                case Directions::WEST:
                    $newDirection = Directions::SOUTH;
                    break;
            }
            $this->_simulation->addDebug(sprintf(
                "Old Direction: %s - New Direction: %s",
                $oldDirection,
                $newDirection
                )
            );
            $this->_simulation->setBikeDirection($newDirection);
        } catch (\Exception $e) {
            // handle failed command
        }
        
    }


}