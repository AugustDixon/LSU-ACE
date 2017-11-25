<?php
/*
	Page Generate Script: /class/index.php
	This script generates the class homepage.
	
	HTTP Inputs:
		'ID' - Cid of the class
	
	Page Features:
		"Back" button - Hyperlinks to /profile/index.php
		"Class Info" button - Hyperlinks to /class/info/index.php
		"Take Notes" button - Hyperlinks to /class/session.php. Appears iff class is in session
		"Bulletin Board" button - Hyperlinks to /class/bulletin/index.php
		"Class Log" button - Hyperlinks to /class/log/index.php
		"Forum" button - Hyperlinks to /class/forum/index.php
		"Group Finder" button - Hyperlinks to /class/group/index.php
		"Options" button - Hyperlinks to /class/options.php
	
	AJAX Functions:
		None
	
	Page Connections:
		/profile/index.php
		/class/options.php
		/class/session/php
		/class/bulletin/index.php
		/class/forum/index.php
		/class/group/index.php
		/class/info/index.php
		/class/log/index.php
*/

session_start();

if(isset($_SESSION['username'])){
	if(($_SESSION['idle'] + 600) < time()){
		unset($_SESSION['username']);
		unset($_SESSION['idle']);
		header("Location: ../index.php", true, 303);
		exit();
	}
}
else{
	header("Location: ../index.php");
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
	header("Location: ../profile/index.php", true, 303);
	exit();
}
$ID = $_GET['ID'];


$res = $mysqli->query("SELECT * FROM Taking WHERE Cid = '$ID' AND Sid = '$username';");

if($res->num_rows == 0){
	header("Location: ../profile/index.php", true, 303);
	exit();
}

$res = $mysqli->query("SELECT Dept, Num, Sect FROM Class WHERE Cid = '$ID';");
$result = $res->fetch_assoc();
$Dept = $result['Dept'];
$Num = $result['Num'];
$Sect = $result['Sect'];


$html = "<html>
 	<head> 
   		 <title>$Dept $Num</title>
  	</head>
  	<body> 
		<h1>$Dept $Num Section $Sect Class Page</h1>
   		 	
			<a href=\"../profile/index.php\">Back</a><br><br>";
			
$res = $mysqli->query("SELECT * FROM Session WHERE Cid = '$ID' AND InSession = 1;");

if($res->num_rows > 0)
	$html .= "
			<a href=\"session.php?ID=$ID\">Take Notes</a><br><br>";


$html .= "
			<a href=\"bulletin/index.php?ID=$ID\">Bulletin Board</a><br>
			<a href=\"info/index.php?ID=$ID\">Class Info</a><br>
			<a href=\"forum/index.php?ID=$ID\">Forum</a><br>
			<a href=\"group/index.php?ID=$ID\">Group Finder</a><br>
			<a href=\"log/index.php?ID=$ID\">Class Log</a><br><br>
			<a href=\"options.php?ID=$ID\">Options</a>
			
  	</body>
	
</html>";



echo $html;

exit();

?>