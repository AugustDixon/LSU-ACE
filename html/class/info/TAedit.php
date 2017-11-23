<?php
/*
	Page Generate Script: class/info/TAedit.php
	This script generates the Add/Edit TA page.
	
	HTTP Inputs:
		'ID'
	
	Page Features:
		"Back" button - Hyperlinks to class/info/index.php
		"Name" Text field - 0 to 30 characters. 
		"Email" Text field - 0 to 30 characters
		"Edit TA" button - Runs AJAX class/info/functions/editTA.php
		
	AJAX Functions:
		editTA.php
			Inputs
				'ID'
				'Name'
				'Email'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1 [Pid]" - Success
					Redirect to class/bulletin/view.php
				Case "2" - Idle Timeout
					Redirect to index.php
				Case "3" - Name constraint error
					Show Constraint
				Case "4" - Email Constraint error
					Show Constraint
				Case "5" - "Total" Success
					Redirect to class/info/index.php
				Case "6 [Pid]" - Already Exists
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