<?php

$ALLOW_GET_REDIRECT = false;

require "req/variables.php";
require "req/functions.php";
require "sites/router.php";

$domain = $_SERVER['SERVER_NAME'];

//used for development. comment out if not in use.
$domain = "drivetodaycard.com";

//use GET domain if ALLOW_GET_REDIRECT is true and domain is present in the GET request
if($ALLOW_GET_REDIRECT && $_GET['domain']) {
    $domain = $_GET['domain'];
}

$router = new Router();
$site = $router->getSite($domain);

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

<!-- Javascript Debugging Code - gets excluded when $ALLOW_GET_REDIRECT is false -->
<?php if($ALLOW_GET_REDIRECT) { ?>
<script>
var sites = [
    'drivenowcard.com',
    'apcardonline.com',
    'drivecnac.com',
    'drivejdb.com',
    'drivetodaycard.com'
];
var timeoutLoop;

function checkForLoopStart() {
    if(getQueryVariable('loop') === "true") {
        startLoop();
    }
}

function startLoop() {
    timeoutLoop = setTimeout(function() {
        loop();
    }, 5000);
}

function next() {
    loop();
}

function pauseLoop() {
    clearTimeout(timeoutLoop);
}

function loop() {
    var domain = getQueryVariable('domain');
    var ref = window.location.origin;

    var site = findNextSite(domain);
    ref = ref + "/?domain=" + site + "&loop=true";

    window.location = ref;

    return true;
}

function findNextSite(previousSite) {
    var $trigger = false;

    for(var i = 0; i <= sites.length; i++) {
        var site = sites[i];

        if(site === previousSite) {
            $trigger = true;
            continue;
        }

        if(i === sites.length) {
            i = -1;
        }

        if(site && $trigger) {
            return site;
        }
    }
}

function getQueryVariable(variable) {
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }

       return(false);
}

checkForLoopStart();
</script>
<?php } ?>

<!-- style sheet externals -->
<?php echo $site->html->css; ?>
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
