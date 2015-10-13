<?php

include_once('url.php');
GoogleUrlApi::$apiKey = "AIzaSyD9KAmcohWG_gofOMThJGYASrm7qNONSGo";


// Strip the standard AFE XML formated phone numbers to 10 digit plain text for the Conceli ADF format
// Array that contains the characters we will be replacing from the phone number
$phoneReplacementCharacters = array("-", "(", ")");

$homePhoneTenDigit = str_replace($phoneReplacementCharacters, "", $homePhone);
$cellPhoneTenDigit = str_replace($phoneReplacementCharacters, "", $cellPhone);
$workPhoneTenDigit = str_replace($phoneReplacementCharacters, "", $workPhone);

// if the record is a duplicate with different information
if ($duplicateRecord && $customerNumber == "123456" && $customerNumber != "12345") {
    // Query the database for the proper delivery addresses for the lead without CRM addresses
    $query = "SELECT delivery_address, cme_customer_name, delivery_name FROM dealers WHERE dealer_id = $dealerId AND delivery_name <> 'LBP ADF XML'";
    $result = $database->runQuery($query);

    while ($row = mysql_fetch_array($result)) {
        $deliveryAddress[] = $row["delivery_address"];
        $deliveryName[] = "Duplicate Record Email";
        $cmeCustomerName = $row["cme_customer_name"];
    }
} else {
    // Query the database for the proper delivery addresses for the lead
    $query = "SELECT delivery_address, cme_customer_name, delivery_name FROM dealers WHERE dealer_id = $dealerId";
    $result = $database->runQuery($query);

    while ($row = mysql_fetch_array($result)) {
        $deliveryAddress[] = $row["delivery_address"];
        $deliveryName[] = $row["delivery_name"];
        $cmeCustomerName = $row["cme_customer_name"];
    }
}

// Query the database for the proper delivery addresses for the lead
$query = "SELECT text_message_address FROM dealer_delivery_addresses WHERE dealer_id = $dealerId";
$result = $database->runQuery($query);


// Sets a return path for email/text messages that encounter errors
//$emailReturnAddress = "bouncedemails@creditmailexperts.com";
//$emailReturnPath = "-f" . $emailFrom . " -r" . $emailReturnAddress;

while ($row = mysql_fetch_array($result)) {
    $textMessageAddresses[] = $row["text_message_address"];
}

for ($j = 0; $j < count($textMessageAddresses); $j++) {
    //$textMessageSubject = "Lead Detail: $cmeCustomerName - $mailType";
    $textMessageSubject = "";
    $textMessageFrom = "Texts@CreditMailExperts.com";
    $textMessageHeader = "From:" . $textMessageFrom;

    //Sets phone number priority for text message
    if ($cellPhone != "") {
        $textMessagePhoneNumber = "Cell Phone: $cellPhone\n";
    } elseif ($homePhone != "") {
        $textMessagePhoneNumber = "Home Phone: $homePhone\n";
    } elseif ($workPhone != "") {
        $textMessagePhoneNumber = "Work Phone: $workPhone\n";
    }

    //Checks if there is an email address
    if ($email != "") {
        $textMessageEmail = "Email: $email";
    } else {
        $textMessageEmail = "";
    }

    //Checks if an appointment has been set and sets text message
    if ($appointmentDate != "") {
        $textMessage = "CME has set a new appointment for $cmeCustomerName with $firstName $lastName on $appointmentDate at $appointmentTime.";
    } else {

       $longUrl = "http://reports.creditmailexperts.com/view-lead-from-text.php?id=" . $customerNumber;

	   $url = GoogleUrlApi::shorten($longUrl);

	   $newText = "New lead from CME!  Click here for lead info..." . $url;

    }

    $textMessageTo = $textMessageAddresses[$j];

    mail($textMessageTo, $textMessageSubject, $newText, $textMessageHeader, $emailReturnAddress);
}

// Depricated 2015-09-18
/*
// Override the delivery address if it is a web lead that has a dealer with appointment hours
if ($callerId == "Web Lead") {
    $query = "SELECT dealer_id FROM dealer_appointment_hours WHERE dealer_id = $dealerId";
    $result = $database->runQuery($query);

    if (mysql_num_rows($result) > 0) {
        unset($deliveryAddress);
        unset($deliveryName);

        $deliveryAddress[] = "bjackson@jcgna.com, jackson@dezmondwright.com";
        $deliveryName[] = "Text Based Email";
    }
}*/

