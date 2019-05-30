This Eclipse project was built and run using PHP 7.3.4 as part of the Apache web server
 in XAMPP. This project depends on having XAMPP 7.3.4, Composer 1.8.5, and Xdebug 2.71 PHP 7.3 VC15 TS (64 bit) installed.
 The IDE used is MyEclipse Profession 2017 from Genuitec along with Genuitec's CodeMix eclipse plug-in.
 Changed XAMPP configuration files are in project folder "XAMPP changed conf files".

The project contains the following runnable code:

tls\Elevator - Basic elevator simulator. Currently configured for a six floor building with four elevators. Two of the
	elevators (3 and 4) are also freight elevators with a front passenger door and a back freight door. Since only starting
	state elevator and floor data is set in BuildingElevatorController.setupTestState(), an elevator can sit idle when it
	reaches a floor for pickup or drop off and it has no further drop offs or new pickups of the correct type for that elevator. 
	An example of this is Elevator 1 which services the pickup going up on Floor 1. Since the passenger did not press an elevator floor
	button to go to and all other passenger pickups have been serviced by other elevators, Elevator 1 sits idle on Floor 1 after the 1st
	iteration. If Elevator 1, as part of iteration 2, had the passenger pressed an elevator floor button (like floor 3) to go to,
	Elevator 1 would have gone to that floor to drop off the passenger. 
	To run in browser: http://localhost/tls-php-portfolio


	