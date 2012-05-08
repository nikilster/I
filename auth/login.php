<?php
	
	function checkLogin()
	{
		//Check session
		session_start();
		//TODO: make this secure
		if(array_key_exists("id", $_SESSION))
			return intval($_SESSION["id"]);			
		
		//Try Cookie
		if(array_key_exists("id", $_COOKIE))
		{
			echo "cookie is set!";
			return intval($_COOKIE["id"]);			
		}
		
		//No valid login information
		header("Location: authenticate.php");
		return null;	
		
	}
?>