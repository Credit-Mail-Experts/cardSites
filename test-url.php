<?php

 $customerNumber = 123456;

 $urls = array();

require('req/url.php');

GoogleUrlApi::$apiKey = "AIzaSyD9KAmcohWG_gofOMThJGYASrm7qNONSGo";

for($i = 0; $i < 5; $i++) {
	$urls[] = GoogleUrlApi::shorten("http://reports.creditmailexperts.com/view-lead-from-text.php?id=" . $customerNumber);
}	

print_r($urls);