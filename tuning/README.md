Excel spreadsheet useful for simulating a simple PID Loop and finding gain values quicker than running real-world tests. It assumes active heating of a mass of water, and passive cooling.

This file is provided as a starting point, with the equations for heating and cooling shown as an example. You will have to adapt it to your usage.

Usage:
Start by collecting some experimental data for your setup. Start with a cold system, and run the heater at maximum power until you reach the maximum operating temp or a bit higher. Then, shut off the heater and allow it to passively cool back to ambient. Collect data at regular intervals and replace your data in the spreadsheets. 

>You might be tempted to insulate your water container, but since we can only cool passively any insulation will limit how fast the algorithm can react to over-temp. Therefore I recommend not using any techniques to stop heat loss such as insulation or putting a lid on the container. Instead we will rely on the controller to find the sweet spot where it puts in exactly as much heat as is being lost, and thus trade a few extra watts consumed during operation for faster reaction times.

Using the data you collected, find an equation for the **rate** of heat loss as a function of temperature (on the cooling sheet). Make the experimental and theoretical lines match pretty closely. The closer the fit, the better your simulated gains will match reality, but remember this is just a starting point so there's no need to spend hours on this.

Input your cooling equation into the simulation sheet.

At this point I recommend checking that your simulation is able to accurately simulate heating at full power before going any further. Do this by replacing the heating data you collected in the heating tab, and setting the simulation setpoint somewhere around the maximum temperature you achieved during the experiment. The simulated and experimental lines should be a close match. Adjust the c.m value until it is.

At this point you should have a reasonably accurate model of your system, and you can simulate many different PID gains quickly, and thereby find acceptable values by trial and error (or goal seek) rather than having to run real-world tests which can take 30-60 minutes each.

I found the gains found on this sheet provide a very useful starting point for real-world testing, and ended up being very close to the final values I chose.
