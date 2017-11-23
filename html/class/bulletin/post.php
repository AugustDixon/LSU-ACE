<?php
/*
	Page Generate Script: class/bulletin/post.php
	This script generates the Make Post page for the bulletin board.
	
	HTTP Inputs:
		'ID' - Cid
		
	Page Features:
		"Back" button - Hyperlinks to class/bulletin/index.php
		"Title" text field - 1 to 30 characters
		"Body" text field - 1 to 200 characters
		"Make Post" button - Runs AJAX class/bulletin/makePost.php
		
	AJAX functions:
		makePost.php
			Inputs
				'ID' - Cid
				'Title'
				'Body'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirect to class/bulletin/view.php
				Case "2" - Idle Timeout
					Redirect to index.php
				Case "3" - Title constraint error
					Show constraints
				Case "4" - Body constraint error
					Show constraints
	
	Page Connections:
		class/bulletin/view.php
		class/bulletin/index.php
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