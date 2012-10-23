<?php
			
	//Constants
	class APIKeys
	{
		
		//POST keys
		public static $TIMEZONE = "timezone";
		public static $POST_FUNCTION = "f";
		public static $AUTH_TOKEN = "authToken";
		public static $EMAIL = "email";
		public static $PASSWORD = "password";
		public static $ACTIVITY_ID = "aid";
		public static $EVENT_ID = "eid";
		public static $PUSH_TOKEN = "pushToken";
		public static $USER_FIRST_NAME = "firstName";
		public static $USER_LAST_NAME = "lastName";
		public static $ACTIVITY_NAME = "activityName";
		public static $ACTIVITY_DURATION = "activityDuration";

		//Function names
		public static $FUNCTION_LOGIN = "login";
		public static $FUNCTION_GET_INFORMATION = "getInformation";
		public static $FUNCTION_START_ACTIVITY = "startActivity";
		public static $FUNCTION_STOP_EVENT = "stopEvent";
		public static $FUNCTION_SET_PUSH_TOKEN = "setPushToken";
		public static $FUNCTION_CREATE_ACCOUNT = "createAccount";
		public static $FUNCTION_CREATE_ACTIVITY = "createActivity";
	}

?>