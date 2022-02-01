<?php

namespace Nathaniel\BikeSimulator;

/**
 * Instance of the bike and its properties during a simulation
 */
class Bike {

    private $_x;
    private $_y;
    private $_direction;

    /**
     * Setup bike
     */
    public function __construct(
        $initialX = 0, 
        $initialY = 0, 
        $direction = Directions::NORTH
    ) {
        $this->_x = $initialX;
        $this->_y = $initialY;
        $this->_direction = $direction;
    }

    /**
     * Get current position of bike
     */
    public function getPosition() {
        return [$this->_x, $this->_y];
    }

    /**
     * Set current position of bike
     */
    public function setPosition($x, $y) {
        // restrict x & y coords in the simulation
        $this->_x = $x;
        $this->_y = $y;
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


}
