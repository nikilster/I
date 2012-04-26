Generating Test Data:
<?php

	include_once('../auth/login.php');
	$id = checkLogin();
	
	include_once('../classes/db.php');
	$db = new Db($id);
	
	$db->generateTestData();

?>

