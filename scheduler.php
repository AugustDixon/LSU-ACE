<?php
/*
	PHP Scheduler
	This program performs time triggered actions on the SQL database.
*/

//Functions
//+15 minutes
function endTime($etime){
	$minute = substr($etime, 3, 2);
	if($minute > 44){
		$minute -= 45;
		$etime = upHour($etime);
	}
	else
		$minute += 15;
	if(strlen("$minute") == 1)
		$minute = "0$minute";
	return substr($etime, 0, 3) . "$minute" . substr($etime, 5, 2);
}
//-15 minutes
function startTime($stime){
	$minute = substr($stime, 3, 2);
	if($minute < 15){
		$minute += 45;
		$stime = downHour($stime);
	}
	else
		$minute -= 15;
	if(strlen("$minute") == 1)
		$minute = "0$minute";
	return substr($stime, 0, 3) . "$minute" . substr($stime, 5, 2);
}
//Up Hour
function upHour($etime){
	$Hour = substr($etime, 0, 2);
	if($Hour == "11"){
		return "12:00pm";
	}
	else if($Hour == "12"){
		return "01:00pm";
	}
	else{
		$Hour += 1;
		if(strlen("$Hour") == 1)
			$Hour = "0$Hour";
		return "$Hour" . ":00" . substr($etime, 5, 2);
	}
}
//Down Hour
function downHour($stime){
	$Hour = substr($stime, 0, 2);
	if($Hour == "01"){
		return "12:00pm";
	}
	else if($Hour == "12"){
		return "01:00am";
	}
	else{
		$Hour -= 1;
		if(strlen("$Hour") == 1)
			$Hour = "0$Hour";
		return "$Hour" . ":00" . substr($etime, 5, 2);
	}
}
//Compare Times
function compare($TimeA, $TimeB){
	if(greaterHour($TimeA, $TimeB))
		return 1;
	else if(equalHour($TimeA, $TimeB)){
		if(substr($TimeA, 3, 2) > substr($TimeB, 3, 2))
			return 1;
		else if(substr($TimeA, 3, 2) == substr($TimeB, 3, 2))
			return 0;
		else
			return -1;
	}
	else
		return -1;
}
function greaterHour($TimeA, $TimeB){
	$HourA = substr($TimeA, 0, 2);
	$HourB = substr($TimeB, 0, 2);
	if($HourA == "12")
		$HourA = "00";
	if($HourB == "12")
		$HourB = "00";
	if(substr($TimeA, 5, 1) == "p")
		$HourA = "1" . $HourA;
	if(substr($TimeB, 5, 1) == "p")
		$HourB = "1" . $HourB;
	return $HourA > $HourB;
}
function equalHour($TimeA, $TimeB){
	return (substr($TimeA, 0, 2) == substr($TimeB, 0, 2)) && (substr($TimeA, 5, 1) == substr($TimeB, 5, 1));
}







//Startup actions

