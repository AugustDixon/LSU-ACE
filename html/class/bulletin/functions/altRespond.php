<?php
/*
	Function Script: class/bulletin/functions/altRespond.php
	Responds to an information alteration query and performs operations based on that response. First, the response is
	recorded in AlteredResp. Then, the script checks whether or not the vote is a majority in either favor against
	the amount of people in the class. Then the script checks if it is a completely tied vote with everyone in the class
	voting. If it ends up in a yes vote majority, the changes from the corresponding Altered relation are applied. If any
	of these conditions are true. The corresponding Bulletin, Altered, and AlteredResp relations are deleted along with
	any temporary tables.
	
	Inputs:
		'Answer' - boolean
		'ID'
		'Pid'
	
	Output Codes:
		0 = Script failure
		1 = Success
		2 = Idle Timeout
*/





?>