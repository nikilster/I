<?php
	
	//Include
	include_once("apiDb.php");	
	
	/*
		Login
		
		Attempts to log the user in and gives the auth token
	*/
	function login($timezone)
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
		
		$result = APIDb::login($email, $password, $timezone);
		
		//show response
		response($result);
		
	}
	
	
	
	
	/*
		Get Information
		
		Gets the information for the home screen.
		Activities, Event
	*/
	
	function getInformation($userId, $timezone)
	{
		$result = APIDb::getInformation($userId, $timezone);
		response($result);
	}
		
	
	
	/*
		Start Activity
		
		Starts the specified activity. (Ends any previous running activity)		
	*/

	function startActivity($userId, $timezone)
	{
		//Checks
		if(!parameterExists(APIKeys::$ACTIVITY_ID))
			displayError("Start Event: Please supply a valid activity id as part of the request");
		
		$activityId = getParameter(APIKeys::$ACTIVITY_ID);
		$startActivity = APIDb::startActivity($userId, $activityId, $timezone);
		
		$currentEvent = APIDb::getCurrentRunningEvent($userId, $timezone);
		
		$result = array('result'=>$startActivity, 'currentRunningEvent'=>$currentEvent);
		
		//Display Response
		response($result);
	}
	
	
	/*
		Stop Activity
		
		Stops the current activity.
	*/
	function stopEvent($userId, $timezone)
	{
		//Check
		if(!parameterExists(APIKeys::$EVENT_ID))
			displayError("Stop Event: Please supply a valid event id as part of the request");
			
		$eventId = getParameter(APIKeys::$EVENT_ID);
		$result = APIDb::stopEvent($userId, $eventId, $timezone);
		
		//Display Response
		response($result);
	}
			
	/*
		Set Push Token

		Sets the push token for the current user (iPhone)
	*/
	function setPushToken($userId, $timezone)
	{
		//Check Parameter
		if(!parameterExists(APIKeys::$PUSH_TOKEN))
			displayError("Set Push Token: Please supply a push token as part of the request");

		//Get the Push Token
		$pushToken = getParameter(APIKeys::$PUSH_TOKEN);

		//Set Token
		$result = APIDb::setPushToken($userId, $pushToken, $timezone);

		//Display Response
		response($result);
	}

	/*
		Create Account

		Let the user create an account from the phone so that they dont have to do a complicated
		matching procedure when they want to use the website for analytics - may change this later 
		to let the user use the mobile app without him/her having to create an account
	*/
	function createAccount($timezone)
	{
		//Checks
		if(!parameterExists(APIKeys::$EMAIL) && !parameterExists(APIKeys::$PASSWORD))
			displayError("Login: Please supply a valid email and password as part of the request");
		
		else if(!parameterExists(APIKeys::$EMAIL))
			displayError("Login: Please supply an email as part of the request");
		
		else if(!parameterExists(APIKeys::$PASSWORD))
			displayError("Login: Please suply a password as part of the request");
		
		if(!parameterExists(APIKeys::$USER_FIRST_NAME))
			displayError("Create Account: Please supply the user's first name as part of the request");

		if(!parameterExists(APIKeys::$USER_LAST_NAME))
			displayError("Create Account: Please supply the user's last name as part of the request");


		//Get the values
		$email = getParameter(APIKeys::$EMAIL);
		$password = getParameter(APIKeys::$PASSWORD);
		$firstName = getParameter(APIKeys::$USER_FIRST_NAME);
		$lastName = getParameter(APIKeys::$USER_LAST_NAME);

		//TODO:any checking 
		$result = APIDb::createAccount($firstName, $lastName, $email, $password, $timezone);
		
		//show response
		response($result);
	}


	/*
		Create Activity

	*/
		function createActivity($userId, $timezone)
		{
			//Check
			if(!parameterExists(APIKeys::$ACTIVITY_NAME) && !parameterExists(APIKeys::$ACTIVITY_DURATION))
				displayError("Create Activity: Please supply an activity name and activty duration as part of the request");

			else if(!parameterExists(APIKeys::$ACTIVITY_NAME))
				displayError("Create Activity: Please supply an activity name as part of the request");

			else if(!parameterExists(APIKeys::$ACTIVITY_DURATION))
				displayError("Create Activity: Please supply an activity duration as part of the request");

			//Get values
			$activityName = getParameter(APIKeys::$ACTIVITY_NAME);
			$activityDuration = getParameter(APIKeys::$ACTIVITY_DURATION);

			//Create activity
			$result = APIDb::createActivity($userId, $activityName, $activityDuration, $timezone);

			//Show Response
			response($result);
		}
?>