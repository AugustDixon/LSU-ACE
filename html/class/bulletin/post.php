<?php
/*
	Page Generate Script: class/bulletin/post.php
	This script generates the Make Post page for the bulletin board.
	
	HTTP Inputs:
		'ID' - Cid
		
	Page Features:
		"Back" button - Hyperlinks to class/bulletin/index.php
		"Title" text field - 1 to 50 characters
		"Body" text field - 1 to 200 characters
		"Make Post" button - Runs AJAX class/bulletin/makePost.php
		
	AJAX functions:
		makePost.php
			Inputs
				'ID' - Cid
				'Title'
				'Body'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirect to class/bulletin/view.php
				Case "2" - Idle Timeout
					Redirect to index.php
				Case "3" - Title constraint error
					Show constraints
				Case "4" - Body constraint error
					Show constraints
	
	Page Connections:
		class/bulletin/view.php
		class/bulletin/index.php
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
   		<title>Bulletin Board</title>
		<style>
		body{margin:40px
		auto;max-width:650px;line-height:1.6;font-size:18px;color:#444;padding:0
		10px}h1,h2,h3{line-height:1.2}
		textarea {
			resize: none;
		}
		</style>
  	</head>
  	<body> 
		<h1>Make Post</h1>
            <a href= \"index.php?ID=$ID\">Back</a>
   		 	<form name=\"postClass\">
				Title: <textarea rows=\"1\" cols=\"93\" name=\"Title\" id=\"Title\" maxlength=50></textarea><br>
				<textarea rows=\"4\" cols=\"100\" name=\"Body\" id=\"Body\" maxlength=200></textarea><br>
				<input type=\"button\" value=\"Make Post\" onClick=\"loadDoc('functions/makePost.php', myFunction)\">
			</form>	
  	</body>
	<script>
		function loadDoc(url, cFunction) 
		{
			var Title = document.getElementById('Title').value;
			var Body = document.getElementById('Body').value;
			var attributes ='ID=$ID&Title=' + escape(Title) + '&Body=' + escape(Body);
	
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
			switch(xhttp.responseText.charAt(0))
			{
				case \"0\": 
					alert(\"Make Post Script Failure\");
					break;
		
				case \"1\":
					var res = xhttp.responseText.split(\" \");
					window.location = 'view.php?ID=$ID&Pid=' + res[1];
					break;
		
				case \"2\": 
					window.location = \"../../index.php\";
					break;
		
				case \"3\": 
					alert(\"Title must be 1 to 50 characters long \");
					break;
		
				case \"4\": 
					alert(\"Body must be 1 to 200 characters long\");
					break;         
			}
		}
	</script>
</html>";

echo $html;

exit();


?>