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
	
	//Authenticate
	$userId = checkAuthentication();
	
	//Make sure the request is in a valid format
	validate();
	
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
		
		//Login
		if($function == APIKeys::$FUNCTION_LOGIN)
			login();
	
		//Get Information
		else if($function == APIKeys::$FUNCTION_GET_INFORMATION)
			getInformation($userId);
		
		//Start Activity
		else if($function == APIKeys::$FUNCTION_START_ACTIVITY)
			startActivity($userId);
		
		//Stop Activity
		else if($function == APIKeys::$FUNCTION_STOP_EVENT)
			stopEvent($userId);
			
		else if($function == APIKeys::$FUNCTION_SET_PUSH_TOKEN)
			setPushToken($userId);

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
		
		//First check if it is a login
		if(getIntendedFunction() == APIKeys::$FUNCTION_LOGIN)
			return 0;
		
		//Check the POST['AUTH_TOKEN']
		$userId = APIDb::userIdForAuthToken(getParameter(APIKEYS::$AUTH_TOKEN));
		
		if($userId < 1)
			//If bad return an error response and exit
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
			 $function === APIKeys::$FUNCTION_SET_PUSH_TOKEN))
			displayError("Please specify a valid function");

		//TODO: Any more validation
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