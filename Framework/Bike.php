<?php

namespace Nathaniel\BikeSimulator;

/**
 * Instance of the bike and its properties during a simulation
 */
class Bike {

    private $_direction;

    /**
     * Setup bike
     */
    public function __construct(
        $direction = Directions::NORTH
    ) {
        $this->_direction = $direction;
    }

    /**
     * Get current direction
     */
    public function getDirection() {
        return $this->_direction;
    }

    /**
     * Set current direction
     */
    public function setDirection($direction) {
        switch ($direction) {
            case Directions::NORTH:
            case Directions::EAST:
            case Directions::SOUTH:
            case Directions::WEST:
                $this->_direction = $direction;
            default:
                // unsuported direction
                $this->_direction = Directions::NORTH;
        }
    }

    /**
     * Get bike data
     */
    public function getData() {
        return [
            'direction' => $this->getDirection()
        ];
    }

}
