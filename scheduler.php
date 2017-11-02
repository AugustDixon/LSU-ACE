<?php
/*
	PHP Scheduler
	This program performs time triggered actions on the SQL database.
*/


//Startup actions

$latch = false;
//Log in to SQL
while(!$latch){
	$mysqli = mysqli("localhost", "Scheduler", "system", "LSU-ACE");
	if(!($mysqli->connect_errno))
		$latch = true;
}

//Clear chat logs
$mysqli->query("DELETE * FROM Chatlog;");

//Clear Schedule
$mysqli->query("DELETE * FROM Schedule;");

//Prepare a string to select against
switch(date("l")){
	case "Monday":
		$day = "M....";
		break;
	case "Tuesday":
		$day = ".T...";
		break;
	case "Wednesday":
		$day = "..W..";
		break;
	case "Thursday":
		$day = "...T.";
		break;
	case "Friday":
		$day = "....F";
		break;
	default:
		$day = "00000";
}
//Select the new classes
		
//Create the schedule
		
//Perform actions already passed







//Looping script
while(true){
	//Look for next start time
	if(date("h:ia") == $nextStart){
		//Select all classes this time pertains to
		
		//Open session for those classes
		
		//Update those times to be 11:59pm
		
		//Find next start time
		
	}
	if(date("h:ia") == $nextEnd){
		//Select all classes this time pertains to
		
		//Close session for those classes
		
		//Delete those classes from schedule
		
		//Find next end time
		
	}
	
	
	
	
	
	
	//Nightly operations
	if(date("h:ia") == "12:01am" && $latch){
		//Previous day cleanup
		//Delete chat logs
		$mysqli->query("DELETE * FROM Chatlog;");
		
		//Empty the schedule
		$mysqli->query("DELETE * FROM Schedule;");
		
		//Next day setup
		//Prepare a string to select against
		switch(date("l")){
			case "Monday":
				$day = "M....";
				break;
			case "Tuesday":
				$day = ".T...";
				break;
			case "Wednesday":
				$day = "..W..";
				break;
			case "Thursday":
				$day = "...T.";
				break;
			case "Friday":
				$day = "....F";
				break;
			default:
				$day = "00000";
		}
		//Select the new classes
		$res = $mysqli->query("SELECT STimeA, ETimeA FROM Class WHERE DayA = '$day';");
		
		//Create the next day's schedule
		
		$latch = false;
	}	
	if(date("h:ia") == "12:02am")
		$latch = true;
}

?>