<?php
/*
	Page Generate Script:	class/forum/post.php
	This script generates the Make Post page.
	
	HTTP Inputs:
		'ID'
		'Tid' - Thread ID
		
	Page Features:
		"Back" button - Hyperlinks to class/forum/view.php
		"Body" text field - 1 to 400 characters
		"Anonymous" check box
		"Make Post" button - Runs AJAX class/forum/functions/makePost.php
		
	AJAX Functions:
		makePost.php
			Inputs
				'ID'
				'Tid'
				'Body'
				'Anon'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirects to class/forum/view.php
				Case "2" - Idle Timeout
					Redirects to index.php
				Case "3" - Body Constraint Error
					Show constraints
					
	Page Connections:
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

$Tid = $_GET['Tid'];



$html = "<html>
 	<head> 
   		<title>Post</title>
		<style>
		textarea {
			resize: none;
		}
		</style>
  	</head>
  	<body> 
		<h1>Post</h1>
			<a href=\"view.php?ID=$ID&Tid=$Tid\">Back</a><br><br>
			<form name=\"makeThreadForm\"> 
			<textarea rows=\"4\" cols=\"100\" wrap=\"soft\" name=\"Body\" id=\"Body\" maxlength=400></textarea><br> 
			Anonymous <input type=\"checkbox\" name=\"Anonymous\" id=\"Anonymous\"><br>  
			</form>
			<input type=\"button\" value=\"Make Post\" onClick=\"loadDoc('functions/makePost.php', myFunction)\">
  	</body>
	<script>
	function loadDoc(url, cFunction) 
	{
		var Body = document.getElementById('Body').value;
		var Anon = document.getElementById('Anonymous').checked;
		var attributes = 'ID=$ID&Tid=$Tid&Body=' + Body + '&Anon=' + Anon;
	
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
			alert(\"Make Post Script Failure\");
			break;
			
		case \"1\": 
			window.location = \"view.php?ID=$ID&Tid=$Tid\";
			break;
			
		case \"2\": 
			window.location = \"../../index.php\";
			break;
			
		case \"3\": 
			alert(\"Body must be 400 characters or less\");
			break;
		}
	}
	</script>
</html>";

echo $html;

exit();


?>