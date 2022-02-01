<?php

namespace Nathaniel\BikeSimulator\Command;

use Nathaniel\BikeSimulator\Directions;

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
    private $_params;

    const NAME = 'PLACE';

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
        return 'PLACE <X>, <Y>, <Facing-Direction> - to place your bike somewhere on the grid';
    }

    /**
     * Get parameters for the function
     */
    public function getParams() {
        if (!$this->_params) {
            $namelessInputs = str_replace(self::NAME, '', $this->_input);
            $params = explode(',', $namelessInputs);
            $params = array_map('trim', $params);
            $this->_params = $params;
        }
        return $this->_params;
    }

    /**
     * Validate the command meets simulation requirements
     */
    public function validate() {
        if (count($this->getParams()) > 3) {
            return false;
            // throw new \Exception('Too many parameters');
        }
        [$xCoord, $yCoord, $direction] = $this->getParams();
        $isValid = true;

        switch ($direction) {
            case Directions::NORTH:
            case Directions::EAST:
            case Directions::SOUTH:
            case Directions::WEST:
                break;
            default:
                return false;
        }

        [$width, $length] = $this->_simulation->getSize();
        if ($xCoord < 0) {
            return false;
        }
        if ($xCoord > $width) {
            return false;
        }
        if ($yCoord < 0) {
            return false;
        } 
        if ($yCoord > $length) {
            return false;
        }
        return true;
    }

    /**
     * Move the bike on the simulation
     */
    public function apply(\Nathaniel\BikeSimulator\Bike $bike) {
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