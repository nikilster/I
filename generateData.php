Generating Test Data:
<?php

	include_once('login.php');
	$id = checkLogin();
	
	include_once('db.php');
	$db = new Db($id);
	
	$db->generateTestData();

?>