// Set the email from and header
$emailFrom = "deliveryagent@creditmailexperts.com";
$emailHeader = "From:" . $emailFrom . "\r\n";
$emailHeader .= 'Content-Type: text/plain' . "\r\n";

// Switch statement to get the full length name of the mailtype for the ADF format
switch ($mailType) {
    case "C":
        $mailTypeFull = "Auto Credit Mail";
        break;
    case "D":
        $mailTypeFull = "Discharged Bankruptcy";
        break;
    case "F":
        $mailTypeFull = "Filed Bankruptcy";
        break;
    case "T":
        $mailTypeFull = "Trigger";
        break;
    case "T2":
        $mailTypeFull = "Near Prime Trigger";
        break;
    case "R1":
    case "R2":
        $mailTypeFull = "BHPH";
        break;
    case "CP01":
    case "CP02":
    case "CP03":
    case "CP04":
    case "CP05":
    case "CP06":
    case "CP07":
    case "CP08":
    case "CP09":
    case "CP10":
        $mailTypeFull = "Credit Predictor";
        break;
    case "FFC7BK":
    	$mailTypeFull = "Fresh Filed Chapter 7 Bankruptcies";
        break;
    case "FDC7BK":
    	$mailTypeFull = "Fresh Discharged Chapter 7 Bankruptcies";
        break;
    case "DC7BK":
    	$mailTypeFull = "Discharged Chapter 7 Bankruptcies";
        break;
    case "OC13BK":
    	$mailTypeFull = "Open Chapter 13 Bankruptcies";
        break;
    default:
        $mailTypeFull = $mailType;
        break;
}

//Sets strtotime function to appointmentTime if appointmentDate is set
if ($appointmentDate != "") {
    $appointmentTime = date('h:i a', strtotime($appointmentTime));
} else {
    $appointmentTime = "";
}

// Set the email subject
$emailSubject = "Lead Detail: $cmeCustomerName - $mailType";

if(!isset($warmTransfer)) {
   $warmTransfer = "No";
}

