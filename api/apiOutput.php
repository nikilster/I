<?php

	/*
		Response
	
		Returns a response for the API
	*/
	function response($result)
	{
		print_r($result);
		
		//Finished
		exit();
	}





	/*
		Display Error
		
		Returns an error response, weith the given message
	*/
	//TODO: Write This
	function displayError($message)
	{
		//JSON -Create response
		echo $message;
		
		//Finished
		exit();
	}
	
?>