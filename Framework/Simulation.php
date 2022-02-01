<?php

namespace Nathaniel\BikeSimulator;

use Exception;

/**
 * Simulate a bike on a grid
 */
class Simulation {

    // const IS_DEBUGGING = false;
    const IS_DEBUGGING = true;

    private $_twig;
    private $_width;
    private $_length;
    private $_isBikePlaced;
    private $_inputs;
    private $_output;

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
        $this->_isBikePlaced = false;
        $this->_inputs = [];
        $this->_output = [];
    }

    /**
     * Set is bike placed
     */
    public function setIsBikePlaced($isBikePlaced) {
        $this->_isBikePlaced = $isBikePlaced;
        return $this;
    }

    /**
     * Run the simulation
     */
    public function run($input) {
        $this->processInputs($input);
        if (empty($this->getInputs())) {
            $this->addOutput("Please enter your commands to see the GPS Output", 'info');
            return;
        }
        $bike = new Bike();
        $this->setIsBikePlaced(false);
        foreach ($this->getInputs() as $input) {
            try {
                $command = $this->getCommandFromInput($input);
            } catch (\Exception $e) {
                // Failed to load command
                $this->addDebug('Input: ' . $input . ' - ' . $e->getMessage());
                $this->addError("Something went wrong. Please check your commands are correct");
                continue;
            }
            if (!$this->_isBikePlaced) {
                if (!$command instanceof \Nathaniel\BikeSimulator\Command\PlaceCommand) {
                    // The application should discard all commands until a valid PLACE command has been executed.
                    continue;
                }
            }
            try {
                $command->apply($bike);
                $this->addSimulationMessage("Performed " . htmlentities($input) . PHP_EOL);
            } catch (\Exception $e) {
                if (self::IS_DEBUGGING) {
                    $this->addSimulationMessage("Failed " . htmlentities($input) . ": " . $e->getMessage() . PHP_EOL);
                } else {
                    $this->addSimulationMessage("Failed " . htmlentities($input) . PHP_EOL);
                }
                continue;
            }
        }
    }

    /**
     * 
     */
    private function getCommandFromInput($input) {
        $this->addDebug("Generate command from input: ". $input);
        [$inputName, $params] = explode(' ', $input);
        $commandClass = '\\Nathaniel\\BikeSimulator\\Command\\' . ucwords(strtolower($inputName)) . 'Command';
        if (!class_exists($commandClass)) {
            throw new Exception("No command defined with name: " . $inputName);
        }
        $command = new $commandClass($this, $input);
        return $command;
    }

    /**
     * Process inputs for the simulation
     */
    private function processInputs($input) {
        if (is_string($input)) {
            $input = explode(PHP_EOL, $input);
        }
        if (!$input) {
            $input = [];
        }
        $this->_inputs = $input;
    }

    /**
     * Get inputs
     */
    private function getInputs() {
        if (!$this->_inputs) {
            $this->_inputs = [];
        }
        return $this->_inputs;
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
            'isDebug' => self::IS_DEBUGGING
        ]);
    }

    /**
     * Debug the grid output
     */
    public function debug() {
        $this->addDebug('Width: ' . $this->_width);
        $this->addDebug('Length: ' . $this->_length);
        $bikePlacedString = ($this->_isBikePlaced) ? 'true' : 'false';
        $this->addDebug('Is Bike Placed: ' . $bikePlacedString, 'debug');
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

    public function outputCommand() {
        if (!$this->_inputs) {
            return $this->getInitialCommand();
        }
        return implode(PHP_EOL, $this->_inputs);
    }

    public function addSimulationMessage($message) {
        $this->addOutput($message, 'simulate');
    }

    public function addDebug($message) {
        $this->addOutput($message, 'debug');
    }

    public function addError($message) {
        $this->addOutput($message, 'error');
    }

    public function addOutput($output, $style) {
        $this->_output[$style][] = htmlentities($output);
    }

    public function getOutput() {
        $output = '';
        foreach ($this->_output as $style => $messages) {
            switch ($style) {
                case 'simulate': 
                    $output .= '<pre class="output-log">';
                    foreach ($messages as $message) {
                        $output .= '<p>' . trim(htmlentities($message)) . '</p>' . PHP_EOL;
                    }
                    $output .= '</pre>';
                    break;
                case 'error': 
                    $output .= '<pre class="output-log-error">';
                    foreach ($messages as $message) {
                        $output .= '<p>' . trim(htmlentities($message)) . '</p>' . PHP_EOL;
                    }
                    $output .= '</pre>';
                    break;
                case 'debug': 
                    if (!self::IS_DEBUGGING) {
                        break;
                    }
                    $output .= '<pre class="output-debug">';
                    $output .= '<p>Debugging</p>';
                    foreach ($messages as $message) {
                        $output .= trim(htmlentities($message)) . PHP_EOL;
                    }
                    $output .= '</pre>';
                    break;
                default:
                    $output .= implode(PHP_EOL, $messages) . PHP_EOL;
                    break;
            }
        }
        return $output;
    }

}