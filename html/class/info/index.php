<?php
/*
	Page Generate Script: class/info/index.php
	This script generates the class info page.
	
	HTTP Inputs:
		'ID' - Cid
		
	Page Features:
		"Back" button - Hyperlinks to class/index.php
		"Class Roster" button - Hyperlinks to class/info/roster.php
		The following info for the class:
			Department
			Number
			Section
			Class Title
			Classroom
			Days A
			Start to End Time A
			Days B
			Start to End Time B
		"Edit Class Info" button - Hyperlinks to class/info/edit.php
		The following info for the Instructor:
			Name
			Email
			Office
			Office Hours
		"Edit Instructor Info" button - Hyperlinks to class/info/instructorEdit.php
		For each TA(ONLY ONE TA NOW):
			Name 
			Email
			"Edit TA Info" button - Hyperlinks to class/info/TAedit.php
		
	AJAX Functions:
		none
		
	Page Connections:
		class/index.php
		class/info/roster.php
		class/info/edit.php
		class/info/instructorEdit.php
		class/info/TAedit.php
*/

session_start();

if(isset($_SESSION['username'])){
	if(($_SESSION['idle'] + 600) < time()){
		unset($_SESSION['username']);
		unset($_SESSION['idle']);
		header("Location: ../../index.php", true, 303);
		exit();
	}
}
else{
	header("Location: ../../index.php");
	exit();
}

$username = $_SESSION['username'];
$_SESSION['idle'] = time();

$mysqli = new mysqli("localhost", "SelectOnly", "system", "LSU-ACE");
if($mysqli->connect_errno){
	//Send HTTP error code
	exit();
}


if(!isset($_GET['ID'])){
	header("Location: ../../profile/index.php", true, 303);
	exit();
}
$ID = $_GET['ID'];

$res = $mysqli->query("SELECT * FROM Taking WHERE Cid = '$ID' AND Sid = '$username';");

if($res->num_rows == 0){
	header("Location: ../../profile/index.php", true, 303);
	exit();
}




$res = $mysqli->query("SELECT Dept, Num, Sect, Title, Classroom, DayA, STimeA, ETimeA, DayB, STimeB, ETimeB FROM Class WHERE Cid = '$ID';");

$result = $res->fetch_assoc();
$ = $result['Dept'];
$ = $result['Num'];
$ = $result['Sect'];
$ = $result['Title'];
$ = $result['Classroom'];
$ = $result['DayA'];
$ = $result['STimeA'];
$ = $result['ETimeA'];
$ = $result['DayB'];
$ = $result['STimeB'];
$ = $result['ETimeB'];


$res = $mysqli->query("SELECT Name, Email, Office, Hours FROM Instructor WHERE Cid = '$ID' AND IsReal = 1;");

$result = $res->fetch_assoc();
$ = $result['Name'];
$ = $result['Email'];
$ = $result['Office'];
$ = $result['Hours'];

$res = $mysqli->query("SELECT Name, Email FROM TA WHERE Cid = '$ID' AND IsReal = 1;");

$result = $res->fetch_assoc();
$ = $result['Name'];
$ = $result['Email'];


$html = "";


echo $html;

exit();

?>