date_default_timezone_set("America/Chicago");
$Start = [];
$End = [];
$latch = true;
//Log in to SQL
while($latch){
	$mysqli = new mysqli("localhost", "Scheduler", "system", "LSU-ACE");
	if($mysqli->connect_errno)
		unset($mysqli);
	else
		$latch = false;
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
$res = $mysqli->query("SELECT Cid, STimeA, ETimeA FROM Class WHERE DayA REGEXP '$day';");
for($i = 0; $i < $res->num_rows; $i++){
	$res->data_seek($i);
	$result = $res->fetch_assoc();
	$Cid = $result['Cid'];
	$STimeA = $result['STimeA'];
	$ETimeA = $result['ETimeA'];
	$STimeA = startTime($STimeA);
	$ETimeA = endTime($ETimeA);
	$Date = date("m/d/y");
	
	$mysqli->query("INSERT INTO Schedule (Cid, STime, ETime) VALUES ('$Cid', '$STimeA', '$ETimeA');");
	$mysqli->query("INSERT INTO Session (Cid, Date, InSession, Hidden) VALUES ('$Cid', '$Date', 0, 1);");
	if(!(in_array($STimeA, $Start)))
		$Start[] = $STimeA;
	if(!(in_array($ETimeA, $End)))
		$End[] = $ETimeA;
}
$res = $mysqli->query("SELECT Cid, STimeB, ETimeB FROM Class WHERE DayB REGEXP '$day';");
for($i = 0; $i < $res->num_rows; $i++){
	$res->data_seek($i);
	$result = $res->fetch_assoc();
	$Cid = $result['Cid'];
	$STimeB = $result['STimeB'];
	$ETimeB = $result['ETimeB'];
	$STimeB = startTime($STimeB);
	$ETimeB = endTime($ETimeB);
	$Date = date("m/d/y");
	
	$mysqli->query("INSERT INTO Schedule (Cid, STime, ETime) VALUES ('$Cid', '$STimeB', '$ETimeB');");
	$mysqli->query("INSERT INTO Session (Cid, Date, InSession, Hidden) VALUES ('$Cid', '$Date', 0, 1);");
	if(!(in_array($STimeB, $Start)))
		$Start[] = $STimeB;
	if(!(in_array($ETimeB, $End)))
		$End[] = $ETimeB;
}
usort($Start, (function($a, $b) {	return compare($b, $a); }));
usort($End, (function($a, $b) {	return compare($b, $a); }));
$nextStart = array_pop($Start);
$nextEnd = array_pop($End);
//Perform actions already passed
while(!$latch){
	$latch = true;
	if(compare(date("h:ia"), $nextStart) >= 0){
		//Select all classes this time pertains to
		$res = $mysqli->query("SELECT Cid FROM Schedule WHERE STime = '$nextStart';");
		
		//Open session for those classes
		for($i = 0; $i < $res->num_rows; $i++){
			$res->data_seek($i);
			$result = $res->fetch_assoc();
			$Cid = $result['Cid'];
			$mysqli->query("UPDATE Session SET InSession = 1 WHERE Cid = '$Cid';");
		}
		
		//Find next start time
		if(empty($Start))
			$nextStart = "00";
		else
			$nextStart = array_pop($Start);
		$latch = false;
	}
	if(compare(date("h:ia"), $nextEnd) >= 0){
		//Select all classes this time pertains to
		$res = $mysqli->query("SELECT Cid FROM Schedule WHERE STime = '$nextStart';");
		
		//Close session for those classes
		for($i = 0; $i < $res->num_rows; $i++){
			$res->data_seek($i);
			$result = $res->fetch_assoc();
			$Cid = $result['Cid'];
			$mysqli->query("UPDATE Session SET InSession = 0, Hidden = 0 WHERE Cid = '$Cid';");
		}
		
		//Find next end time
		if(empty($End))
			$nextEnd = "00";
		else
			$nextEnd = array_pop($End);
		$latch = false;
	}
}









//Looping script
while(true){
	//Look for next start time
	if(date("h:ia") == $nextStart){
		//Select all classes this time pertains to
		$res = $mysqli->query("SELECT Cid FROM Schedule WHERE STime = '$nextStart';");
		
		//Open session for those classes
		for($i = 0; $i < $res->num_rows; $i++){
			$res->data_seek($i);
			$result = $res->fetch_assoc();
			$Cid = $result['Cid'];
			$mysqli->query("UPDATE Session SET InSession = 1 WHERE Cid = '$Cid';");
		}
		
		//Find next start time
		if(empty($Start))
			$nextStart = "00";
		else
			$nextStart = array_pop($Start);
	}
	if(date("h:ia") == $nextEnd){
		//Select all classes this time pertains to
		$res = $mysqli->query("SELECT Cid FROM Schedule WHERE STime = '$nextStart';");
		
		//Close session for those classes
		for($i = 0; $i < $res->num_rows; $i++){
			$res->data_seek($i);
			$result = $res->fetch_assoc();
			$Cid = $result['Cid'];
			$mysqli->query("UPDATE Session SET InSession = 0, Hidden = 0 WHERE Cid = '$Cid';");
		}
		
		//Find next end time
		if(empty($End))
			$nextEnd = "00";
		else
			$nextEnd = array_pop($End);
	}
	
	
	
	
	
	
	//Nightly operations
	if(date("h:ia") == "12:01am" && $latch){
		//Previous day cleanup
		//Delete chat logs
		$mysqli->query("DELETE * FROM Chatlog;");
		
		//Empty the schedule
		$mysqli->query("DELETE * FROM Schedule;");
		
		unset($Start);
		unset($End);
		$Start = [];
		$End = [];
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
		$res = $mysqli->query("SELECT Cid, STimeA, ETimeA FROM Class WHERE DayA REGEXP '$day';");
		for($i = 0; $i < $res->num_rows; $i++){
			$res->data_seek($i);
			$result = $res->fetch_assoc();
			$Cid = $result['Cid'];
			$STimeA = $result['STimeA'];
			$ETimeA = $result['ETimeA'];
			$STimeA = startTime($STimeA);
			$ETimeA = endTime($ETimeA);
			$Date = date("m/d/y");
			
			$mysqli->query("INSERT INTO Schedule (Cid, STime, ETime) VALUES ('$Cid', '$STimeA', '$ETimeA');");
			$mysqli->query("INSERT INTO Session (Cid, Date, InSession, Hidden) VALUES ('$Cid', '$Date', 0, 1);");
			if(!(in_array($STimeA, $Start)))
				$Start[] = $STimeA;
			if(!(in_array($ETimeA, $End)))
				$End[] = $ETimeA;
		}
		$res = $mysqli->query("SELECT Cid, STimeB, ETimeB FROM Class WHERE DayB REGEXP '$day';");
		for($i = 0; $i < $res->num_rows; $i++){
			$res->data_seek($i);
			$result = $res->fetch_assoc();
			$Cid = $result['Cid'];
			$STimeB = $result['STimeB'];
			$ETimeB = $result['ETimeB'];
			$STimeB = startTime($STimeB);
			$ETimeB = endTime($ETimeB);
			$Date = date("m/d/y");
			
			$mysqli->query("INSERT INTO Schedule (Cid, STime, ETime) VALUES ('$Cid', '$STimeB', '$ETimeB');");
			$mysqli->query("INSERT INTO Session (Cid, Date, InSession, Hidden) VALUES ('$Cid', '$Date', 0, 1);");
			if(!(in_array($STimeB, $Start)))
				$Start[] = $STimeB;
			if(!(in_array($ETimeB, $End)))
				$End[] = $ETimeB;
		}
		usort($Start, (function($a, $b) {	return compare($b, $a); }));
		usort($End, (function($a, $b) {	return compare($b, $a); }));
		$nextStart = array_pop($Start);
		$nextEnd = array_pop($End);
		$latch = false;
	}	
	if(date("h:ia") == "12:02am")
		$latch = true;
}

?>