<?php
/*
	Page Generate Script: class/log/index.php
	This script generates a page which contains all of the dates of past class sessions.
	
	HTTP Inputs:
		'ID' - Cid
		
	Page Features:
		"Back" button - Hyperlinks to class/index.php
		For each unhidden session:
			Date
			Hyperlink to class/log/class.php
			
	AJAX Functions:
		none
		
	Page Connections:
		class/index.php
		class/log/class.php
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
		<title>Class Log</title>
		<style>
		body{margin:40px
		auto;max-width:650px;line-height:1.6;font-size:18px;color:#444;padding:0
		10px}h1,h2,h3{line-height:1.2}
		</style>
	</head>
	<body>
		<h1>Class Log</h1>
		<a href=\"../index.php?ID=$ID\">Back</a>";

$res = $mysqli->query("SELECT Date, Sesid FROM Session WHERE Cid = '$ID' AND Hidden = 0;");

for($i = 0; $i < $res->num_rows; $i++){
	$res->data_seek($i);
	$result = $res->fetch_assoc();
	$Date = $result['Date'];
	$Sesid = $result['Sesid'];
		
	$html .= "
		<p>
			<a href=\"class.php?ID=$ID&Sesid=$Sesid\">Class on $Date</a>
		</p>";
}

$html .= "
	</body>
</html>";


echo $html;

exit();



?>