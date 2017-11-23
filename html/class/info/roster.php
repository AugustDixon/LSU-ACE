<?php
/*
	Page Generate Script: class/info/roster.php
	This script generates the class roster page.
	
	HTTP Inputs:
		'ID' - Cid
		
	Page Features:
		"Back" button - Hyperlinks to class/info/index.php
		For each student:
			LSUID - Replaced with "Hidden" if HideID is set.
			Nickname
			FirstName - Replaced with "Hidden" if HideName is set.
			LastName - Replaced with "Hidden" if HideName is set.
			PhoneNumber - Replaced with "Hidden" if HidePhone is set.
			
	AJAX Functions:
		none
		
	Page Connections:
		class/info/index.php
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

$res = $mysqli->query("SELECT Sid, HideName, HideID, HidePhone, FirstName, LastName, Nickname, Phone FROM Taking NATURAL JOIN Student WHERE Cid = '$ID';");

for($i = 0; $i < $res->num_rows; $i++){
	$res->data_seek($i);
	$result = $res->fetch_assoc();
	$HideName = $result['HideName'];
	$HideID = $result['HideID'];
	$HidePhone = $result['HidePhone'];
	if($HideID)
		$LSUID = $result['Sid'];
	else
		$LSUID = "Hidden";
	$Nickname = $result['Nickname'];
	if($HideName){
		$FirstName = $result['FirstName'];
		$LastName = $result['LastName'];
	}
	else{
		$FirstName = "Hidden";
		$LastName = "Hidden";
	}
	if($HidePhone)
		$Phone = $result['Phone'];
	else
		$Phone = "Hidden";
	$html .= "";
}

$html .= "";

echo $html;

exit();

?>