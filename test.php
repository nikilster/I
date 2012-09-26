<?php
	
	include_once('login.php');
	list($id, $timezone) = checkLogin();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<style type="text/css">
		.span4
		{
			background-color:blue;
		}
		
		.span8
		{
			background-color:red;
		}
	</style>
    <meta charset="utf-8">
    <title>Magic Time</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

	<!-- My Stuff -->
	<link rel="stylesheet" type="text/css" href="css/index.css" />
	<script type="text/javascript" src="js/main.js" ></script>
	<script type="text/javascript" src="js/event.js" ></script>
	<script type="text/javascript" src="js/time.js" ></script>
	<script type="text/javascript" src="js/activity.js" ></script>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"> </script>
	

	<script type="text/javascript" >
		<?php

			//timer
			//Stop
			//Activity 1
			//Activity 2
			//Activity 3
			
			//History
			//Swim @4:35pm 4min
			//Eat @3:32 12 min
			
			//Include
			include_once('db.php');
			include_once('event.php');
			
			//Get the current running event
			$db = new Db($id, $timezone);
			$currentEvent = $db->getCurrentRunningEvent();
			$activities = $db->getActivities();
			$completed = $db->getCompletedEvents();
			
			//Output data
			//TODO: Figure out a better way to do this
			echo "var STATE = {};";
			echo "STATE.currentEvent = " . json_encode($currentEvent). ";";
			echo "\n";
			echo "if(STATE.currentEvent) STATE.currentEvent = new Event(STATE.currentEvent);";
			//For readability
			echo "\n";
			echo "STATE.activities = createActivitiesArray(".json_encode($activities).");";
			echo "\n";
			echo "STATE.completed = createEventsArray(".json_encode($completed).");";
		?>


		//Document Ready
		$(document).ready(function() {

			setupPage();
		});

	</script>
	
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
              <li class="active"><a href="index.php">Home</a></li>
              <li><a href="see.php">Insights</a></li>
              <li><a href="logout.php">Logout</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
		<div class="row">
			<div class="span4">asdf</div>
			<div class="span8">asdf</div>
		</div>
	</div> <!-- /container -->
  </body>
</html>