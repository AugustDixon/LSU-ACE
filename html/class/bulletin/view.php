<?php
/*
	Page Generate Script: class/bulletin/view.php
	This script generates a page which views bulletin posts. Query posts will have two buttons that allow you to
	respond yes or no to the query.
	
	HTTP Inputs:
		'ID' - Cid of the class
		'Pid' - Post ID
		
	Page Features:
		"Back" button - Hyperlinks to class/bulletin/index.php
		Title
		Body
		"Yes" button - Runs AJAX class/bulletin/functions/altRespond.php. Appears only if the post is a query and the student has not yet responded.
		"No" button - Runs AJAX class/bulletin/functions/altRespond.php. Appears only if the post is a query and the student has not yet responded.
		"Delete Post" button - Runs AJAX class/bulletin/functions/deletePost.php. Appears only if the user is the author of the post.
		
	AJAX Functions:
		altRespond.php
			Inputs:
				'Answer' - boolean
				'ID'
				'Pid'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirects to class/bulletin/index.php
				Case "2" - Idle Timeout
					Redirects to index.php
		deletePost.php
			Inputs:
				'ID'
				'Pid'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirects to class/bulletin/index.php
				Case "2" - Idle Timeout
					Redirects to index.php
					
	Page Connections
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







$Pid = $_GET['Pid'];

$res = $mysqli->query("SELECT Sid, Title, Body, Date, Query, Aid, HideID, Nickname FROM Bulletin NATURAL JOIN Taking NATURAL JOIN Student WHERE Cid = '$ID' AND Pid = '$Pid';");

$result = $res->fetch_assoc();

if($result['HideID'])
	$Sid = $result['Nickname'];
else
	$Sid = $result['Sid'];

$Title = $result['Title'];
$Body = $result['Body'];
$Date = $result['Date'];
$Query = $result['Query'];
$Author = $username == $result['Sid'];


if($Query){
	$Aid = $result['Aid'];
	$res = $mysqli->query("SELECT EditClass, Title, Classroom, EditInstructor, InstrName, InstrEmail, Office, Hours, EditTA, TAName, TAEmail FROM Altered WHERE Cid = '$ID' AND Aid = '$Aid';");
	$result = $res->fetch_assoc();
	$EditInstructor = $result['EditInstructor'];
	$EditTA = $result['EditTA'];
	$EditClass = $result['EditClass'];
	
	if($EditClass){
		$NewTitle = $result['Title'];
		$NewClassroom = $result['Classroom'];
		$res = $mysqli->query("SELECT Title, Classroom FROM Class WHERE Cid = '$ID';");
		$result = $res->fetch_assoc();
		$CurrentTitle = $result['Title'];
		$CurrentClassroom = $result['Classroom'];
		
		$Body = "Current Information:
		Title: $CurrentTitle
		Classroom: $CurrentClassroom
		
		New Information:
		Title: $NewTitle
		Classroom: $NewClassroom
		
		Do you agree with these changes?";
	}
	else if($EditInstructor){
		$NewName = $result['InstrName'];
		$NewEmail = $result['InstrEmail'];
		$NewOffice = $result['Office'];
		$NewHours = $result['Hours'];
		$res = $mysqli->query("SELECT Name, Email, Office, Hours FROM Instructor WHERE Cid = '$ID';");
		$result = $res->fetch_assoc();
		$CurrentName = $result['Name'];
		$CurrentEmail = $result['Email'];
		$CurrentOffice = $result['Office'];
		$CurrentHours = $result['Hours'];
		
		$Body = "Current Information:
		Name: $CurrentName
		Email: $CurrentEmail
		Office: $CurrentOffice
		Hours: $CurrentHours
		
		New Information:
		Name: $NewName
		Email: $NewEmail
		Office: $NewOffice
		Hours: $NewHours
		
		Do you agree with these changes?";
	}
	else if($EditTA){
		$NewName = $result['InstrName'];
		$NewEmail = $result['InstrEmail'];
		$res = $mysqli->query("SELECT Name, Email FROM TA WHERE Cid = '$ID';");
		$result = $res->fetch_assoc();
		$CurrentName = $result['Name'];
		$CurrentEmail = $result['Email'];
		
		$Body = "Current Information:
		Name: $CurrentName
		Email: $CurrentEmail
		
		New Information:
		Name: $NewName
		Email: $NewEmail
		
		Do you agree with these changes?";
	}
}




$html = "";

if($Query)
	$html .= "";

if($Author)
	$html .= "";

$html .= "";

echo $html;

exit();



?>