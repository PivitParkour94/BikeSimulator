<?php

namespace Nathaniel\BikeSimulator;

/**
 * Simulate a bike on a grid
 */
class Simulation {

    private $isRunning;
    private $_twig;
    private $_width;
    private $_length;

    /**
     * Simluate the bike on a grid. Default 7 x 7
     */
    public function __construct(
        $width = 7,
        $length = 7
    ) {
        $this->isRunning = false;
        $this->_width = $width;
        $this->_length = $length;
    }

    /**
     * Run the simulation
     */
    public function run() {
        // $this->isRunning = true;
        while ($this->isRunning) {
            $message = "";
            foreach ($this->getInputs() as $input) {
                try {
                    $command = $this->getCommandFromInput($input);
                    $bike->apply($command);
                } catch (\Exception $e) {
                    $message = $e->getMessage();
                }
            }    
        }
        // $this->updateOutput($message);
    }

    private function getInputs() {
        return [];
    }

    /**
     * Build twig renderer
     */
    private function getTwigRenderer() {
        if (!$this->_twig) {
            $loader = new \Twig\Loader\FilesystemLoader('./templates');
            $twig = new \Twig\Environment($loader);
            $this->_twig = $twig;
        }
        return $this->_twig;
    }

    /**
     * Render the grid for the bike simulation
     */
    public function renderBoard() {
        return $this->getTwigRenderer()->render('board.html', [
            'width' => $this->_width, 
            'length' => $this->_length,
            'isDebug' => true
        ]);
    }

    /**
     * Reset the simulation
     */
    public function reset() {
        $this->isRunning = false;
    }

    /**
     * Debug the grid output
     */
    public function debugGrid() {
        return "Rendering board with width: " . $this->_width . ' and length: ' . $this->_length; 
    }

    /* COMMANDS */

    public function getCommandHelp() {
        return "<pre>Generate command options here</pre>";
        // return $this->getTwigRenderer()->render('command-help.html', [
        //     'width' => $this->_width, 
        //     'length' => $this->_length,
        //     'isDebug' => true
        // ]);
    }

    /**
     * Get initial command
     * TODO: load list of commands in a specific order
     */
    public function getInitialCommand() {
        return "PLACE 0, 0, " . Directions::NORTH . PHP_EOL;
    }

}