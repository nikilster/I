<?php
	
	include_once('../db.php');
	
	//Wrapper methods for the db for the web api
	class APIDb
	{

		//This is static so we can call it without being already authenticated
		public static function login($email, $password)
		{
			//Invalid user id - just to init the db
			$fakeUserId = -1;
			$db = new Db($fakeUserId);
			
			//Try to log in
			$result = $db->authenticate($email, $password);
			
			//TODO: switch this to ===
			//API:: if we have a response == 1,  there ALWAYSwill always be a "data"
			if($result["result"] == 1)
			{
				$id = $result["id"];
				$authToken = $db->getAuthToken($id);
				
				//Return the response
				$return = array('result'=>1, 'data'=>array('authToken'=>$authToken));
				response($return);
			}
			else
			{
				$return = array('result'=>0, 'message'=>"Invalid email or password");
				response($return);
			}
		}

	}
	
	
?>