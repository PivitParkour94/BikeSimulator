A breakdown of the design choices made to build this simulator.

1. Composer was used to easily pull in project requirements (PHPUnit).
2. Decided against a frontend JS framework as the task was specifically for PHP development
3. Had to find a nice way to inject Server Side output into the Frontend
4. Decided on Twig for building the frontend grid as it could easily be changed from a 7 x 7 grid to something else if needed later
5. Simulation needed to parse the input as a string and build an array of commands, that could then be tested for whether or not the command would lead to an invalid state (ignore if bike tried going outside the grid)
6. Added debugging for grid output to determine why grid was not rendering correctly
7. Made a Bike class to keep track of all bike related attributes (x, y and direction facing)
8. Decided to restrict the X, Y movement outside the Bike class, because the information is not relevant for Bike, but rather Simulation
9. Restricted the Direction directly in the Bike class and if an invalid direction is given, set a fallback of NORTH
10. Decided against a reset button, as PHP application renders from the page reload (no persistent data)
11. Simulation has is bike placed as a public method so that the place command can update the state, which allows for all commands to be ignored until a place bike command
