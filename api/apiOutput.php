<?php

	/*
		Response
	
		Returns a response for the API
	*/
	function response($result)
	{
		//Format the response
		$response = array("result"=>1, "data"=>$result);
		
		//JSON
		echo json_encode($response);
		
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
		//JSON - Create response
		$result = array('result'=>-1, 'message'=>$message);
		
		echo json_encode($result);
		
		//Finished
		exit();
	}
	
?>