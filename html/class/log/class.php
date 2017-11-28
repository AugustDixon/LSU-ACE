<?php
/*
	Page Generate Script: class/log/class.php
	This script generates a page which shows all the notes in a class session.
	
	HTTP Inputs:
		'ID' - Cid
		'Sesid' - Session ID
		
	Page Features:
		"Back" button - Hyperlinks to class/log/index.php
		For each student's notes:
			"Name" - Shows Sid or Nickname depending on student's options
			Hyperlink to class/log/view.php
			
	AJAX Functions:
		none
		
	Page Connections:
		class/log/index.php
		class/log/view.php
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

$Sesid = $_GET['Sesid'];

$res = $mysqli->query("SELECT Date FROM Session WHERE Cid = '$ID' AND Sesid = '$Sesid';");
$result = $res->fetch_assoc();
$Date = $result['Date'];




$html = "<html>
	<head>
		<title>Class Session</title>
		<style>
		body{margin:40px
		auto;max-width:650px;line-height:1.6;font-size:18px;color:#444;padding:0
		10px}h1,h2,h3{line-height:1.2}
		</style>
	</head>
	<body>
		<h1>Class Session on $Date</h1>
		<a href=\"index.php?ID=$ID\">Back</a>";
		
$res = $mysqli->query("SELECT Nid, Sid FROM Notes WHERE Cid = '$ID' AND Sesid = '$Sesid';");

for($i = 0; $i < $res->num_rows; $i++){
	$res->data_seek();
	$result = $res->fetch_assoc();
	$Nid = $result['Nid'];
	$Sid = $result['Sid'];
	
	$html .= "
		<p>
			<a href=\"view.php?ID=$ID&Sesid=$Sesid&Nid=$Nid\">Notes for $Sid</a>
		</p>";
}

$html .= "
	</body>
</html>";

echo $html;

exit();


?>