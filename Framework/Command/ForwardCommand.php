<?php

namespace Nathaniel\BikeSimulator\Command;

use Nathaniel\BikeSimulator\Directions;

/**
 * Command used to place the bike somewhere on the grid
 */
class ForwardCommand { // implements ComamndInterface {

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
    public function getDescription() {
        return 'FORWARD - Move the bike one block in the direction it is facing';
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

        return true;
    }

    /**
     * Move the bike on the simulation
     */
    public function apply(\Nathaniel\BikeSimulator\Bike $bike) {
        $this->_bike = $bike;
        $oldPosition = $bike->getPosition();
        try {
            $this->validate();
            $this->_simulation->setIsBikePlaced(true);
            $this->_simulation->addDebug(sprintf(
                "Moving bike from (%s,%s) to (%s,%s) facing %s",
                $oldPosition[0],
                $oldPosition[1],
                $bike->getPosition()[0],
                $bike->getPosition()[1],
                $bike->getDirection()
                )
            );
        } catch (\Exception $e) {
            // handle failed command
        }
        
    }


}