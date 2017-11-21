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
			Author - Either Nickname or Anonymous
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





?>