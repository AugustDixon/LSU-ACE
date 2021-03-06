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




$html = "<html>
 	<head> 
   		<title>Class Bulletin</title>
		<style>
		body{margin:40px
		auto;max-width:650px;line-height:1.6;font-size:18px;color:#444;padding:0
		10px}h1,h2,h3{line-height:1.2}
		</style>
  	</head>
    <body> 
		<h1>Class Bulletin</h1>
        <a href=\"../index.php?ID=$ID\">Back</a>
		<a href=\"post.php?ID=$ID\">Make Post</a><br><br>";

$res = $mysqli->query("SELECT Pid, Sid, Title, Date FROM Bulletin WHERE Cid = '$ID' ORDER BY Pid;");

for($i = 0; $i < $res->num_rows; $i++){
	$res->data_seek($i);
	$result = $res->fetch_assoc();
	$Sid = $result['Sid'];
	$Pid = $result['Pid'];
	$Title = $result['Title'];
	$Date = $result['Date'];
	$html .= "
        <p>
			<a href=\"view.php?ID=$ID&Pid=$Pid\">$Title</a>  $Date $Sid
        </p>";
}

$html .= "
	</body>
</html>";

echo $html;

exit();


?>