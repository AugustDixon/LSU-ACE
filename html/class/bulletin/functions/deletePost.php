<?php
/*
	Function Script: class/bulletin/functions/deletePost.php
	This script deletes a bulletin post. If the post is a query, it also deletes any relations related to the query.
	
	Inputs:
		'ID'
		'Pid'
		
	Outputs:
		0 = Script failure
		1 = Success
		2 = Idle Timeout
*/

date_default_timezone_set("America/Chicago");
session_start();

if(($_SESSION['idle'] + 600) < time()){
	unset($_SESSION['username']);
	unset($_SESSION['idle']);
	echo "2";
	exit();
}

$username = $_SESSION['username'];
$_SESSION['idle'] = time();

$mysqli = new mysqli("localhost", "DeleteOnly", "system", "LSU-ACE");
if($mysqli->connect_errno){
	echo "0";
	exit();
}

$ID = $_POST['ID'];

$res = $mysqli->query("SELECT * FROM Taking WHERE Cid = '$ID' AND Sid = '$username';");

if($res->num_rows == 0){
	echo "0";
	exit();
}

$Pid = $_POST['Pid'];



$res = $mysqli->query("SELECT Query, Aid, Sid FROM Bulletin WHERE Pid = '$Pid';");
$result = $res->fetch_assoc();

if($username != $result['Sid']){
	echo "0";
	exit();
}

if($result['Query']){
	$Aid = $result['Aid'];
	if(!($mysqli->query("DELETE FROM AlteredResp WHERE Aid = '$Aid';"))){
		echo "0";
		exit();
	}
	if(!($mysqli->query("DELETE FROM Altered WHERE Aid = '$Aid';"))){
		echo "0";
		exit();
	}
}

if($mysqli->query("DELETE FROM Bulletin WHERE Pid = '$Pid';"))
	echo "1";
else
	echo "0";

exit();


?>