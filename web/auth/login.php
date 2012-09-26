<?php
	
	function checkLogin()
	{
		//Check session
		session_start();
		//TODO: make this secure
		if(array_key_exists("id", $_SESSION) && array_key_exists("timezone", $_SESSION))
			return array(intval($_SESSION["id"]), $_SESSION["timezone"]);			
		
		//Try Cookie
		if(array_key_exists("id", $_COOKIE) && array_key_exists("timezone", $_COOKIE))
		{
			return intval($_COOKIE["id"], $_COOKIE["timezone"]);			
		}
		
		//No valid login information
		//Called from the base directory
		header("Location: authenticate.php");
		return null;	
		
	}
?>