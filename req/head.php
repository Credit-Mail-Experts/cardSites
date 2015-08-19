<?php
require "req/variables.php";
require "req/functions.php";

// parse the ini file into an array
$ini = parse_ini_file("req/settings.ini");

// assign variables from the ini array
$host = $ini["host"];
$username = $ini["username"];
$password = $ini["password"];
$database = $ini["database"];
$salt = $ini["salt"];
$leadEmails = $ini["emails"];

// set the time zone to eastern standard time
date_default_timezone_set("America/New_York");

// instaniate an instance of our class
$database = new database($host, $username, $password, $database);

$query = "SELECT first_name FROM leads";
$database->runQuery($query);

// grab the session if one exists
if (isset($_SESSION["employeeId"])) {
    $employeeId = $_SESSION["employeeId"];
}
?>

<!-- style sheet externals -->
<link href="css/main.css?reload" media="all" type="text/css" rel="stylesheet"/>
<link href="css/jquery-ui.min.css" media="all" type="text/css" rel="stylesheet"/>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link rel="icon" href="favicon.ico" type="image/x-icon">

<!-- javascript externals -->
<script type="text/javascript" src="js/functions.js"></script>
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/jquery-ui.min.js"></script>

<!-- highchart externals -->
<!--<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>-->

<!-- Google Analytics -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-61650150-2', 'auto');
  ga('send', 'pageview');

</script>