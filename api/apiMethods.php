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
			displayError("Login: Please suuply a password as part of the request");
		
		//Get the values
		$email = getParameter(APIKeys::$EMAIL);
		$password = getParameter(APIKeys::$PASSWORD);
		
		//TODO:any checking 
		
		$result = APIDb::login($email, $password);
		
	}
	
	
	
	
	/*
		Get Information
		
		Gets the information for the home screen.
		Activities, Event
	*/
	
	function getInformation()
	{
	
	}
		
	
	
	/*
		Start Activity
		
		Starts the specified activity. (Ends any previous running activity)		
	*/

	function startActivity()
	{
	
	}
	
	
	/*
		Stop Activity
		
		Stops the current activity.
	*/
	function stopActivity()
	{
	}
			
		
?>