<?php
/*
	Page Generate Script:	class/forum/index.php
	This script generates the main forum page.
	
	HTTP Inputs:
		'ID'
		
	Page Features:
		"Back" button - Hyperlinks to class/index.php
		"Make Thread" button - Hyperlinks to class/forum/makeThread.php
		For each thread:
			Title of the tread - Hyperlinks to class/forum/view.php
			
	AJAX Functions:
		none
		
	Page Connections:
		class/index.php
		class/forum/makeThread.php
		class/forum/view.php
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
   		 <title>Forum</title>
  	</head>
  	<body> 
		<h1>Forum</h1>
		<a href=\"../index.php?ID=$ID\">Back</a>
		<a href=\"makeThread.php?ID=$ID\">Make Thread</a>";

$res = $mysqli->query("SELECT Tid, Title FROM Thread WHERE Cid = '$ID' ORDER BY Pid;");

if($res->num_rows > 0)
	$html .= "
		<br>
		<br>
		<br>
		<br>
			
			<table style=\"width:100%\">
				<tr>
					<th>Thread Title:</th>
				</tr>";
				
for($i = 0; $i < $res->num_rows; $i++){
	$res->data_seek($i);
	$result = $res->fetch_assoc();
	$Tid = $result['Tid'];
	$Title = $result['Title'];
	
	$html .= "
				<tr> 
					<td><a href=\"view.php?ID=$ID&Tid=$Tid\">$Title</a></td>
				</tr>";
}

if($res->num_rows > 0)
	$html .= "
			</table>";

$html .= "
  	</body>
</html>";

echo $html;

exit();



?>