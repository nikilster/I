<?php

	session_start();
	session_destroy();
	
	//Set the cookie to already be expired
	//1 second after the start of unix tim3es 
	//4th Argument is the gPath parameter - set this so that it works outside of this folder
	setcookie("id",'', 1, "/");
	
	header("Location: ../web/authenticate.php");
	
?>