// Send a new email for each deliveryAddress pulled from the database
for ($j = 0; $j < count($deliveryAddress); $j++) {

    // Additional duplicate check
    $query = "SELECT allow_duplicates FROM dealer_delivery_addresses WHERE dealer_id = $dealerId AND delivery_address = '$deliveryAddress[$j]' AND delivery_name = '$deliveryName[$j]'";
    $result = $database->runQuery($query);

    while ($row = mysql_fetch_array($result)) {
        $allowDuplicates = $row["allow_duplicates"];
    }

    // If allow duplicates its set to a strict NEVER
    if ($allowDuplicates == 'no') {
        $query = "SELECT customer_number FROM leads WHERE customer_number = '$customerNumber' AND dealer_id = '$dealerId'";
        $result = $database->runQuery($query);

        // If a customer number for the dealer exists in the database we're going to skip it as a duplicate
        if (mysql_num_rows($result) > 0) {
            // Move on to the next delivery address
            continue;
        }
    }



    // Set the email to the delivery address of the current loop itteration
    $emailTo = $deliveryAddress[$j];

    // Plain Text Format
    if ($deliveryName[$j] == "Text Based Email") {

        $emailMessage = "Lead ID:L-00000\n"
                . "Customer Number = $dealerId\n"
                . "Last Name = $lastName\n"
                . "First Name = $firstName\n"
                . "Middle Name = $middleName\n"
                . "Address = $addressOne\n"
                . "Address2 = $addressTwo\n"
                . "City = $city\n"
                . "State = $state\n"
                . "Zip Code = $zip\n"
                . "Home Phone = $homePhone\n"
                . "Mobile Phone = $cellPhone\n"
                . "Email = $email\n"
                . "Work Phone = $workPhone\n"
                . "Pin Code = $customerNumber\n"
                . "Mail Type = $mailType\n"
                . "Comment = $comment\n"
                . "Caller ID = $callerId\n"
                . "Appointment Date/Time = $appointmentDate $appointmentTime\n"
                . "Appointment Refusal Reason = $appointmentRefusalReason\n"
                . "Advertising Source = $sourceType\n"
                . "Warm Transfer = $warmTransfer";

        mail($emailTo, $emailSubject, $emailMessage, $emailHeader, $emailReturnPath);

        // ADF format
    } else if ($deliveryName[$j] == "JDB BDC") {

        $emailMessage = "Lead ID:L-00000\n"
                . "Customer Number = $dealerId\n"
                . "Last Name = $lastName\n"
                . "First Name = $firstName\n"
                . "Middle Name = $middleName\n"
                . "Address = $addressOne\n"
                . "Address2 = $addressTwo\n"
                . "City = $city\n"
                . "State = $state\n"
                . "Zip Code = $zip\n"
                . "Home Phone = $homePhone\n"
                . "Mobile Phone = $cellPhone\n"
                . "Email = $email\n"
                . "Work Phone = $workPhone\n"
                . "Pin Code = $customerNumber\n"
                . "Mail Type = $mailType\n"
                . "Comment = $comment\n"
                . "Caller ID = $callerId\n"
                . "Appointment Date/Time = $appointmentDate $appointmentTime\n"
                . "Appointment Refusal Reason = $appointmentRefusalReason\n"
                . "Advertising Source = $sourceType\n"
                . "Warm Transfer = $warmTransfer\n"
                . "Lead Type = JDB BDC";

        mail($emailTo, $emailSubject, $emailMessage, $emailHeader, $emailReturnPath);

        // ADF format
    } else if ($deliveryName[$j] == "LBP ADF XML") {
        $emailMessage = "<?xml version=\"1.0\"?>\n"
                . "<?ADF version=\"1.0\"?>\n"
                . "<adf>\n"
                . "<prospect>\n"
                . "<id sequence=\"00002\" source=\"CME LBP\"></id>\n"
                . "<vehicle interest=\"buy\" status=\"used\">\n"
                . "<year />\n"
                . "<make />\n"
                . "<model />\n"
                . "</vehicle>\n"
                . "<customer>\n"
                . "<contact>\n"
                . "<name part=\"first\">$firstName</name>\n"
                . "<name part=\"last\">$lastName</name>\n"
                . "<email>$email</email>\n"
                . "<phone type=\"voice\" time=\"day\">$cellPhone</phone>\n"
                . "<phone type=\"voice\" time=\"evening\">$homePhone</phone>\n"
                . "<address>\n"
                . "<street line=\"1\">$addressOne</street>\n"
                . "<city>$city</city>\n"
                . "<regioncode>$state</regioncode>\n"
                . "<postalcode>$zip</postalcode>\n"
                . "<country>US</country>\n"
                . "</address>\n"
                . "</contact>\n"
                . "<comments>\n"
                . "ANI/Called From Number:$callerId;\n"
                . "Appointment:$appointmentDate $appointmentTime;\n"
                . "Pin #:$customerNumber;\n"
                . "Refusal Reason #:$appointmentRefusalReason;\n"
                . "Mail Type:$mailType;\n"
                . "Years on Job:;\n"
                . "Months on Job:;\n"
                . "Work Phone:$workPhone;\n"
                . "Monthly Gross:;\n"
                . "Credit Check:;\n"
                . "DOB:;\n"
                . "Rent/Own:;\n"
                . "Housing Payment:\n"
                . "Years at Residence:;\n"
                . "Months at Residence:;\n"
                . "</comments>\n"
                . "</customer>\n"
                . "<vendor>\n"
                . "<vendorname>$cmeCustomerName - $mailTypeFull</vendorname>\n"
                . "<contact primarycontact=\"1\">\n"
                . "<name part=\"full\">CME Lead</name>\n"
                . "<phone type=\"voice\" time=\"morning\" />\n"
                . "<address>\n"
                . "<street line=\"1\"></street>\n"
                . "<city />\n"
                . "<regioncode />\n"
                . "<postalcode />\n"
                . "<country>US</country>\n"
                . "<url />\n"
                . "</address>\n"
                . "</contact>\n"
                . "</vendor>\n"
                . "<provider>\n"
                . "<name part=\"full\">$cmeCustomerName</name>\n"
                . "<service>$mailTypeFull</service>\n"
                . "<url>http://www.creditmailexperts.com</url>\n"
                . "<email>todd@creditmailexperts.com</email>\n"
                . "<phone>269-488-9925</phone>\n"
                . "<contact primary=\"1\">\n"
                . "<name part=\"full\">Todd Urbanowicz</name>\n"
                . "<email></email>\n"
                . "<phone type=\"voice\" time=\"day\"></phone>\n"
                . "<address>\n"
                . "<street line=\"1\"></street>\n"
                . "<city />\n"
                . "<regioncode>MI</regioncode>\n"
                . "<postalcode />\n"
                . "<country>US</country>\n"
                . "</address>\n"
                . "</contact>\n"
                . "</provider>\n"
                . "</prospect>\n"
                . "</adf>\n";

        mail($emailTo, $emailSubject, $emailMessage, $emailHeader, $emailReturnPath);

        // Conicelli ADF format (AFE format with the standard phone fields removed and replaced by the ten digit phones all next to eachother)
    } else if ($deliveryName[$j] == "Conicelli ADF") {
        $emailMessage = "<?xml version=\"1.0\"?>\n"
                . "<?ADF version=\"1.0\"?>\n"
                . "<adf>\n"
                . "<prospect>\n"
                . "<id sequence=\"00002\" source=\"CME LBP\"></id>\n"
                . "<vehicle interest=\"buy\" status=\"used\">\n"
                . "<year />\n"
                . "<make />\n"
                . "<model />\n"
                . "</vehicle>\n"
                . "<customer>\n"
                . "<contact>\n"
                . "<name part=\"first\">$firstName</name>\n"
                . "<name part=\"last\">$lastName</name>\n"
                . "<email>$email</email>\n"
                . "<phone type=\"home\">$homePhoneTenDigit</phone>\n"
                . "<phone type=\"cell\">$cellPhoneTenDigit</phone>\n"
                . "<phone type=\"work\">$workPhoneTenDigit</phone>\n"
                . "<address>\n"
                . "<street line=\"1\">$addressOne</street>\n"
                . "<city>$city</city>\n"
                . "<regioncode>$state</regioncode>\n"
                . "<postalcode>$zip</postalcode>\n"
                . "<country>US</country>\n"
                . "</address>\n"
                . "</contact>\n"
                . "<comments>\n"
                . "ANI/Called From Number:$callerId;\n"
                . "Pin #:$customerNumber;\n"
                . "Mail Type:$mailType;\n"
                . "Years on Job:;\n"
                . "Months on Job:;\n"
                . "Monthly Gross:;\n"
                . "Credit Check:;\n"
                . "DOB:;\n"
                . "Rent/Own:;\n"
                . "Housing Payment:\n"
                . "Years at Residence:;\n"
                . "Months at Residence:;\n"
                . "</comments>\n"
                . "</customer>\n"
                . "<vendor>\n"
                . "<vendorname>$cmeCustomerName - $mailTypeFull</vendorname>\n"
                . "<contact primarycontact=\"1\">\n"
                . "<name part=\"full\">CME Lead</name>\n"
                . "<phone type=\"voice\" time=\"morning\" />\n"
                . "<address>\n"
                . "<street line=\"1\"></street>\n"
                . "<city />\n"
                . "<regioncode />\n"
                . "<postalcode />\n"
                . "<country>US</country>\n"
                . "<url />\n"
                . "</address>\n"
                . "</contact>\n"
                . "</vendor>\n"
                . "<provider>\n"
                . "<name part=\"full\">$cmeCustomerName</name>\n"
                . "<service>$mailTypeFull</service>\n"
                . "<url>http://www.creditmailexperts.com</url>\n"
                . "<email>todd@creditmailexperts.com</email>\n"
                . "<phone>269-488-9925</phone>\n"
                . "<contact primary=\"1\">\n"
                . "<name part=\"full\">Todd Urbanowicz</name>\n"
                . "<email></email>\n"
                . "<phone type=\"voice\" time=\"day\"></phone>\n"
                . "<address>\n"
                . "<street line=\"1\"></street>\n"
                . "<city />\n"
                . "<regioncode>MI</regioncode>\n"
                . "<postalcode />\n"
                . "<country>US</country>\n"
                . "</address>\n"
                . "</contact>\n"
                . "</provider>\n"
                . "</prospect>\n"
                . "</adf>\n";
        mail($emailTo, $emailSubject, $emailMessage, $emailHeader, $emailReturnPath);

        // VOISYS XML
    } else if ($deliveryName[$j] == "VOISYS XML") {
        $emailMessage = "<?xml version=\"1.0\"?>\n"
                . "<CreditMailExpertsXML>\n"
                . "<lead>\n"
                . "<datestamp>$date</datestamp>\n"
                . "<timestamp>$time</timestamp>\n"
                . "<customername>$cmeCustomerName</customername>\n"
                . "<servicename>Internet Leads</servicename>\n"
                . "<leadno>00000</leadno>\n"
                . "<PersonalInformation>\n"
                . "<LastName>$lastName</LastName>\n"
                . "<FirstName>$firstName</FirstName>\n"
                . "<Address_one>$addressOne</Address_one>\n"
                . "<Address_two>$addressTwo</Address_two>\n"
                . "<City>$city</City>\n"
                . "<State>$state</State>\n"
                . "<ZipCode>$zip</ZipCode>\n"
                . "<HomePhone>$homePhone</HomePhone>\n"
                . "<WorkPhone>$workPhone</WorkPhone>\n"
                . "<CellPhone>$cellPhone</CellPhone>\n"
                . "<Email>$email</Email>\n"
                . "</PersonalInformation>\n"
                . "<ApplicationInformation>\n"
                . "<PriorityCode>$customerNumber</PriorityCode>\n"
                . "<MailType>$mailType</MailType>\n"
                . "<Comment>$comment</Comment>\n"
                . "</ApplicationInformation>\n"
                . "</lead>\n"
                . "<provider>\n"
                . "<name>Credit Mail Experts</name>\n"
                . "<url>http://creditmailexperts.com</url>\n"
                . "<email>todd@creditmailexperts.com</email>\n"
                . "<phone>269-488-9925</phone>\n"
                . "</provider>\n"
                . "</CreditMailExpertsXML>";
        mail($emailTo, $emailSubject, $emailMessage, $emailHeader, $emailReturnPath);

        // Conicelli XML
    } else if ($deliveryName[$j] == "Conicelli XML") {
        $emailMessage = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n"
                . "<Lead>\n"
                . "<LeadType>$sourceType</LeadType>\n"
                . "<LeadDate>$date $time</LeadDate>\n"
                . "<FirstName>$firstName</FirstName>\n"
                . "<MiddleInitial>$middleName</MiddleInitial>\n"
                . "<LastName>$lastName</LastName>\n"
                . "<HomePhone>$homePhoneTenDigit</HomePhone>\n"
                . "<CellPhone>$cellPhoneTenDigit</CellPhone>\n"
                . "<WorkPhone>$workPhoneTenDigit</WorkPhone>\n"
                . "<EmailAddress>$email</EmailAddress>\n"
                . "<BestContactMethod></BestContactMethod>\n"
                . "<BestContactTime></BestContactTime>\n"
                . "<OptOut></OptOut>\n"
                . "<LeadID>$customerNumber</LeadID>\n"
                . "<Source>Credit Mail Experts</Source>\n"
                . "<PreferredVehicleType></PreferredVehicleType>\n"
                . "<VehicleYear></VehicleYear>\n"
                . "<VehicleMake></VehicleMake>\n"
                . "<VehicleModel></VehicleModel>\n"
                . "<VehicleTrim></VehicleTrim>\n"
                . "<SSN></SSN>\n"
                . "<DateOfBirth></DateOfBirth>\n"
                . "<BankName></BankName>\n"
                . "<HaveCheckingAccount></HaveCheckingAccount>\n"
                . "<HaveSavingsAccount></HaveSavingsAccount>\n"
                . "<DownPayment></DownPayment>\n"
                . "<Authorization></Authorization>\n"
                . "<ApplicantType></ApplicantType>\n"
                . "<CosignerAvailable></CosignerAvailable>\n"
                . "<RelatedLeadID></RelatedLeadID>\n"
                . "<ResidenceType></ResidenceType>\n"
                . "<Address1>$addressOne</Address1>\n"
                . "<Address2>$addressTwo</Address2>\n"
                . "<City>$city</City>\n"
                . "<State>$state</State>\n"
                . "<Zipcode>$zip</Zipcode>\n"
                . "<Country>USA</Country>\n"
                . "<YearsAtAddress></YearsAtAddress>\n"
                . "<MonthsAtAddress></MonthsAtAddress>\n"
                . "<HousingPayment></HousingPayment>\n"
                . "<PrevAddress1></PrevAddress1>\n"
                . "<PrevAddress2></PrevAddress2>\n"
                . "<PrevCity></PrevCity>\n"
                . "<PrevState></PrevState>\n"
                . "<PrevZipcode></PrevZipcode>\n"
                . "<YearsAtPrevAddress></YearsAtPrevAddress>\n"
                . "<MonthsAtPrevAddress></MonthsAtPrevAddress>\n"
                . "<EmploymentType></EmploymentType>\n"
                . "<Employer></Employer>\n"
                . "<Occupation></Occupation>\n"
                . "<EmployerPhone></EmployerPhone>\n"
                . "<YearsAtEmployer></YearsAtEmployer>\n"
                . "<MonthsAtEmployer></MonthsAtEmployer>\n"
                . "<MonthlyIncome></MonthlyIncome>\n"
                . "<OtherIncome></OtherIncome>\n"
                . "<OtherIncomeSource></OtherIncomeSource>\n"
                . "<PrevEmployer></PrevEmployer>\n"
                . "<PrevEmployerPhone></PrevEmployerPhone>\n"
                . "<YearsAtPrevEmployer></YearsAtPrevEmployer>\n"
                . "<MonthsAtPrevEmployer></MonthsAtPrevEmployer>\n"
                . "<TradeVIN></TradeVIN>\n"
                . "<TradeYear></TradeYear>\n"
                . "<TradeMake></TradeMake>\n"
                . "<TradeModel></TradeModel>\n"
                . "<TradeMileage></TradeMileage>\n"
                . "<TradeAmountOwed></TradeAmountOwed>\n"
                . "<TradeLienHolder></TradeLienHolder>\n"
                . "</Lead>";

        mail($emailTo, $emailSubject, $emailMessage, $emailHeader, $emailReturnPath);
        // Wheel City XML
    } elseif ($deliveryName[$j] == "Daily Report") {

        // CRM Email setup without sending an email (Paul's request)
    } elseif ($deliveryName[$j] == "ADF XML Minus Email") {
        $emailMessage = "<?xml version=\"1.0\"?>\n"
                . "<?ADF version=\"1.0\"?>\n"
                . "<adf>\n"
                . "<prospect>\n"
                . "<id sequence=\"00002\" source=\"CME LBP\"></id>\n"
                . "<vehicle interest=\"buy\" status=\"used\">\n"
                . "<year />\n"
                . "<make />\n"
                . "<model />\n"
                . "</vehicle>\n"
                . "<customer>\n"
                . "<contact>\n"
                . "<name part=\"first\">$firstName</name>\n"
                . "<name part=\"last\">$lastName</name>\n"
                . "<email></email>\n"
                . "<phone type=\"voice\" time=\"day\">$cellPhone</phone>\n"
                . "<phone type=\"voice\" time=\"evening\">$homePhone</phone>\n"
                . "<address>\n"
                . "<street line=\"1\">$addressOne</street>\n"
                . "<city>$city</city>\n"
                . "<regioncode>$state</regioncode>\n"
                . "<postalcode>$zip</postalcode>\n"
                . "<country>US</country>\n"
                . "</address>\n"
                . "</contact>\n"
                . "<comments>\n"
                . "ANI/Called From Number:$callerId;\n"
                . "Appointment:$appointmentDate $appointmentTime;\n"
                . "Pin #:$customerNumber;\n"
                . "Refusal Reason #:$appointmentRefusalReason;\n"
                . "Mail Type:$mailType;\n"
                . "Years on Job:;\n"
                . "Months on Job:;\n"
                . "Work Phone:$workPhone;\n"
                . "Monthly Gross:;\n"
                . "Credit Check:;\n"
                . "DOB:;\n"
                . "Rent/Own:;\n"
                . "Housing Payment:\n"
                . "Years at Residence:;\n"
                . "Months at Residence:;\n"
                . "</comments>\n"
                . "</customer>\n"
                . "<vendor>\n"
                . "<vendorname>$cmeCustomerName - $mailTypeFull</vendorname>\n"
                . "<contact primarycontact=\"1\">\n"
                . "<name part=\"full\">CME Lead</name>\n"
                . "<phone type=\"voice\" time=\"morning\" />\n"
                . "<address>\n"
                . "<street line=\"1\"></street>\n"
                . "<city />\n"
                . "<regioncode />\n"
                . "<postalcode />\n"
                . "<country>US</country>\n"
                . "<url />\n"
                . "</address>\n"
                . "</contact>\n"
                . "</vendor>\n"
                . "<provider>\n"
                . "<name part=\"full\">$cmeCustomerName</name>\n"
                . "<service>$mailTypeFull</service>\n"
                . "<url>http://www.creditmailexperts.com</url>\n"
                . "<email>todd@creditmailexperts.com</email>\n"
                . "<phone>269-488-9925</phone>\n"
                . "<contact primary=\"1\">\n"
                . "<name part=\"full\">Todd Urbanowicz</name>\n"
                . "<email></email>\n"
                . "<phone type=\"voice\" time=\"day\"></phone>\n"
                . "<address>\n"
                . "<street line=\"1\"></street>\n"
                . "<city />\n"
                . "<regioncode>MI</regioncode>\n"
                . "<postalcode />\n"
                . "<country>US</country>\n"
                . "</address>\n"
                . "</contact>\n"
                . "</provider>\n"
                . "</prospect>\n"
                . "</adf>\n";

        mail($emailTo, $emailSubject, $emailMessage, $emailHeader, $emailReturnPath);

        // Duplicate Record Email (send if email address is already in database)
    } elseif ($deliveryName[$j] == "Duplicate Record Email") {
        $emailSubject = "CME Duplicate Lead Alert – Card Number $customerNumber";

        $emailMessage = "The lead with card number, $customerNumber, ";

        if ($dupFirstName || $dupLastName || $dupEmail || $dupHomePhone || $dupWorkPhone || $dupCellPhone || $dupAddressOne || $dupAddressTwo || $dupCity || $dupState || $dupZip) {
            $emailMessage .= "has submitted additional information...\n\n";
        } else {
            $emailMessage .= "has activated their card again.  This may be an indication that they are still waiting to hear from you.\n\n";
        }

        if ($dupFirstName) {
            $emailMessage .= "First Name: $dupFirstName\n";
        }
        if ($dupLastName) {
            $emailMessage .= "Last Name: $dupLastName\n";
        }
        if ($dupEmail) {
            $emailMessage .= "Email: $dupEmail\n";
        }
        if ($dupHomePhone) {
            $emailMessage .= "Home Phone: $dupHomePhone\n";
        }
        if ($dupWorkPhone) {
            $emailMessage .= "Work Phone: $dupWorkPhone\n";
        }
        if ($dupCellPhone) {
            $emailMessage .= "Cell Phone: $dupCellPhone\n";
        }
        if ($dupAddressOne) {
            $emailMessage .= "Address One: $dupAddressOne\n";
        }
        if ($dupAddressTwo) {
            $emailMessage .= "Address Two: $dupAddressTwo\n";
        }
        if ($dupCity) {
            $emailMessage .= "City: $dupCity\n";
        }
        if ($dupState) {
            $emailMessage .= "State: $dupState\n";
        }
        if ($dupZip) {
            $emailMessage .= "Zip: $dupZip\n";
        }

        mail($emailTo, $emailSubject, $emailMessage, $emailHeader, $emailReturnPath);

    } else {
        $emailMessage = "<?xml version=\"1.0\"?>\n"
                . "<?ADF version=\"1.0\"?>\n"
                . "<adf>\n"
                . "<prospect>\n"
                . "<id sequence=\"00002\" source=\"CME LBP\"></id>\n"
                . "<vehicle interest=\"buy\" status=\"used\">\n"
                . "<year />\n"
                . "<make>$mailType</make>\n"
                . "<model />\n"
                . "</vehicle>\n"
                . "<customer>\n"
                . "<contact>\n"
                . "<name part=\"first\">$firstName</name>\n"
                . "<name part=\"last\">$lastName</name>\n"
                . "<email>$email</email>\n"
                . "<phone type=\"voice\" time=\"day\">$cellPhone</phone>\n"
                . "<phone type=\"voice\" time=\"evening\">$homePhone</phone>\n"
                . "<address>\n"
                . "<street line=\"1\">$addressOne</street>\n"
                . "<city>$city</city>\n"
                . "<regioncode>$state</regioncode>\n"
                . "<postalcode>$zip</postalcode>\n"
                . "<country>US</country>\n"
                . "</address>\n"
                . "</contact>\n"
                . "<comments>\n"
                . "ANI/Called From Number:$callerId;\n"
                . "Pin #:$customerNumber;\n"
                . "Years on Job:;\n"
                . "Months on Job:;\n"
                . "Work Phone:$workPhone;\n"
                . "Monthly Gross:;\n"
                . "Credit Check:;\n"
                . "DOB:;\n"
                . "Rent/Own:;\n"
                . "Housing Payment:\n"
                . "Years at Residence:;\n"
                . "Months at Residence:;\n"
                . "</comments>\n"
                . "</customer>\n"
                . "<vendor>\n"
                . "<vendorname>$cmeCustomerName</vendorname>\n"
                . "<contact primarycontact=\"1\">\n"
                . "<name part=\"full\">CME Lead</name>\n"
                . "<phone type=\"voice\" time=\"morning\" />\n"
                . "<address>\n"
                . "<street line=\"1\"></street>\n"
                . "<city />\n"
                . "<regioncode />\n"
                . "<postalcode />\n"
                . "<country>US</country>\n"
                . "<url />\n"
                . "</address>\n"
                . "</contact>\n"
                . "</vendor>\n"
                . "<provider>\n"
                . "<name part=\"full\">CREDIT MAIL EXPERTS</name>\n"
                . "<service>CME LBP</service>\n"
                . "<url>http://www.creditmailexperts.com</url>\n"
                . "<email>todd@creditmailexperts.com</email>\n"
                . "<phone>269-488-9925</phone>\n"
                . "<contact primary=\"1\">\n"
                . "<name part=\"full\">Todd Urbanowicz</name>\n"
                . "<email></email>\n"
                . "<phone type=\"voice\" time=\"day\"></phone>\n"
                . "<address>\n"
                . "<street line=\"1\"></street>\n"
                . "<city />\n"
                . "<regioncode>MI</regioncode>\n"
                . "<postalcode />\n"
                . "<country>US</country>\n"
                . "</address>\n"
                . "</contact>\n"
                . "</provider>\n"
                . "</prospect>\n"
                . "</adf>";
        mail($emailTo, $emailSubject, $emailMessage, $emailHeader, $emailReturnPath);
    }
}

// also mail to these guys on EVERY lead sent out
$emailTo = "leads@creditmailexperts.com";

$emailMessage = "Lead ID:L-00000\n"
        . "Customer Number = $dealerId\n"
        . "Last Name = $lastName\n"
        . "First Name = $firstName\n"
        . "Middle Name = $middleName\n"
        . "Address = $addressOne\n"
        . "Address2 = $addressTwo\n"
        . "City = $city\n"
        . "State = $state\n"
        . "Zip Code = $zip\n"
        . "Home Phone = $homePhone\n"
        . "Mobile Phone = $cellPhone\n"
        . "Email = $email\n"
        . "Work Phone = $workPhone\n"
        . "Pin Code = $customerNumber\n"
        . "Mail Type = $mailType\n"
        . "Comment = $comment\n"
        . "Caller ID = $callerId\n"
        . "Appointment Date/Time = $appointmentDate $appointmentTime\n"
        . "Appointment Refusal Reason = $appointmentRefusalReason\n"
        . "Advertising Source = $sourceType\n"
        . "Warm Transfer = $warmTransfer";

mail($emailTo, $emailSubject, $emailMessage, $emailHeader, $emailReturnPath);
?>
