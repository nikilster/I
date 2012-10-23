<?php
	//API 
	//Exposes the methods for the iPhone app to use
	
	//Constants
	include_once('apiKeys.php');
	
	//API functions
	include_once('apiMethods.php');
	
	//Functions for outputting responses
	include_once('apiOutput.php');	
	
	//Include
	include_once("apiDb.php");	
	
	
	
	//Make sure the request is in a valid format
	validate();
	//Authenticate
	$userId = checkAuthentication();
	//Process the request (ie. run the function)
	processTheRequest($userId);

?>

<?php

	/*
		processTheRequest()
		
		Handles seeing what function the user wanted to call and calling that 
	*/
	function processTheRequest($userId)
	{		
		$function = getIntendedFunction();
		$timezone = getParameter(APIKeys::$TIMEZONE);

		//Login
		if($function == APIKeys::$FUNCTION_LOGIN)
			login($timezone);

		//Create Account
		else if($function == APIKeys::$FUNCTION_CREATE_ACCOUNT)
			createAccount($timezone);
	
		//Get Information
		else if($function == APIKeys::$FUNCTION_GET_INFORMATION)
			getInformation($userId, $timezone);
		
		//Start Activity
		else if($function == APIKeys::$FUNCTION_START_ACTIVITY)
			startActivity($userId, $timezone);
		
		//Stop Activity
		else if($function == APIKeys::$FUNCTION_STOP_EVENT)
			stopEvent($userId, $timezone);
			
		//Set Push Token
		else if($function == APIKeys::$FUNCTION_SET_PUSH_TOKEN)
			setPushToken($userId, $timezone);

		//Create Activity
		else if($function == APIKeys::$FUNCTION_CREATE_ACTIVITY)
			createActivity($userId, $timezone);

		//Should never get here
		else
			error();
	}
	
	/*
		Check Authentication
	
		Make sure that the user is authenticating appropriatley
		For now this means passing in the valid auth token for the user
		Unless this is the login function - then we will let the user continue without a token
	*/
	function checkAuthentication()
	{
		//Display error exits the script (so no need to return)

		//If this is a login or create user call we can skip the authentication check
		//These are the only two functions that get this priviledge 
		if(getIntendedFunction() == APIKeys::$FUNCTION_LOGIN ||
			getIntendedFunction() == APIKeys::$FUNCTION_CREATE_ACCOUNT)
			return 0;

		//Make sure they sent an auth token
		if(!parameterExists(APIKeys::$AUTH_TOKEN))
			displayError("Need auth token");

		//Check the POST['AUTH_TOKEN']
		$userId = APIDb::userIdForAuthToken(getParameter(APIKEYS::$AUTH_TOKEN), getParameter(APIKeys::$TIMEZONE));
		
		//If bad return an error response and exit
		if($userId < 1)
			displayError("Invalid Auth Token");		

		//If good, do nothing and continue	
		
		return $userId;
	}
	
	/*
		Validate
		
		Validates the base arguments to the API call
	*/
	function validate()
	{
		//Auth is good by now
		
		//Make sure the "function" key exists
		if(!parameterExists(APIKeys::$POST_FUNCTION))
			displayError("Please specify function");
			
		$function = getIntendedFunction();
		//TODO: find a better way to do this
		if(!($function === APIKeys::$FUNCTION_LOGIN ||
			 $function === APIKeys::$FUNCTION_GET_INFORMATION ||
			 $function === APIKeys::$FUNCTION_START_ACTIVITY ||
			 $function === APIKeys::$FUNCTION_STOP_EVENT ||
			 $function === APIKeys::$FUNCTION_SET_PUSH_TOKEN ||
			 $function === APIKeys::$FUNCTION_CREATE_ACCOUNT ||
			 $function === APIKeys::$FUNCTION_CREATE_ACTIVITY))
			displayError("Please specify a valid function");

		//TODO: validate timezone
		validateTimezone();

		//TODO: Any more validation
	}

	/*
		Validate Timezone
	
		Make sure that it exists and also is appropriately formatted ("-7:00" or "+8:00")
	*/
	function validateTimezone()
	{

		//Make sure they submitted a timezone
		if(!parameterExists(APIKeys::$TIMEZONE))
			displayError("Please submit your timezone");

		//Basic Timezone Validation
		$timezone = getParameter(APIKeys::$TIMEZONE);	
		
		//Assume that timezone good
		$valid = true;

		//First character should be - or +
		if( !($timezone[0] === "-" || $timezone[0] === "+")) $valid = false;

		//Should have :
		//Testing explicitly for false because index 0 == false in php
		//Though this should never happen because the : should either be in position the 2 or 3
		if( strpos($timezone,":") == false) $valid = false;

		if(!$valid)
			displayError("Please submit a valid timezone");
	}

	/*
		Get Parameter
		
		Returns the parameter if it was passed in by the request and "" if it wasn't
	*/
	function getParameter($param)
	{
		if(parameterExists($param))
			//Possible TODO: Abstract the post here
			return $_GET[$param];
		
		else
			return "";
	}
	
	/*
		Parameter Exists
		
		Checks to see if the given parameter exists in the request
	*/
	function parameterExists($param)
	{
		return array_key_exists($param, $_GET);
	}
	
	/*
		Get Intended Function
		
		Gets the name of the API function call
	*/
	function getIntendedFunction()
	{
		return getParameter(APIKeys::$POST_FUNCTION);
	}
	
?>