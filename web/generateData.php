Generating Test Data:
<?php

	include_once('auth/login.php');
	list($id, $timezone) = checkLogin();
	
	include_once('../classes/db.php');
	$db = new Db($id, $timezone);
	
	$db->generateTestData();

?>

