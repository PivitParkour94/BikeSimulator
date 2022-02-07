<?php

namespace Nathaniel\BikeSimulator;

use Exception;
use Nathaniel\BikeSimulator\Command\PlaceCommand;
use Nathaniel\BikeSimulator\Command\TurnLeftCommand;

/**
 * Simulate a bike on a grid
 */
class Simulation {

    /**
     * @var \Twig\Environment
     */
    private $_twig;


    /**
     * @var bool
     */
    private $_isDebugging;

    /**
     * @var int
     */
    private $_width;
    /**
     * @var int
     */
    private $_length;

    /**
     * @var \Nathaniel\BikeSimulator\Bike
     */
    private $_bike;

    /**
     * array
     */
    private $_bikePosition;

    private $_bikeDirection;

    /**
     * @var bool
     */
    private $_isBikePlaced;
    
    /**
     * @var array
     */
    private $_inputs;
    /**
     * @var array
     */
    private $_output;



    /**
     * Simluate the bike on a grid. Default 7 x 7
     */
    public function __construct(
        $width = 7,
        $length = 7,
        $isDebugging = false
    ) {
        $this->_isDebugging = $isDebugging;
        $this->_width = $width;
        $this->_length = $length;
        $this->_bike = null;
        $this->_isBikePlaced = false;
        $this->_inputs = [];
        $this->_output = [];
    }

    public function getBike() {
        return $this->_bike;
    }

    public function setBike($bike) {
        $this->_bike = $bike;
        return $this;
    }

    public function getBikePosition() {
        return $this->_bikePosition;
    }

    public function setBikePosition($bikePosition) {
        $this->_bikePosition = $bikePosition;
        return $this;
    }

    public function getBikeDirection() {
        return $this->_bikeDirection;
    }

    public function setBikeDirection($direction) {
        // limit direction to N,S,E,W
        switch ($direction) {
            case Directions::NORTH:
            case Directions::EAST:
            case Directions::SOUTH:
            case Directions::WEST:
                break;
            default:
                // unsuported direction
                $direction = Directions::NORTH;
                break;
        }
        $this->_bikeDirection = $direction;
    }

    /**
     * Check if the bike is placed
     */
    public function getIsBikePlaced() {
        return $this->_isBikePlaced;
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
                    $this->addError("Please ensure you have called the PLACE command");
                    continue;
                }
            }
            try {
                $command->apply();
                // $this->addSimulationMessage("Performed " . htmlentities($input) . PHP_EOL);
            } catch (\Exception $e) {
                $this->addDebug("Failed " . htmlentities($input) . ": " . $e->getMessage() . PHP_EOL);
                $this->addError("Something went wrong. Please check your commands are correct");
                continue;
            }
        }
        $this->addSimulationMessage("Simulation Complete!");
    }

    /**
     * Get command from the input
     */
    private function getCommandFromInput($input) {
        $this->addDebug("Generate command from input: ". $input);
        $inputName = explode(' ', $input)[0];
        $parts = explode('_', $inputName);
        $commandClassName = '';
        foreach ($parts as $part) {
            $commandClassName .= ucwords(strtolower($part));
        }
        $commandClass = '\\Nathaniel\\BikeSimulator\\Command\\' . $commandClassName . 'Command';
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
        $this->addDebug('Processing Inputs: ' . json_encode($input));
        $comamnds = [];
        if (!$input) {
            return [];
        }
        if (is_string($input)) {
            $commands = explode(PHP_EOL, $input);
        }
        $inputs = [];
        foreach ($commands as $command) {
            $trimmedCommand = trim($command);
            if (ctype_space($trimmedCommand)) {
                continue;
            }
            if (!$trimmedCommand) {
                continue;
            }
            if (empty($trimmedCommand)) {
                continue;
            }
            $inputs[] = trim($trimmedCommand);
        }
        $this->_inputs = $inputs;
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
        $templateData = [
            'width' => $this->_width, 
            'length' => $this->_length,
            'isDebug' => $this->_isDebugging
        ];
        if ($this->getIsBikePlaced()) {
            $templateData['bike'] = [
                // have to invert x and y due to table i and j inverted to and y of simulation
                'x' => ($this->_length - $this->getBikePosition()[1]),
                // 'y' => ($this->_length -$this->getBikePosition()[1]),
                'y' => $this->getBikePosition()[0],
                'direction' => $this->getBikeDirection()
            ];
        }
        return $this->getTwigRenderer()->render(
            'board.html', 
            $templateData
        );
    }

    /**
     * Get Allowed Dimensions for the simulation
     */
    public function getSize() {
        return [$this->_width, $this->_length];
    }

    /* COMMANDS */

    /**
     * Get Comamnds help
     */
    public function getCommandHelp() {
        return $this->getTwigRenderer()->render('command-help.html', [
            'commands' => $this->getAvailableComamnds(), 
            'isDebug' => false
        ]);
    }

    /**
     * Get available commands
     */
    public function getAvailableComamnds() {
        $commands = [];
        // $comamnds[] = [
        //     'name' => TurnLeftCommand::NAME,
        //     'usage' => TurnLeftCommand::getUsage(),
        //     'description' => TurnLeftCommand::getDescription()
        // ];

        // return $commands;
        $this->addDebug('Determining Available commands...');
        foreach (get_declared_classes() as $className) {
            // $this->addDebug('class implements: ' . json_encode(class_implements($className)));
            if (!in_array('Nathaniel\\BikeSimulator\\ComamndInterface', class_implements($className))) {
                continue;
            }
            $comamnds[] = [
                'name' => constant("$className::NAME"),
                'usage' => call_user_func($className .'::getUsage'),
                'description' => call_user_func($className .'::getDescription')
            ];
            // $commandNamespace = __NAMESPACE__ . '\\Command\\';
            // if (strpos($className, $commandNamespace) !== false) {
            //     $this->addDebug('Available command: ' . constant("$className::NAME"));
            //     $comamnds[] = [
            //         'name' => constant("$className::NAME"),
            //         'usage' => call_user_func($className .'::getUsage'),
            //         'description' => call_user_func($className .'::getDescription')
            //     ];
            // }

        }
        $this->addDebug('Available commands: ' . json_encode($commands));
        if (!$comamnds) {
            return [];
        }
        return $comamnds;
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


     /* LOGGING */

     /**
     * Debug the grid output
     */
    public function debug() {
        $this->addDebug('Width: ' . $this->_width);
        $this->addDebug('Length: ' . $this->_length);
        $bikePlacedString = ($this->_isBikePlaced) ? 'true' : 'false';
        $this->addDebug('Is Bike Placed: ' . $bikePlacedString, 'debug');
    }
   
    /**
     * Get simulation messages
     */
    public function getSimulationMessages() {
        return $this->_output['simulate'];
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
        if (is_array($output)) {
            $output = json_encode($output);
        }
        $this->_output[$style][] = htmlentities($output);
    }

    public function getDebugMessages() {
        if (!$this->_isDebugging) {
            return '';
        }
        $output = '';
        $output .= '<p>Debugging</p>';
        foreach ($this->_output as $style => $messages) {
            foreach ($messages as $message) {
                $output .= trim(htmlentities($message)) . PHP_EOL;
            }
        }
        return $output;
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
                    // ignore debug messages in output
                    break;
                default:
                    $output .= implode(PHP_EOL, $messages) . PHP_EOL;
                    break;
            }
        }
        return $output;
    }

}