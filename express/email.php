<?php
session_start();
ob_start();
?>
<!DOCTYPE html>


<html>
    <head>
        <title>ApCardOnline - Email</title>

        <?php
        require "req/head.php";
        ?>
    </head>
    <body style="background-color: white; background-image: none;">

        <?php
        /*
         * Notes for me!
         * 
         * The $callerId & $customerNumber never have quotes added to them so they are going into the database without quotes ''
         * 
         * This doesn't seem to be an issue for some reason
         */
		 

        $mailbox = "{mail.creditmailexperts.com:143/novalidate-cert}INBOX";
        $username = "leadparse@creditmailexperts.com";
        $password = "oD2E&bi%";

        $skippedCounter = 0;

        $imapStream = imap_open($mailbox, $username, $password);

        if ($imapStream) {
            $messageCount = imap_num_msg($imapStream);
            
            if ($messageCount > 10) {
                $messageCount = 10;
            }

            for ($counterA = 1; $counterA <= $messageCount; $counterA++) {
                $body = imap_qprint(imap_body($imapStream, $counterA));
                $body = base64_decode($body);
                //$body = imap_fetchbody($imapStream, $counterA, 1);
                // grab header information and from address
                $header = imap_headerinfo($imapStream, $counterA);
                $fromAddress = $header->from[0]->mailbox . "@" . $header->from[0]->host;
				

                // We were getting leads from R&R that were causing the simplexml_load_string to fail
                // This was implemented to find and replace all ampersands with the encoded version of the symbol to keep it from failing
                $body = str_replace("&", "&amp;", $body);
                
                echo $body;
                

                // Error handling for simple XML so that we can skip broken leads
                // http://stackoverflow.com/questions/1307275/simplexml-error-handling-php
                if (@simplexml_load_string($body)) {
                    $customers = simplexml_load_string($body);
                } else {
                    echo "broken<br />";
                    echo $body;
                    
                    $brokenLeadSubject = "ATTN: Broken Lead Received!";
                    $brokenLeadFrom = "Warnings@CreditMailExperts.com";
                    $brokenLeadHeader = "From:" . $brokenLeadFrom;
                    $brokenLeadMessage = "The system received a broken lead and automatically skipped parsing it. Below you can find the data related to the lead.\n\n $header->subject \n\n $body";

                    $brokenLeadTo = "todd@creditmailexperts.com, bmanhard@gmail.com";

                    mail($brokenLeadTo, $brokenLeadSubject, $brokenLeadMessage, $brokenLeadHeader);
                    continue;
                }
				
				
				


                $date = $database->dbPrepare(date("Y-m-d"));
                $time = $database->dbPrepare(date("H:i:s"));

                // initialize some variables that may or may not be retreived from the email
                $email = NULL;
                $addressTwo = NULL;
                $workPhone = NULL;

                $appointmentDate = NULL;
                $appointmentTime = NULL;
                $appointmentRefusalReason = NULL;


                $firstName = $customers->prospect->customer->contact->name[0];
                $lastName = $customers->prospect->customer->contact->name[1];
                $email = $customers->prospect->customer->contact->email;
                $cellPhone = $customers->prospect->customer->contact->phone[0];
                $homePhone = $customers->prospect->customer->contact->phone[1];
                $addressOne = $customers->prospect->customer->contact->address->street;
                $city = $customers->prospect->customer->contact->address->city;
                $state = $customers->prospect->customer->contact->address->regioncode;
                $zip = $customers->prospect->customer->contact->address->postalcode;



                // Set the source based on the XML source. It might be a web lead returning back to the system through the toll free call center.
                // This variable is stored as an attribute so we have to extract it as such
                $sourceAttributes = $customers->prospect->id->attributes();

                if ($sourceAttributes['source'] == "Web") {
                    $source = "Web";
                    $sourceType = "";
                    $advertisingSource = "Web";
                } else {
                    $source = "Toll-Free";
                    $sourceType = "ITM Inbound";
                    $advertisingSource = "Toll-Free";
                }

                // Use substr function to grab the customer number out of the comments (Not in XML)
                $customerNumber = substr($body, strpos($body, "#:") + 2, 15);
                $customerNumber = substr($customerNumber, 0, strpos($customerNumber, ";"));

                // Grab the warm transfer option from the comments
                $warmTransfer = substr($body, strpos($body, "Transfer:") + 9, 10);
                $warmTransfer = substr($warmTransfer, 0, strpos($warmTransfer, ";"));

                // Use substr function to grab the called from number or email (callerId) from the comments (Not in XML)
                $callerId = substr($body, strpos($body, "From Number:") + 13, 15);
                $callerId = substr($callerId, 0, strpos($callerId, ";"));

                // Grab the appointment date/time field
                $appointmentDate = substr($body, strpos($body, "Appointment:") + 12, 25);
                $appointmentDate = substr($appointmentDate, 0, strpos($appointmentDate, ";"));

                // If an appointment date exists than seperate and parse the date/time into variables
                if (strlen($appointmentDate) > 5) {
                    $appointmentDate = substr($appointmentDate, 0, 10);

                    $appointmentTime = substr($body, strpos($body, "Appointment:") + 12, 25);
                    $appointmentTime = substr($appointmentTime, 11, 8);

                    // If the field is blank than just set both of the variables to null
                } else {
                    $appointmentDate = NULL;
                    $appointmentTime = NULL;
                }

                $appointmentRefusalReason = substr($body, strpos($body, "Refusal Reason:") + 15, 255);
                $appointmentRefusalReason = substr($appointmentRefusalReason, 0, strpos($appointmentRefusalReason, ";"));
                
                // Set appointment refusal reason to NULL if appointment date exists
                if ($appointmentDate != NULL) {
                    $appointmentRefusalReason = NULL;
                }

                $query = "SELECT fico, store, mail_date, mail_type, dealer_id, middle_name, address_two FROM customers WHERE customer_number = '$customerNumber'";
                $result = $database->runQuery($query);

                if (mysql_num_rows($result) == 0) {
                    $skippedCounter++;
                    echo "$skippedCounter entries skipped due to customer number $customerNumber not existing in database!";
                    continue;
                }

                while ($row = mysql_fetch_array($result)) {
                    $fico = $database->dbPrepare($row["fico"]);
                    $store = $database->dbPrepare($row["store"]);
                    $mailType = $database->dbPrepare($row["mail_type"]);
                    $mailDate = $database->dbPrepare($row["mail_date"]);
                    $dealerId = $database->dbPrepare($row["dealer_id"]);
                    $middleName = $database->dbPrepare($row["middle_name"], "ucfirst");
                    $addressTwo = $database->dbPrepare($row["address_two"], "ucwords");
                }

                // For some reason this was being prepared with a carriage return at the end of the line. No idea why but an escaped \r check will remove it
                $store = str_replace("\\r", "", $store);

                $firstName = $database->dbPrepare($firstName, "ucfirst");
                $lastName = $database->dbPrepare($lastName, "ucfirst");
                $homePhone = $database->dbPrepare($homePhone);
                $cellPhone = $database->dbPrepare($cellPhone);
                $workPhone = $database->dbPrepare($workPhone);
                $email = $database->dbPrepare($email, "lower");
                $addressOne = $database->dbPrepare($addressOne, "ucwords");
                $city = $database->dbPrepare($city, "ucwords");
                $state = $database->dbPrepare($state, "lower");
                $zip = $database->dbPrepare($zip);

                $warmTransfer = $database->dbPrepare($warmTransfer, "lower");

                $callerId = $database->dbPrepare($callerId);

                $source = $database->dbPrepare($source);
                $sourceType = $database->dbPrepare($sourceType);

                $appointmentDate = $database->dbPrepare($appointmentDate);
                $appointmentTime = $database->dbPrepare($appointmentTime);
                $appointmentRefusalReason = $database->dbPrepare($appointmentRefusalReason);

                // insert everything into the database
                $query = "INSERT INTO leads (appointment_date, appointment_time, appointment_refusal_reason, warm_transfer, caller_id, source_type, mail_date, customer_number, date, time, source, mail_type, fico, store, dealer_id, first_name, middle_name, last_name, home_phone, work_phone, cell_phone, email, address_one, address_two, city, state, zip) VALUES ($appointmentDate, $appointmentTime, $appointmentRefusalReason, $warmTransfer, $callerId, $sourceType, $mailDate, $customerNumber, $date, $time, $source, $mailType, $fico, $store, $dealerId, $firstName, $middleName, $lastName, $homePhone, $workPhone, $cellPhone, $email, $addressOne, $addressTwo, $city, $state, $zip)";
                $database->runQuery($query);
                
                echo $query;

                /*
                 * email section
                 */

                // variables from the form
                $customerNumber = stripQuotes($customerNumber);
                $firstName = stripQuotes($firstName);
                $middleName = stripQuotes($middleName);
                $lastName = stripQuotes($lastName);
                $homePhone = stripQuotes($homePhone);
                $workPhone = stripQuotes($workPhone);
                $cellPhone = stripQuotes($cellPhone);
                $email = stripQuotes($email);
                $addressOne = stripQuotes($addressOne);
                $addressTwo = stripQuotes($addressTwo);
                $city = stripQuotes($city);
                $state = stripQuotes($state);
                $zip = stripQuotes($zip);

                // variables not from the form
                $fico = stripQuotes($fico);
                $store = stripQuotes($store);
                $mailDate = stripQuotes($mailDate);
                $mailType = stripQuotes($mailType);
                $dealerId = stripQuotes($dealerId);
                $date = stripQuotes($date);
                $time = stripQuotes($time);

                // Appointment variables
                $appointmentDate = stripQuotes($appointmentDate);
                $appointmentTime = stripQuotes($appointmentTime);
                $appointmentRefusalReason = stripQuotes($appointmentRefusalReason);

                $callerId = stripQuotes($callerId);



                // unset these two arrays, we were having issues with leftover email addresses remaining in arrays after they got sent out
                unset($deliveryAddress);
                unset($deliveryName);

                // Include the email sender code
                require "req/send-lead-email.php";
            }

            imap_mail_move($imapStream, "1:" . $messageCount, "INBOX.Parsed");
            imap_expunge($imapStream);
            //close the stream 
            imap_close($imapStream);
            echo "$messageCount records have been parsed!";
        }
        ?>

    </body>
</html>