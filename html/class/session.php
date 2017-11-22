<?php
/*
	Page generate Script: class/session.php
	This script generates the HTML page which contains the note taking environment and the chat box. 
	
	HTTP Inputs:
		'ID' - Cid of class
	
	Page Features:
		"Back" button - Hyperlinks to class/index.php
		Text editor
		"Save Work" button - Runs AJAX class/functions/autosave.php
		"Save to Computer" button - Saves the text as a .txt file
		Chat Log
		"Message" text field
		"Send" button - Runs AJAX class/functions/sendMessage.php
		
	AJAX Functions:
		autosave.php - Should be run every thirty seconds
			Inputs
				'ID'
				'text'
			Outputs/Actions
				Case "0" - Script failure
					Alert the user that saving to server failed
				Case "1" - Success
					Nothing
		sendMessage.php
			Inputs
				'ID'
				'message'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Nothing
		pingChat.php - Should be run every five seconds
			Inputs
				'ID'
				'Mid' - The Mid of the last message received
			Outputs/Actions
				Case "0" - Script Failure or no new messages
					Nothing
				ELSE - Success
					Add new messages to chat log, response text must be parsed.
		load.php - Should be run only once at the start of the page
			Inputs
				'ID'
			Outputs/Actions
				Case "0" - Script failure
					TBD
				ELSE - Success
					Load sent data into the Text Editor
					
	Page Connections:
		class/index.php
		
	Extra Info:
		Response text from pingChat.php will be formatted as follows:
		"[Mid] [Time] [Message]\n[Time] [Message]\n...\n[Time] [Message]\n"
		[Mid] - Sent only once at the start, the Mid of the last message in the sequence for use in calling pingChat.php
		[Time] - The timestamp for a message followed by a space
		[Message] - The message followed by a newline
		The first message in the sequence will be the earliest message.
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

$res = $mysqli->query("SELECT * FROM Session WHERE Cid = '$ID' AND InSession = 1;");

if($res->num_rows == 0){
	header("Location: index.php", true, 303);
	exit();
}

echo "<html>
	<head>
		<title>Take Notes</title>
	</head>
	<body>
		<a href=\"index.php\">Back</a>
		<input type=\"button\" value=\"Save Work\" onClick=\"autosave()\">
		<input type=\"button\" value=\"Save to Computer\" onClick=\"download()\"><br>
		<textarea rows=\"150\" cols=\"100\" wrap=\"hard\" id=\"notes\"></textarea>
		<textarea rows=\"50\" cols=\"60\" wrap=\"soft\" id=\"chat\" readonly></textarea>
		<textarea rows=\"2\" cols=\"60\" wrap=\"soft\" id=\"message\"></textarea>
		<input type=\"button\" value=\"Send Message\" onClick=\"sendMessage()\">
		
		
		<script>
			window.onLoad(load());
			window.setInterval(autosave(), 30000);
			window.setInterval(pingChat(), 5000);
			var Mid = '0';
			
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
			
			function load(){
				
				var attributes = 'ID=$ID';
				
				var xhttp;
				xhttp=new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						finishLoad(this);
					}
				};
				xhttp.open(\"POST\", \"functions/load.php\", true);
				xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");
				xhttp.send(attributes);
			}
			
			function finishLoad(xhttp){
				switch(xhttp.responseText){
					case \"0\":
						alert(\"Load Script Failure\");
						window.location = index.php;
						break;
					default:
						document.getElementById(\"notes\").value = xhttp.responseText;
			}
			
			function autosave(){
				
				var text = document.getElementById(\"notes\").value;
				var attributes = 'ID=$ID&text=' + escape(text);
				
				var xhttp;
				xhttp=new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						finishAutosave(this);
					}
				};
				xhttp.open(\"POST\", \"functions/autosave.php\", true);
				xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");
				xhttp.send(attributes);
			}
			
			function finishAutosave(xhttp){
				switch(xhttp.responseText){
					case \"0\":
						alert(\"Autosave failure\");
						break;
					case \"1\":
						break;
					default:
						alert(\"Autosave Unknown Error\");
				}
			}
			
			function pingChat(){
				
				var attributes = 'ID=$ID&Mid=' + Mid;
				
				var xhttp;
				xhttp=new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						finishPingChat(this);
					}
				};
				xhttp.open(\"POST\", \"functions/pingChat.php\", true);
				xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");
				xhttp.send(attributes);
			}
			
			function finishPingChat(xhttp){
				var x = xhttp.responseText;
				if(x.charAt(0) != '0'){
					var i = 0;
					var temp = '';
					while(x.charAt(i) != ' '){
						temp += x.charAt(i);
						i++;
					}
					i++;
					Mid = tempMid;
					var chat = document.getElementById(\"chat\").value;
					while(i < x.length){
						chat += x.charAt(i);
						i++;
					}
				}
			}
			
			function sendMessage(){
				
				var message = document.getElementById(\"message\").value;
				
				var attributes = 'ID=$ID&message=' + escape(message);
				
				var xhttp;
				xhttp=new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						finishSendMessage(this);
					}
				};
				xhttp.open(\"POST\", url, true);
				xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");
				xhttp.send(attributes);
			}
			
			function finishSendMessage(xhttp){
			
			}
		</script>
	</body>
</html>";

exit();


?>