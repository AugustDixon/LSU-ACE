<?php
/*
	Function Script: class/info/functions/editTA.php
	This script generates a Altered, AlteredResp, and Bulletin relation to make an info edit query.
	
	Inputs:
		'ID'
		'OldName' - 0 to 30 characters.
		'Name' - 0 to 30 characters.
		'Email' - 0 to 30 characters
		
	Outputs:
		0 = Script Failure
		1 [Pid]= Success
		2 = Idle Timeout
		3 = Name Constraint Error
		4 = Email Constraint Error
		5 = Same as current info
		6 [Pid]= Already Exists
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

$mysqli = new mysqli("localhost", "Scheduler", "system", "LSU-ACE");
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




$Name = $_POST['Name'];
$Email = $_POST['Email'];

//Check Constraints
if(strlen($Name) > 30){
	echo "3";
	exit();
}

if(strlen($Email) > 30){
	echo "4";
	exit();
}




//Check if same as current info
$res = $mysqli->query("SELECT * FROM TA WHERE Cid = '$ID' AND Name = '$Name' AND Email = '$Email';");

if($res->num_rows > 0){
	echo "5";
	exit();
}

//Check if query already exists

$res = $mysqli->query("SELECT Aid FROM Altered WHERE Cid = '$ID' AND TAName = '$Name' AND TAEmail = '$Email' AND EditTA = 1;");

if($res->num_rows > 0){
	$result = $res->fetch_assoc();
	$Aid = $result['Aid'];
	$res = $mysqli->query("SELECT Pid FROM Bulletin WHERE Cid = '$ID' AND Aid = '$Aid';");
	$result = $res-> fetch_assoc();
	$Pid = $result['Pid'];
	$res = $mysqli->query("SELECT * FROM AlteredResp WHERE Cid = '$ID' AND Aid = '$Aid' AND Sid = '$username';");
	if($res->num_rows > 0){
		echo "6" . " $Pid";
	}
	else{
		if($mysqli->query("INSERT INTO AlteredResp (Cid, Aid, Sid, Response) VALUES ('$ID', '$Aid', '$username', 1);")){
			echo "6" . " $Pid";
		}
		else
			echo "0";
	}
	exit();
}

//Begin operations to execute

//Create Altered relation
if(!($mysqli->query("INSERT INTO Altered (Cid, EditTA, TAName, TAEmail) VALUES ('$ID', 1, '$Name', '$Email');"))){
	echo "0";
	exit();
}
$Aid = $mysqli->insert_id;

//Create AlteredResp relation;
if(!($mysqli->query("INSERT INTO AlteredResp (Cid, Aid, Sid, Response) VALUES ('$ID', '$Aid', '$username', 1);"))){
	echo "0";
	exit();
}

//Create Bulletin relation
$PostTitle = "TA Information Change Request";
$Date = date("m/d/y");

if(!($mysqli->query("INSERT INTO Bulletin (Cid, Sid, Title, Body, Date, Query, Aid) VALUES ('$ID', '$username', '$PostTitle', '', '$Date', 1, '$Aid');"))){
	echo "0";
	exit();
}
$Pid = $mysqli->insert_id;

echo "1 $Pid";

exit();


?>