<?php
/*
	Page Generate Script: class/log/view.php
	This script generates an HTML page which displays a student's notes.
	
	HTTP Inputs:
		'ID' - Cid
		'Sesid' - Session ID
		'Nid' - Notes ID
		
	Page Features:
		"Back" button - Hyperlinks to class/log/class.php
		Student's notes as unalterable text
		"Save to Computer" button which saves notes to the user's computer
			
	AJAX Functions:
		none
		
	Page Connections:
		class/log/class.php
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

$Sesid = $_GET['Sesid'];
$Nid = $_GET['Nid'];





$res = $mysqli->query("SELECT Notes, Sid FROM Notes WHERE Cid = '$ID' AND Sesid = '$Sesid' AND Nid = '$Nid';");
$result = $res->fetch_assoc();
$Notes = $result['Notes'];
$Sid = $result['Sid'];

$html = "<html>
	<head>
		<title>View Notes</title>
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
		<h1>Notes for $Sid</h1>
		<a href=\"class.php?ID=$ID&Sesid=$Sesid\">Back</a>
		<input type=\"button\" value=\"Save Notes to Computer\" onClick=\"download()\"><br>
		<textarea rows=\"37\" cols=\"120\" wrap=\"hard\" id=\"notes\" readonly>$Notes</textarea>
	</body>
	<script>
		function download() {
			var notes = document.getElementById(\"notes\").value;
			var file = new Blob([notes], {type: \"text/plain\"});
			
			if (window.navigator.msSaveOrOpenBlob)
				window.navigator.msSaveOrOpenBlob(file, notes.txt);
			else {
				var a = document.createElement(\"a\"),
				url = URL.createObjectURL(file);
				a.href = url;
				a.download = \"notes.txt\";
				document.body.appendChild(a);
				a.click();
				setTimeout(function() {
					document.body.removeChild(a);
					window.URL.revokeObjectURL(url);  
				}, 0); 
			}
		}
	</script>
</html>";

echo $html;

exit();


?>