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




?>