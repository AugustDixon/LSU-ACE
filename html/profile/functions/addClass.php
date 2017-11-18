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
//Start/Load session
session_start();

//Connect to the MySQL server
$mysqli = new mysqli("localhost", "Scheduler", "system", "LSU-ACE");
if($mysqli->connect_errno){
	echo "0";
	exit();
}
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

//need to find what constraints need to be added.

//need to find what values go in Cid, Title, Classroom, Confirmed.
$sql = "INSERT Class (Cid, Dept, Num, Sect, Title, Classroom, Confirmed, DayA, STimeA, ETimeA, DayB, STimeB, ETimeB)
VALUES ('', '$dept', '$num', '$sect', '', '', '', '$daya', '$stimea', '$etimea', '$dayb', '$stimeb', '$etimeb')";
if ($mysqli->query($sql) === TRUE) {
    echo "1";
} else {
    echo "0";
    exit();
}
$mysqli->close();

?>