<?php
	
	//Include
	include_once("apiDb.php");	
	
	/*
		Login
		
		Attempts to log the user in and gives the auth token
	*/
	function login()
	{
		//Checks
		if(!parameterExists(APIKeys::$EMAIL) && !parameterExists(APIKeys::$PASSWORD))
			displayError("Login: Please supply a valid email and password as part of the request");
		
		else if(!parameterExists(APIKeys::$EMAIL))
			displayError("Login: Please supply a email as part of the request");
		
		else if(!parameterExists(APIKeys::$PASSWORD))
			displayError("Login: Please suply a password as part of the request");
		
		//Get the values
		$email = getParameter(APIKeys::$EMAIL);
		$password = getParameter(APIKeys::$PASSWORD);
		
		//TODO:any checking 
		
		$result = APIDb::login($email, $password);
		
		//show response
		response($result);
		
	}
	
	
	
	
	/*
		Get Information
		
		Gets the information for the home screen.
		Activities, Event
	*/
	
	function getInformation($userId)
	{
		$result = APIDb::getInformation($userId);
		response($result);
	}
		
	
	
	/*
		Start Activity
		
		Starts the specified activity. (Ends any previous running activity)		
	*/

	function startActivity($userId)
	{
		//Checks
		if(!parameterExists(APIKeys::$ACTIVITY_ID))
			displayError("Start Event: Please supply a valid activity id as part of the request");
		
		$activityId = getParameter(APIKeys::$ACTIVITY_ID);
		$result = APIDb::startActivity($userId, $activityId);
		
		//Display Response
		response($result);
	}
	
	
	/*
		Stop Activity
		
		Stops the current activity.
	*/
	function stopEvent($userId)
	{
		//Check
		if(!parameterExists(APIKeys::$EVENT_ID))
			displayError("Stop Event: Please supply a valid event id as part of the request");
			
		$eventId = getParameter(APIKeys::$EVENT_ID);
		$result = APIDb::stopEvent($userId, $eventId);
		
		//Display Response
		response($result);
	}
			
		
?>