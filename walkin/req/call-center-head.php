<?php
require "req/variables.php";
require "req/functions.php";
require "req/acl.php";

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


$acl = new App\Acl($database);

// grab the session if one exists
if (isset($_SESSION["employeeId"])) {
    $employeeId = $_SESSION["employeeId"];
}
?>

<!-- style sheet externals -->
<link href="css/main.css?reload" media="all" type="text/css" rel="stylesheet"/>
<link href="css/jquery-ui.min.css" media="all" type="text/css" rel="stylesheet"/>

<!-- javascript externals -->
<script type="text/javascript" src="js/functions.js"></script>
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/jquery-ui.min.js"></script>

<!-- highchart externals -->
<!--<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>-->