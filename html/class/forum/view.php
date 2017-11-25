<?php
/*
	Page Generate Script:	class/forum/view.php
	This script generates the View Thread page.
	
	HTTP Inputs:
		'ID'
		'Tid'
		
	Page Features:
		"Back" button - Hyperlinks to class/forum/index.php
		"Make Post" button - Hyperlinks to class/forum/post.php
		Thread Title
		For each post:
			Author - Either Username or Anonymous
			Body
			"Delete Post" button - Runs AJAX class/forum/functions/deletePost.php. Only exists if the user is the author of the post.
			
	AJAX Functions:
		deletePost.php
			Inputs
				'ID'
				'Pid'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Reloads class/forum/view.php
				Case "2" - Idle Timeout
					Redirects to index.php
					
	Page Connections:
		class/forum/index.php
		class/forum/post.php
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

$Tid = $_GET['Tid'];

$res = $mysqli->query("SELECT Title FROM Thread WHERE Tid = '$Tid';");
$result = $res->fetch_assoc();
$Title = $result['Title'];



$html = "<html>
 	<head> 
   		 <title>View Thread</title>
		 <style>
			table, th, td {
				border: 1px solid black;
				border-collapse: collapse;
			}
			th, td {
				padding: 5px;
				text-align: left;    
			}
		</style>
  	</head>
  	<body>
		<h1>$Title</h1>
   		 	<a href=\"index.php?ID=$ID\">Back</a>
			<a href=\"post.php?ID=$ID&Tid=$Tid\">Make Post</a><br><br>
			<table style=\"width:100%\">";
			
$res = $mysqli->query("SELECT Sid, Body, Pid, Anonymous, Date FROM ForumPost WHERE Tid = '$Tid' ORDER BY Pid;");

for($i = 0; $i < $res->num_rows; $i++){
	$res->data_seek($i);
	$result = $res->fetch_assoc();
	if($result['Anonymous'])
		$Sid = "Anonymous";
	else
		$Sid = $result['Sid'];
	$Pid = $result['Pid'];
	$Body = $result['Body'];
	$Date = $result['Date'];
	if($i != 0)
		$html .= "
				<tr>
					<td colspan=\"3\"></td>
				</tr>";
	
	$html .= "
				<tr> 
					<td>$Sid</td>
					<td rowspan=\"2\">$Body</td>";
	if(($result['Sid'] == $username) && ($Body != "Deleted"))
		$html .= "
					<td rowspan=\"2\"><input type=\"button\" value=\"Delete Post\" onClick=\"loadDoc('functions/deletePost.php?Pid=$Pid', myFunction)\"></td>";
	else
		$html .= "
					<td rowspan=\"2\"></td>";
	$html .= "
				</tr>
				<tr>
					<td>$Date</td>
					
				</tr>";
}

$html .= "
			</table>
	</body>
	<script>
		function loadDoc(url, cFunction) 
		{
			var attributes = 'ID=$ID';
	
			var xhttp;
			xhttp=new XMLHttpRequest();
			xhttp.onreadystatechange = function() 
			{
				if (this.readyState == 4 && this.status == 200) 
				{
					cFunction(this);
				}
			};
			xhttp.open(\"POST\", url, true);
			xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");
			xhttp.send(attributes);
		}
	
		function myFunction(xhttp) 
		{
			switch(xhttp.responseText)
			{
			case \"0\": 
				alert(\"Delete Post Script Failure\");
				break;
			
			case \"1\": 
				location.reload();
				break;
			
			case \"2\": 
				window.location = \"../../index.php\";
				break;
			}
		}
	</script>
</html>";


echo $html;

exit();



?>