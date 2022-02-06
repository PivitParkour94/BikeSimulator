<?php

namespace Nathaniel\BikeSimulator\Command;

use Nathaniel\BikeSimulator\Directions;

/**
 * Command used to report the bike's GPS Coordinates
 */
class GpsReportCommand // implements ComamndInterface {
{
    /**
     * @var \Nathaniel\BikeSimulator\Simulation
     */
    private $_simulation;
    private $_input;
    private $_params;

    const NAME = 'GPS_REPORT';

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
        return 'GPS_REPORT - will output the bike\'s position and facing in the following format:
            (<X>, <Y>), <Facing-direction>';
    }

    /**
     * Get parameters for the function
     */
    public function getParams() {
        if ($this->_params) {
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
        if (count($this->getParams()) > 1) {
            return false;
            // throw new \Exception('Too many parameters');
        }

        return $this->_simulation->getIsBikePlaced();
    }

    /**
     * Move the bike on the simulation
     */
    public function apply() {
        if (!$this->validate()) {
            throw new \Exception("Failed to run GPS_REPORT");
        }
        $output = sprintf('(%s,%s), %s', 
            $this->_simulation->getBikePosition()[0],
            $this->_simulation->getBikePosition()[1],
            $this->_simulation->getBikeDirection(),            
        );
        $this->_simulation->addSimulationMessage('GPS Output: ' . $output);
        return $output;
    }


}