<?php
	
	include_once('auth/login.php');
	list($id, $timezone) = checkLogin();

	
	$errorInput = false;
	
	if(count($_POST) > 0)
	{
		//Create the activity and start!

		//Validate 
		$activityName1 = trim($_POST['activityName1']);
		$goalDuration1 = floatval(trim($_POST['goalDuration1']));

		$activityName2 = trim($_POST['activityName2']);
		$goalDuration2 = floatval(trim($_POST['goalDuration2']));

		$activityName3 = trim($_POST['activityName3']);
		$goalDuration3 = floatval(trim($_POST['goalDuration3']));
		
		if(functionValidate($activityName1, $goalDuration1)
			&& functionValidate($activityName2, $goalDuration2)
			&& functionValidate($activityName3, $goalDuration3))
		{
		
			include_once('../classes/db.php');
			$db = new Db($id, $timezone);	
			$result1 = $db->createActivity($activityName1, $goalDuration1);
			$result2 = $db->createActivity($activityName2, $goalDuration2);
			$result3 = $db->createActivity($activityName3, $goalDuration3);
			
			//If the activities are created successfully
			if($result1 && $result2 && $result3)
				redirectToIndex();
			//Broke here
			else
				echo "Error creating activities! Try again!";
		}
		else
			$errorInput = true;
	}
		
	function functionValidate($name, $goal)
	{
		//Max activity 
		$MAX_ACTIVITY_LENGTH = 100;
		
		return strlen($name) >=1 
				&& strlen($name) <= $MAX_ACTIVITY_LENGTH
				&& $goal >= 0.1
				&& $goal <= 24;
	}
	
	function redirectToIndex()
	{
		header("Location: index.php");
		exit();
	}		
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Magic Time</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
	<link href="css/createActivities.css" rel="stylesheet">

	<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"> </script>
	
    <!-- Le styles -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

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
	
		<div class="row">
			<div class="">
				
				<h2>Let's put in some activities to track! </h2>
				We can put in 3 activities and a goal (number of hours) for time to spend per day each.

				<div class="small"></div>
				
				<form class="form-inline" method="post" action="">
					
					<?php if($errorInput){ ?>						
						<div class="control-group warning">
							<label class="control-label">
								Please put in 3 activities with the number of hours you want to spend on each.
							</label>
						</div>						
					<?php } ?>
					
					<!-- Activity -->
					<input type="text" name="activityName1" class="input-medium" placeholder="Ex: Exercise"/>
					<div class="input-append">
						<input type="text" name="goalDuration1" class="input-small" placeholder="Ex: 1"/><span class="add-on">hours</span>
					</div>
					<div class="small"></div>
					
					<input type="text" name="activityName2" class="input-medium" placeholder="Ex: Study"/>
					<div class="input-append">
						<input type="text" name="goalDuration2" class="input-small" placeholder="Ex: 2"/><span class="add-on">hours</span>
					</div>
					<div class="small"></div>
					
					<input type="text" name="activityName3" class="input-medium" placeholder="Ex: Play"/>
					<div class="input-append">
						<input type="text" name="goalDuration3" class="input-small" placeholder="Ex: 2"/><span class="add-on">hours</span>
					</div>
					<div class="small"></div>
					<button type="submit" id="createButton" class="btn btn-primary btn-large span2">Start!</button>
				</form>


			</div>
		</div>
	</div> <!-- /container -->
	
	<!-- Javascript - Placed at the end of the document so pages load faster! -->
	<script src="bootstrap/js/bootstrap-button.js"></script>

	</body>
</html>