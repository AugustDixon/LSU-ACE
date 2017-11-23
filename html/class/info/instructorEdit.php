<?php
/*
	Page Generate Script: class/info/instructorEdit.php
	This script generates the Edit Instructor page.
	
	HTTP Inputs:
		'ID'
		
	Page Features:
		"Back" button - Hyperlinks to class/info/index.php
		"Name" text field - 0 to 30 characters
		"Email" text field - 0 to 30 characters
		"Office" text field - 0 to 20 characters
		"Office Hours" text field - 0 to 50 characters
		"Submit Change Request" button - Runs class/info/functions/editInstructor.php
		
	AJAX Functions:
		editInstructor.php
			Inputs:
				'ID'
				'Name'
				'Email'
				'Office'
				'Hours'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1 [Pid]" - Success
					Redirect to class/bulletin/view.php
				Case "2" - Idle Timeout
					Redirect to index.php
				Case "3" - Name Constraint Error
					Show Constraints
				Case "4" - Email Constraint Error
					Show Constraints
				Case "5" - Office  Constraint Error
					Show Constraints
				Case "6" - Hours  Constraint Error
					Show Constraints
				Case "7" - "Total" Success
					Redirect to class/info/index.php
				Case "8 [Pid]" - Already Exists
					Redirect to class/bulletin/view.php
					
	Page Connections:
		class/info/index.php
		class/bulletin/view.php
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



$html = "";

echo $html;

exit();


?>