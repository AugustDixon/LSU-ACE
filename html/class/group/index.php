<?php
/*
	Page Generate Script:	class/group/index.php
	This script generates the main page for the Group Finder function.
	
	HTTP Inputs:
		'ID'
	
	Page Features:
		"Back" button - Hyperlinks to class/index.php
		"Form Group" button - Hyperlinks to class/group/make.php
		For Each Group:
			Group Title
			Username of group leader
			Whether or not the group is looking for members
			Whether or not the group is open
			"View Group" button - Hyperlinks to class/group/view.php
			
	AJAX Functions:
		none
		
	Page Connections:
		class/index.php
		class/group/make.php
		class/group/view.php
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
   		 <title>Group Finder</title>
  	</head>
  	
    <body> 
		<h1>Group Finder</h1>
        <a href= \"../index.php?ID=$ID\">Back</a>
		<a href= \"make.php?ID=$ID\">Form Group</a><br><br>";



$res = $mysqli->query("SELECT Name, Sid, Open, Looking, Max FROM SGroup WHERE Cid = '$ID';");

for($i = 0; $i < $res->num_rows; $i++){
	$res->data_seek($i);
	$result = $res->fetch_assoc();
	$Name = $result['Name'];
	$Sid = $result['Sid'];
	$Open = $result['Open'];
	$Looking = $result['Looking'];
	$Max = $result['Max'];
	$result = $mysqli->query("SELECT * FROM InGroup WHERE Cid = '$ID' AND Gid = '$Sid';");
	$Num = $result->num_rows;
	$Open = $Open && ($Num < $Max);
	
	$html .= "<p>
            Group Name: $Name<br>
            Group Leader: $Sid<br>";
	if($Open)
		$html .= "
			Group Open<br>";
	if($Looking)
		$html .= "
			Looking for Members<br>";
	$html .= "
			<a href= \"view.php?ID=$ID&Sid=$Sid\">View Group</a><br>
        </p>";
}

$html .= "
	</body>
</html>";

echo $html;

exit();



?>