<?php

	session_start();
	session_destroy();
	
	//Set the cookie to already be expired
	//1 second after the start of unix tim3es 
	setcookie("id",'', 1);
	
	header("Location: authenticate.php");
	
?>