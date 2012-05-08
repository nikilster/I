<?php

	include_once('../classes/db.php');
	
	//Checks to see if the id GET argument is a valid user id
	//If so returns that (we are trying to look at that user's id
	function getUsersPage($loggedInUsersId)
	{
		//Exists
		if(!array_key_exists('id', $_GET)) return $loggedInUsersId;
		
		//Numeric
		if(!is_numeric($_GET['id'])) return $loggedInUsersId;
		
		$potentialId = intval($_GET['id']);
		
		//>0
		if($potentialId <= 0) return $loggedInUsersId;
		
		if(DB::validUserId($potentialId))
			return $potentialId;
		
		//Good
		return $loggedInUsersId;
	}
?>