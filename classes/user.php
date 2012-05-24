<?php

class User
{
	public $id;
	public $firstName;
	public $lastName;
	public $pushToken;


	function __construct($dbData)
	{

		//Key(Names of the fields in the db)
		$KEY_USER_ID = 'id';
		$KEY_FIRST_NAME = 'first_name';
		$KEY_LAST_NAME = 'last_name';
		$KEY_PUSH_TOKEN = 'push_token';

		//QUESTION:
		//Otherwise leave as numm?
		if(array_key_exists($KEY_USER_ID, $dbData))
			$this->id = $dbData[$KEY_USER_ID];

		if(array_key_exists($KEY_FIRST_NAME, $dbData))
			$this->firstName = $dbData[$KEY_FIRST_NAME];

		if(array_key_exists($KEY_LAST_NAME, $dbData))
			$this->lastName = $dbData[$KEY_LAST_NAME];

		if(array_key_exists($KEY_PUSH_TOKEN, $dbData))
			$this->pushToken = $dbData[$KEY_PUSH_TOKEN];
	}
}
?>