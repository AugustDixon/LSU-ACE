<?php
/*
	Function Script: profile/functions/addClass.php
	This script either adds a new class to a student's schedule. The script should first check whether or not the class is
	already a part of the database. Classes are the same if all Dept, Num, Sect, DayA, STimeA, ETimeA, DayB, STimeB, and ETimeB 
	are the same. If it is set Confirmed for that class to true (Only if the Student isn't already taking the class). If it is not, 
	create a new class with confirmed set to false. Cid will be automatically generated so nothing should be sent. A new entry in 
	the Taking table must be made for the individual student, unless the student is already taking the class.
	
	Inputs
		'Dept' - Must be between 1 and 5 characters
		'Num' - Must be between 1 and 5 characters
		'Sect' - Must be between 1 and 3 characters
		'DayA' - Must be 5 characters
		'STimeA' - Must be 6 or 7 characters
		'ETimeA' - Must be 6 or 7 characters
		'DayB' - Must be 5 characters
		'STimeB' - Must be 6 or 7 characters
		'ETimeB' - Must be 6 or 7 characters
		'HName'
		'HPhone'
		'HLSUID'
		All three DayB, STimeB, ETimeB may be null, but only if they are all null.
	Outputs/Actions
		0 - Script Failure
		1 - Success
		2 - Idle timer expiration
		3 - Department Constraint Error
		4 - Number Constraint Error
		5 - Section Constraint Error
		6 - DayA Constraint Error
		7 - Start Time A Constraint Error
		8 - End Time A Constraint Error
		9 - DayB Constraint Error
		10 - Start Time B Constraint Error
		11 - End Time B Constraint Error
		12 - Student already taking class
*/

//Check if a start and end time pair is valid
function invalid($STime, $ETime){
	$STime = convert($STime);
	$ETime = convert($ETime);
	return $STime > $ETime;
}

//Convert a Time for use in previous function
function convert($Time){
	$Time = substr($Time, 0, 2);
	if($Time < 7)
		$Time = '2' . $Time;
	return (int) $Time;
}



//Start/Load session
session_start();

//Connect to the MySQL server
$mysqli = new mysqli("localhost", "Scheduler", "system", "LSU-ACE");
if($mysqli->connect_errno){
	echo "0";
	exit();
}

//check idle time
if(($_SESSION['idle'] + 600) < time()){
	unset($_SESSION['username']);
	unset($_SESSION['idle']);
	echo "2";
	exit();
}

$_SESSION['idle'] = time();

//inputs from add.php
$dept = $_POST['Dept'];
$num = $_POST['Num'];
$sect = $_POST['Sect'];
$daya = $_POST['DayA'];
$stimea = $_POST['STimeA'];
$etimea = $_POST['ETimeA'];
$dayb = $_POST['DayB'];
$stimeb = $_POST['STimeB'];
$etimeb = $_POST['ETimeB'];
$hname = $_POST['HName'];
$hphone = $_POST['HPhone'];
$hlsuid = $_POST['HLSUID'];
$user = $_SESSION['username'];

//check dept constraints
if(strlen($dept) > 5 || strlen($dept) < 1){
   echo "3";
   exit();
}
//check num constraints
if(strlen($num) > 5 || strlen($num) < 1){
   echo "4";
   exit();
}
//check sect constraints
if(strlen($sect) > 3 || strlen($sect) < 1){
   echo "5";
   exit();
}
if(substr($sect, 0, 1) == "0"){
	$sect = substr($sect, 1, 2);
	if(substr($sect, 0, 1) == "0")
		$sect = substr($sect, 1, 1);
}
//check dayA constraints
if(strlen($daya) != 5){
   echo "6";
   exit();
}
//check STimeA constraints
if(strlen($stimea) < 6 || strlen($stimea) > 7){
   echo "7";
   exit();
}
//check ETimeA constraints
if(strlen($etimea) < 6 || strlen($etimea) > 7 || invalid($stimea, $etimea)){
   echo "8";
   exit();
}
//check dayB constraints
if(strlen($dayb) != 5){
   echo "9";
   exit();
}
//check STimeB constraints
if(strlen($stimeb) < 6 || strlen($stimeb) > 7){
   echo "10";
   exit();
}
//check ETimeB constraints
if(strlen($etimeb) < 6 || strlen($etimeb) > 7 || invalid($stimea, $etimea)){
   echo "11";
   exit();
}





//check if the class is already in the database
$res = $mysqli->query("SELECT Cid FROM Class WHERE Dept = '$dept' AND Num = '$num' AND Sect = '$sect' AND DayA = '$daya' AND STimeA = '$stimea' AND ETimeA = '$etimea' AND DayB = '$dayb' AND STimeB = '$stimeb' AND ETimeB = '$etimeb';");

if($res->num_rows > 0){
	$result = $res->fetch_assoc();
	$Cid = $result['Cid'];
	$res = $mysqli->query("SELECT Sid FROM Taking WHERE Sid = '$user' AND Cid = '$Cid';");
	if($res->num_rows > 0){
		echo "12";
		exit();
	}
	if(!($mysqli->query("INSERT INTO Taking (Sid, Cid, HideName, HidePhone, HideID) VALUES ('$user', '$Cid', $hname, $hphone, $hlsuid);"))){
		echo "0";
		exit();
	}
	if($mysqli->query("UPDATE Class SET Confirmed = 1 WHERE Cid = '$Cid';"))
		echo "1";
	else
		echo "0";
	exit();
}
else{   
	$sql = "INSERT INTO Class (Dept, Num, Sect, Confirmed, DayA, STimeA, ETimeA, DayB, STimeB, ETimeB) VALUES ('$dept', '$num', '$sect', 0, '$daya', '$stimea', '$etimea', '$dayb', '$stimeb', '$etimeb');";
	if(!($mysqli->query($sql))){
		echo "0";
		exit();
	} 
	$Cid = $mysqli->insert_id;
	if(!($mysqli->query("INSERT INTO Instructor (Cid, Name, Email, Office, Hours) VALUES ('$Cid', '', '', '', '');"))){
		echo "0";
		exit();
	} 
	if(!($mysqli->query("INSERT INTO TA (Cid, Name, Email) VALUES ('$Cid', '', '');"))){
		echo "0";
		exit();
	}
	if($mysqli->query("INSERT INTO Taking (Sid, Cid, HideName, HidePhone, HideID) VALUES ('$user', '$Cid', $hname, $hphone, $hlsuid);"))
		echo "1";
	else
		echo "0";
	exit();
}

?>