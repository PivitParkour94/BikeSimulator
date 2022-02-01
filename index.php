<?php 
/**
 * Here is the demo code for a bike simulator
 */

require_once 'vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_clean();

$simulation = new \Nathaniel\BikeSimulator\Simulation(7,7);

$inputs = isset($_GET['commands']) ? (array)$_GET['commands'] : [];

$simulation->run($inputs);
$simulation->debug();
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
            <span>In order to successfully simulate the excitement and fun of a bike, please input your commands starting with
                <pre class="command"><?php echo htmlentities($simulation->getInitialCommand()); ?></pre>
            </span>
                 <?php /**
                  * may have time to add click to start
                 or click which position you would like to start from on the grid below.</span>
                 */ ?>
        </div>
        <div class="board">
            <div class="input">
                <!-- <h3>Inputs</h3> -->
                <form action="/">
                    <input type="hidden" name="security" value="ARBITRARY" />
                    <label class="title" for="commands">Enter your commands here</label><br>
                    <textarea name="commands" autofocus="true" rows="5"><?php echo $simulation->outputCommand(); ?></textarea>
                    <span class="help_commands"><?php echo $simulation->getCommandHelp(); ?></span>
                    <input type="submit" value="RUN"></input>
                </form>
            </div>
            <h3 class="title">Simulation Grid</h3>
            <?php echo $simulation->renderBoard(); ?>
        </div>
        <div class="output-box">
            <span class="output"><?php echo $simulation->getOutput(); ?></span>
        </div>        
    </div>
</body>
</html>

