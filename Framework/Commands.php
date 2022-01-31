<?php

namespace Nathaniel\BikeSimulator;

class AbstractCommand {

    /**
     * Get description for the command
     */
    public function getDescription() {
        return "Description for command";
    }

    /**
     * Get parameters for the function
     */
    public function getParams() {
        return [];
    }

}