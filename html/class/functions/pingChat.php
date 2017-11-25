<?php
/*
	Function Script: class/functions/pingChat.php
	This script returns the messages with Mid greater than the one received.
	
	Script Inputs:
		'ID' - The Cid of the class
		'Mid'
	
	Outputs:
		0 = Script failure or no new messages
		Other = Success
		
		See session.php on how to format the returned information.
*/

date_default_timezone_set("America/Chicago");
session_start();

$username = $_SESSION['username'];
$_SESSION['idle'] = time();

$mysqli = new mysqli("localhost", "SelectOnly", "system", "LSU-ACE");
if($mysqli->connect_errno){
	echo "0";
	exit();
}

$ID = $_POST['ID'];
$Mid = $_POST['Mid'];


$res = $mysqli->query("SELECT * FROM Taking WHERE Cid = '$ID' AND Sid = '$username';");

if($res->num_rows == 0){
	echo "0";
	exit();
}

$res = $mysqli->query("SELECT * FROM Session WHERE Cid = '$ID' AND InSession = 1;");

if($res->num_rows == 0){
	echo "0";
	exit();
}

$res = $mysqli->query("SELECT Mid, Body, Time FROM Chatlog WHERE Cid = '$ID' AND Mid > '$Mid' ORDER BY Mid;");
$num_rows = $res->num_rows;

if($num_rows == 0){
	echo "0";
	exit();
}

$res->data_seek($num_rows - 1);
$result = $res->fetch_assoc();
$Mid = $result['Mid'];

$output = "$Mid ";

for($i = 0; $i < $num_rows; $i++){
	$res->data_seek($i);
	$result = $res->fetch_assoc();
	$Time = $result['Time'];
	$Body = $result['Body'];
	$output .= "$Time $Body\n";
}

echo $output;

exit();

?>