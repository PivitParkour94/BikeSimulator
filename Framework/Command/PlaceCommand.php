<?php

namespace Nathaniel\BikeSimulator\Command;
use Nathaniel\BikeSimulator\Directions;

/**
 * Command used to place the bike somewhere on the grid
 */
class PlaceCommand //implements \Nathaniel\BikeSimulator\Command\ComamndInterface {
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
    static public function getDescription() {
        return 'PLACE <X>, <Y>, <Facing-Direction> - to place your bike somewhere on the grid';
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
    public function apply() {
        // die('Placing bike: ' . json_encode($this->getParams()));
        try {
            if (!$this->validate()) {
                return;
            };
            $this->_placeBike();
        } catch (\Exception $e) {
            // handle failed command
            $this->_simulation->addDebug($e->getMessage());
            $this->_simulation->addError("Failed to place Bike");
        }
        
    }

    /**
     * Place the bike
     */
    private function _placeBike() {
        $oldPosition = $this->_simulation->getBikePosition();
        if (!$oldPosition) {
            $oldPosition = [0,0];
        }
        $this->_simulation->addDebug(sprintf('Old Position: (%s,%s)', $oldPosition[0], $oldPosition[1]));
        $this->_simulation->addDebug(sprintf(
            "Moving bike from (%s,%s) to (%s,%s) facing %s",
            $oldPosition[0],
            $oldPosition[1],
            $this->getParams()[0],
            $this->getParams()[1],
            $this->getParams()[2],
            )
        );
        $this->_simulation->setIsBikePlaced(true);
        $this->_simulation->setBikePosition([$this->getParams()[0],$this->getParams()[1]]);
        $this->_simulation->setBikeDirection($this->getParams()[2]);
        $this->_simulation->addDebug(
            sprintf(
                'New Bike Position: (%s,%s)', 
                    $this->_simulation->getBikePosition()[0], 
                    $this->_simulation->getBikePosition()[1]
            )
        );

    }


}