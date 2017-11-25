<?php
/*
	Function Script: class/info/functions/editInstructor.php
	This script generates an Altered, AlteredResp, and Bulletin relation to make an info edit query.
	
	Inputs:
		'ID'
		'Name' - 0 to 30 characters
		'Email' - 0 to 30 characters
		'Office' - 0 to 20 character
		'Hours' - 0 to 50 characters
		
	Outputs:
		0 = Script Failure
		1 [Pid]= Success
		2 = Idle Timeout
		3 = Name Constraint Error
		4 = Email Constraint Error
		5 = Office Constraint Error
		6 = Hours Constraint Error
		7 = Same as current info
		8 [Pid] = Already Exists
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
$Office = $_POST['Office'];
$Hours = $_POST['Hours'];

//Check Constraints
if(strlen($Name) > 30){
	echo "3";
	exit();
}

if(strlen($Email) > 30){
	echo "4";
	exit();
}

if(strlen($Office) > 20){
	echo "5";
	exit();
}

if(strlen($Hours) > 50){
	echo "6";
	exit();
}


//Check if same as current info
$res = $mysqli->query("SELECT * FROM Instructor WHERE Cid = '$ID' AND Name = '$Name' AND Email = '$Email' AND Office = '$Office' AND Hours = '$Hours';");

if($res->num_rows > 0){
	echo "7";
	exit();
}


//Check if query already exists
$res = $mysqli->query("SELECT Aid FROM Altered WHERE Cid = '$ID' AND InstrName = '$Name' AND InstrEmail = '$Email' AND Office = '$Office' AND Hours = '$Hours' AND EditInstructor = 1;");

if($res->num_rows > 0){
	$result = $res->fetch_assoc();
	$Aid = $result['Aid'];
	$res = $mysqli->query("SELECT Pid FROM Bulletin WHERE Cid = '$ID' AND Aid = '$Aid';");
	$result = $res-> fetch_assoc();
	$Pid = $result['Pid'];
	$res = $mysqli->query("SELECT * FROM AlteredResp WHERE Cid = '$ID' AND Aid = '$Aid' AND Sid = '$username';");
	if($res->num_rows > 0){
		echo "8" . " $Pid";
	}
	else{
		if($mysqli->query("INSERT INTO AlteredResp (Cid, Aid, Sid, Response) VALUES ('$ID', '$Aid', '$username', 1);")){
			echo "8" . " $Pid";
		}
		else
			echo "0";
	}
	exit();
}

//Begin operations to execute

//Create Altered relation
if(!($mysqli->query("INSERT INTO Altered (Cid, EditInstructor, InstrName, InstrEmail, Office, Hours) VALUES ('$ID', 1, '$Name', '$Email', '$Office', '$Hours');"))){
	echo "0";
	exit();
}
$Aid = $mysqli->insert_id;

//Create AlteredResp relation
if(!($mysqli->query("INSERT INTO AlteredResp (Cid, Aid, Sid, Response) VALUES ('$ID', '$Aid', '$username', 1);"))){
	echo "0";
	exit();
}

//Create Bulletin relation
$PostTitle = "Instructor Information Change Request";
$Date = date("m/d/y");

if(!($mysqli->query("INSERT INTO Bulletin (Cid, Sid, Title, Body, Date, Query, Aid) VALUES ('$ID', '$username', '$PostTitle', '', '$Date', 1, '$Aid');"))){
	echo "0";
	exit();
}
$Pid = $mysqli->insert_id;

echo "1 $Pid";

exit();


?>