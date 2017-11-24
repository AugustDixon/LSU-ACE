<?php
/*
	Page Generate Script: class/bulletin/index.php
	This script generates the main page of the bulletin board.
	
	HTTP Inputs:
		'ID' - Cid of class
	
	Page Features:
		"Back" button - Hyperlinks to class/index.php
		"Make Post" button - Hyperlinks to class/bulletin/post.php
		For each post:
			Date
			Username of poster
			Title of post - Hyperlinks to class/bulletin/view.php
			
	AJAX Functions:
		none
		
	Page Connections:
		class/index.php
		class/bulletin/post.php
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

$res = $mysqli->query("SELECT Pid, Sid, Title, Date FROM Bulletin WHERE Cid = '$ID';");

for($i = 0; $i < $res->num_rows; $i++){
	$res->data_seek($i);
	$result = $res->fetch_assoc();
	$Username = $result['Sid'];
	$Pid = $result['Pid'];
	$Title = $result['Title'];
	$Date = $result['Date'];
	$html .= "";
}

$html .= "";

echo $html;

exit();


?>