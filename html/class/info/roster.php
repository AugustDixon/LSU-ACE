<?php
/*
	Page Generate Script: class/info/roster.php
	This script generates the class roster page.
	
	HTTP Inputs:
		'ID' - Cid
		
	Page Features:
		"Back" button - Hyperlinks to class/info/index.php
		For each student:
			Username
			LSUID - Replaced with "Hidden" if HideID is set.
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


$html = "<html>
	<head>
		<title>Class Roster</title>
	</head>
	<body>
		<h1>Class Roster</h1>
		<a href=\"index.php?ID=$ID\">Back</a>";

$res = $mysqli->query("SELECT Sid, HideName, HideID, HidePhone, FirstName, LastName, LSUID, Phone FROM Taking NATURAL JOIN Student WHERE Cid = '$ID';");

for($i = 0; $i < $res->num_rows; $i++){
	$res->data_seek($i);
	$result = $res->fetch_assoc();
	$HideName = $result['HideName'];
	$HideID = $result['HideID'];
	$HidePhone = $result['HidePhone'];
	if($HideID)
		$LSUID = "Hidden";
	else
		$LSUID = $result['LSUID'];
	$SUsername = $result['Sid'];
	if($HideName){
		$FirstName = "Hidden";
		$LastName = "Hidden";
	}
	else{
		$FirstName = $result['FirstName'];
		$LastName = $result['LastName'];
	}
	if($HidePhone)
		$Phone = "Hidden";
	else
		$Phone = $result['Phone'];
	$html .= "
		<p>
			Username: $SUsername<br>
			LSUID: $LSUID<br>
			Name: $FirstName $LastName<br>
			Phone Number: $Phone
		</p>";
}

$html .= "
	</body>
</html>";

echo $html;

exit();

?>