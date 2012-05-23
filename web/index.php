<?php
	
	include_once('main.php');
	
	//$activities is already set		
	$currentEvent = $db->getCurrentRunningEvent();
	$completed = $db->getCompletedEventsForToday();
	
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Magic Time</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

	<link href="css/main.css" rel="stylesheet" type="text/css"/>
	
	<!-- My Stuff see.php -->
	<script type="text/javascript" src="d3/d3.js"></script>
	<script type="text/javascript" src="d3/d3.layout.js"></script>
	<link href="css/see.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="js/event.js" ></script>
	<script type="text/javascript" src="js/graph.js" ></script>
	<script type="text/javascript" src="js/pie-chart-d3.js" ></script>
	
	<!-- My Stuff index.php -->
	<script type="text/javascript" src="js/main.js" ></script>
	<script type="text/javascript" src="js/event.js" ></script>
	<script type="text/javascript" src="js/time.js" ></script>
	<script type="text/javascript" src="js/activity.js" ></script>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"> </script>
	
	
	<script type="text/javascript" >
		<?php
		
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
			
			//Data Format
			//Array (('id'=>'1', 'name'=>'exercise', 'goal'=>3600 duration'=>600), ...)
			echo "STATE.data = [];";
			echo "STATE.days = [];";
			//Get the graph data
			for($dayOffset = -5; $dayOffset < 1; $dayOffset++)
			{
				echo "STATE.data.push(" . json_encode($db->getData(getDateString($dayOffset))) . "); \n";
				echo "STATE.days.push('" . getDayFormattedString($dayOffset) . "'); \n";
			}		
	?>
	
		
		//Document Ready
		$(document).ready(function() {
			
		
			//Enable Collapse
			$(".collapse").collapse();
			
			//Bar or Pie
			var useBarChart = true;
		
			console.log(STATE.data);
			STATE.dataForToday = STATE.data[STATE.data.length-1];
			
			setupPage(STATE);
			drawDayViewGraph(STATE.dataForToday, "day", 500, useBarChart);			
			drawWeekViewGraphs(STATE.data, STATE.days, "week-", 200, useBarChart); //Data , div prefix, length
				
			//Click  Button
			$("#weekView").hide();
			
			$("#daySelector").click(function(){ 
				$("#dayView").show('fast'); 
				$("#weekView").hide('fast'); 
			});
			
			$("#weekSelector").click(function(){ 
				$("#weekView").show('fast'); 
				$("#dayView").hide('fast'); 
				});
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
		      <li><a href="board.php">Life</a></li>
              <li class="active"><a href="index.php">Home</a></li>
			  <li> <a href="stats.php">Friends</a></li>
              <li><a href="auth/logout.php">Logout</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
	
		<div class="row">
				<div class="page-header">
					<h1>Welcome to Visualize
					<small>Insights into me</small>
					</h1>
			</div>
		</div>
		
		<!-- The timer
		  ======================== -->
		<div class="row">
			<div class="span3">	
				<div id="leftSide">
					<div id="currentEvent" class="well">
						<div id="title"></strong></div>
						<strong><div id="stopwatch" ></div>	</strong>
					</div>

					<button id="finishAction" class="activity-button btn btn-large">Finished</button>

					<div id="activities"></div>

			
					<!-- Completed Activities -->
					<a href="#" data-toggle="collapse" data-target="#completed">Completed</a>
					<div id="completed" class="collapse in"></div>
					
					<!-- Create Activity -->
					<div id="createActivity">
						<a href="" id="createActivityLink">Create Activity</a>
						<div id="createActivityInput" >
							<input type="text" id="activityName" placeholder="Activity Name"/>
							<input type="text" id="goalDuration" placeholder="Goal: Hours per day"/>
							<input type="submit" id="createButton" value="Add"/>
						</div>
					</div>
					
					<div id="createActivityResult"></div>
				</div>
				<div class="spacer" style="height:100px;"></div>
			</div>
			
			<!--  The Graph
			  ===================== -->
			<div class="span9">
				
				<!-- Day View -->
				<div id="dayView">
					<div class="row">
						<div class="span8">
							<div id="day"></div>
						</div>
					</div>
				</div>
				
				<!-- The Week -->
				<div id="weekView">
					<div class="row">
						<div id="week-0" class="weekChart span3"></div>
						<div id="week-1" class="weekChart span3"></div>
						<div id="week-2" class="weekChart span3"></div>
					</div>	
					<div class="row">
						<div id="week-3" class="weekChart span3"></div>
						<div id="week-4" class="weekChart span3"></div>
						<div id="week-5" class="weekChart span3"></div>
					</div>
				</div>
				<!-- Toggle Buttons -->
				<div class="row">
					<div class="span2 offset3">
						<div class="btn-group" data-toggle="buttons-radio">
							<button id="daySelector"  class="btn active">Day</button>
							<button id="weekSelector" class="btn">Week</button>
						</div>
					</div>
				</div>
			</div>
	</div> <!-- /container -->
	
	<!-- Javascript - Placed at the end of the document so pages load faster! -->
	<script src="bootstrap/js/bootstrap-button.js"></script>
	<script src="bootstrap/js/bootstrap-collapse.js"></script>

	
	<!-- Highcharts -->
	<script src="Highcharts-2.2.0/js/highcharts.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/bar-chart-hc.js" ></script>

  </body>
</html>