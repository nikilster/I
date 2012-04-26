<?php

	function getUsersPage($id)
	{
		if(!array_key_exists('id', $_GET)) return $id;
		if(!is_numeric($_GET['id'])) return $id;
		
		$potentialId = intval($_GET['id']);
		if($potentialId > 0) return $potentialId;
	}
?>