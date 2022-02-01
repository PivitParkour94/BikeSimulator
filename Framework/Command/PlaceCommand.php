<?php

namespace Nathaniel\BikeSimulator\Command;

/**
 * Command used to place the bike somewhere on the grid
 */
class PlaceCommand // implements ComamndInterface {
{
    /**
     * @var \Nathaniel\BikeSimulator\Simulation
     */
    private $_simulation;
    private $_input;

    const NAME = 'PLACE';

    /**
     * Setup command with unvalidated input
     */
    public function __construct($simulation, $input) {
        $this->_simulation = $simulation;
        $this->_input = $input;
    }

    /**
     * Get description for the command
     */
    public function getDescription() {
        return 'PLACE <X>, <Y>, <Facing-Direction> - to place your bike somewhere on the grid';
    }

    /**
     * Get parameters for the function
     */
    public function getParams() {
        $params = explode(',', $this->_input);
        $filteredParams = array_filter($params, function ($param) {
            if ($param == self::NAME) {
                return false;
            } 
        });
        $this->_params = $filteredParams;
    }

    /**
     * Validate the command meets simulation requirements
     */
    public function validate() {
        // TODO: check if position is within the simulation grid
    }

    /**
     * Move the bike on the simulation
     */
    public function apply(\Nathaniel\BikeSimulator\Bike $bike) {
        $oldPosition = $bike->getPosition();
        if (!$this->validate()) {
            // handle failed command
        }
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
        
    }


}