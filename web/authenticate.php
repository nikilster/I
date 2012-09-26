<?php

	session_start();
	
	if(array_key_exists("id",$_SESSION)) 
		//redirect
		redirect();
		
	if($_POST)
	{

		if(array_key_exists('signup-submit',$_POST)) 
		{
			include_once('../classes/db.php');
			$createAccountErrorMessage = tryCreate();
		}
		
		//else 
		if(array_key_exists('login-submit', $_POST))
		{
			include_once('../classes/db.php');
			$loginErrorMessage = tryAuthenticate();
		}
	}
	
	function tryCreate()
	{
		$name = trim($_POST["name"]);
		$parts = explode(' ', $name);
		
		$email = trim($_POST["email"]);
		$password = trim($_POST["password"]);
		$confirmPassword = trim($_POST["confirmPassword"]);
		
		$timezone = trim($_POST["timezone"]);
		
		//Missing input
		if(!($email && $password && $confirmPassword) || count($parts) < 2 || count($parts) > 3 || $password != $confirmPassword)
		{
			return "Please enter valid information";
		}
		//Good
		else
		{
			$firstName = $parts[0];
			$lastName = $parts[1];
			
			$fakeUserId = -1;
			$db = new Db($fakeUserId, $timezone);
			
			//Try to log in or create the account
			$result = $db->createUser($firstName, $lastName, $email, $password);
			
			if($result["result"] == 1)
			{
				handleSuccessfulAuthentication($result["id"], $timezone);
			}
			//Else
			else
			//Error message		
				return "Please enter a valid email address and password";
		}
	}
	
	function tryAuthenticate()
	{
		$email = trim($_POST["email"]);
		$password = trim($_POST["password"]);
		
		$timezone = trim($_POST["timezone"]);
		
		//missing input
		if(!($email && $password))
		{
			return "Please enter an email address and your password";
		}
		//Good
		else
		{
			$fakeUserId = -1;
			$db = new Db($fakeUserId, $timezone);
			
			//Try to log in 
			$result = $db->authenticate($email, $password);
			
			if($result["result"] == 1)
				handleSuccessfulAuthentication($result["id"], $timezone);
				
			else
			//Error message		
				return "Please enter a valid email address and password";
		}
		
	}	
	
	function handleSuccessfulAuthentication($id, $timezone)
	{
		//Session
		$_SESSION["id"] = $id;
		$_SESSION['timezone'] = $timezone;
		
		//Cookie
		//Unix time maxmium 2038
		$TWENTY_YEARS_IN_SECONDS = 60*60*24*365*20;
		$cookieExpiration = time() + $TWENTY_YEARS_IN_SECONDS;
		//Path Parameter
		setcookie("id", $id, $cookieExpiration, "/");
		setcookie("timezone", $timezone, $cookieExpiration, "/");

		//redirect;
		redirect();
	}
	function redirect()
	{
		header("Location: index.php");
		exit();
	}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Welcome to</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

	<!-- My Stuff -->
	
	
    <!-- Le styles -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

    <link href="css/authentication.css" rel="stylesheet"/>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
  
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Visualize</a>
          <div class="nav-collapse">
            <ul class="nav">
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

		<div id="title">
			<!-- Promo Image -->
			<img src="img/title.png" alt="Title"/>
		</div>
		<div class="row">
		
			<!-- Sign Up Form-->
			<div class="span2 offset1">
				<form name="create" action="" method="POST">
					<fieldset>
						
						<legend>Join Visualize!</legend>
						<?php 
							if(isset($createAccountErrorMessage)) 
								echo "<div class=''>$createAccountErrorMessage</div>";
						?>
						<div class="control-group">
							<div class="controls">
								<input class="input-medium" type="text" placeholder="Full Name" name="name"/>
							</div>
							
							<div class="controls">
								<input class="input-medium" type="text" placeholder="Email" name="email"/>
							</div>
					
							<div class="controls">
								<input class="input-medium" type="password" placeholder="Password" name="password"/>
							</div>
						
							<div class="controls">
								<input class="input-medium" type="password" placeholder="Confirm" name="confirmPassword"/>
							</div>

							<div class="controls">
								<input type="hidden" name="timezone">
							</div>
						</div>
						
						<div class="control-group">
							<div>
								<button type="submit" name="signup-submit" class="btn btn-primary">Sign in</button>
							</div>
						</div>
					</fieldset>
				</form>		
			
			<!-- Login Form-->
			
				<form name="login" action="" method="POST">
					<fieldset>
						
						<legend>Sign in</legend>
						
						<?php 
							if(isset($loginErrorMessage)) 
								echo "<div class=''>$loginErrorMessage</div>";
						?>
						
						<div class="control-group">
							<div class="controls">
								<input class="input-medium" type="text" placeholder="Email" name="email"/>
							</div>
						
							<div class="controls">
								<input class="input-medium" type="password" placeholder="Password" name="password"/>
							</div>
							
							<div class="controls">
								<input type="hidden" name="timezone">
							</div>
						</div>
						
						<div class="control-group">
							<div >
								<button type="submit" name="login-submit" class="btn btn-success">Sign in</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		
			<!-- Screenshots -->
			<div class="span6 offset1">
				<img id="screenshots" src="img/screenshots.png" alt="screenshots"/>
			</div>
		</div> <!-- /Row -->
	</div> <!-- /Container -->
	
	<script type="text/javascript">
         //Get the user's timezone
		 var timezone = -new Date().getTimezoneOffset()/60;
		 //Format according to the mysql requirment nicely
		 if(timezone >= 0) timezone = "+"+timezone;
		 timezone = timezone + ":00";
		 //Set the fields in the form (or we could use document.create.timezone.value)
		 document.forms["create"].timezone.value = timezone;
		 document.forms["login"].timezone.value = timezone;
	</script>
  </body>
</html>