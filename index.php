<?php 
/**
 * Here is the demo code for a bike simulator
 */

require_once 'vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$simulation = new \Nathaniel\BikeSimulator\Simulation(7,7);

$simulation->run();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nathaniel - Bike Simulator</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="application">
        <div class="instructions">
            <p class="title">Welcome to the bike simulator 3000.</p>
            <span>In order to successfully simulate the excitement and fun of a bike, please input your commands starting with a 
                <pre><?php echo htmlentities($simulation->getInitialCommand()); ?></pre>
            </span>
                 <?php /**
                  * may have time to add click to start
                 or click which position you would like to start from on the grid below.</span>
                 */ ?>
        </div>
        <div class="board">
        <?php echo $simulation->renderBoard(); ?>
        </div>        
        <div class="toolbar">
            <div class="input">
                <!-- <h3>Inputs</h3> -->
                <form action="navigate.php">
                    <input type="hidden" name="security" value="ARBITRARY" />
                    <label class="title" for="commands">Enter your commands here</label><br>
                    <textarea name="commands"><?php echo $simulation->getInitialCommand(); ?></textarea>
                    <span class="help_commands"><?php echo $simulation->getCommandHelp(); ?></span>
                    <input type="submit">RUN</input>
                </form>
            </div>
            <div class="output">
                <!-- <h3>Output</h3> -->
                <span class="output">Please enter your commands to see the GPS Output</span>
            </div>
        </div>
    </div>
</body>
</html>

