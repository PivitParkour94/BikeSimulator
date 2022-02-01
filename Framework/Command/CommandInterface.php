<?php

namespace Nathaniel\BikeSimulator\Command;

interface ComamndInterface {

    /**
     * Get description for the command
     */
    public function getDescription();

    /**
     * Get parameters for the function
     */
    public function getParams();

    /**
     * Validate the command meets simulation requirements
     */
    public function validate();

    /**
     * Apply the command 
     */
    public function apply();


}