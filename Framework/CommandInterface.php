<?php

namespace Nathaniel\BikeSimulator;

/**
 * TODO: Issue reading interface
 */
interface ComamndInterface {

    /**
     * Get description for the command
     */
    static public function getDescription();

    /**
     * Get Usage for the command
     */
    static public function getUsage();

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