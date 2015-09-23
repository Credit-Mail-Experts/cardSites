<?php
session_start();
ob_start();
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Thank You!</title>

        <?php
        require "req/head.php";
        ?>
    </head>

    <body>
        <?php require "req/header.php"; ?>

        <div id="content">
            <section id="thank-you">
                <h1>Thank You!</h1>
                <p>We have sent your information to the authorized auto dealer in your area.</p>
                <p>They will be contacting you shortly to set up your personal appointment.</p>
            </section>
            <br />
        </div>

        <?php require "req/footer.php"; ?>

        <?php
        /*
         * Duplicate avoidance section
         */

        // If nothing was filled out on the form, send back to index
        if (empty($_POST["FirstNameTextBox"])) {
            header("Location: index.php");
            exit;
        }

        // Grab variables that will be used to cross reference for duplicates
        $email = mysql_real_escape_string(strtolower($_POST["EmailTextBox"]));
        $firstName = upcfirst($_POST["FirstNameTextBox"]);
        $middleName = upcfirst($_POST["MiddleNameTextBox"]);
        $lastName = upcfirst($_POST["LastNameTextBox"]);

        // Travier added a bunch of caller id stuff, no idea why, I edited it all out and just set it to Web Lead because it never parsed boxes from the form anyways

        /* Changes by Travier
          $callerIdPhoneOne = $_POST["CallerIdTextBoxOne"];
          $callerIdPhoneTwo = $_POST["CallerIdTextBoxTwo"];
          $callerIdPhoneThree = $_POST["CallerIdTextBoxThree"];
          /* End */



        $homePhoneOne = $_POST["HomePhoneTextBoxOne"];
        $homePhoneTwo = $_POST["HomePhoneTextBoxTwo"];
        $homePhoneThree = $_POST["HomePhoneTextBoxThree"];
        $workPhoneOne = $_POST["WorkPhoneTextBoxOne"];
        $workPhoneTwo = $_POST["WorkPhoneTextBoxTwo"];
        $workPhoneThree = $_POST["WorkPhoneTextBoxThree"];
        $cellPhoneOne = $_POST["CellPhoneTextBoxOne"];
        $cellPhoneTwo = $_POST["CellPhoneTextBoxTwo"];
        $cellPhoneThree = $_POST["CellPhoneTextBoxThree"];
        $addressOne = upcwords($_POST["AddressOneTextBox"]);
        $addressTwo = upcwords($_POST["AddressTwoTextBox"]);
        $city = upcwords($_POST["CityTextBox"]);
        $state = $_POST["StateDropDownList"];
        $zip = $_POST["ZipTextBox"];

        // convert the chunk's of phone number into one fluid number in the format (xxx)xxx-xxxx (ADF XML FORMAT)

        /* Changes by Travier
          if (!empty($callerIdPhoneOne)) {
          $callerId = "$callerIdPhoneOne$callerIdPhoneTwo$callerIdPhoneThree";
          } else {
          $callerId = NULL;
          }
          /* End */
        if (!empty($homePhoneOne)) {
            $homePhone = "($homePhoneOne)$homePhoneTwo-$homePhoneThree";
        } else {
            $homePhone = NULL;
        }
        if (!empty($workPhoneOne)) {
            $workPhone = "($workPhoneOne)$workPhoneTwo-$workPhoneThree";
        } else {
            $workPhone = NULL;
        }
        if (!empty($cellPhoneOne)) {
            $cellPhone = "($cellPhoneOne)$cellPhoneTwo-$cellPhoneThree";
        } else {
            $cellPhone = NULL;
        }

        $currentDate = date("Y-m-d");
        $currentTime = date("H:i:s");

        // Execute a query that searches for emails existing in the database (duplicates)
        $query = "SELECT * FROM leads WHERE email = '$email' ORDER BY date ASC, time ASC";
        $result = $database->runQuery($query);

        // If a duplicate is found grab all the information from the database
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                $dbDate = $row["date"];
                $dbTime = $row["time"];
                $dbFirstName = $row["first_name"];
                $dbMiddleName = $row["middle_name"];
                $dbLastName = $row["last_name"];
                $dbHomePhone = $row["home_phone"];
                $dbWorkPhone = $row["work_phone"];
                $dbCellPhone = $row["cell_phone"];
                $dbAddressOne = $row["address_one"];
                $dbAddressTwo = $row["address_two"];
                $dbCity = $row["city"];
                $dbState = $row["state"];
                $dbZip = $row["zip"];
            }

            $timeDifference = getTimeDifference($dbTime, $currentTime);

            // If no input variables changed from our most recent lead in the database then just exit and do nothing
            if ($currentDate == $dbDate and $timeDifference <= 60 and $firstName == $dbFirstName and $middleName == $dbMiddleName and $lastName == $dbLastName and $homePhone == $dbHomePhone and $workPhone == $dbWorkPhone and $cellPhone == $dbCellPhone and $addressOne == $dbAddressOne and $addressTwo == $dbAddressTwo and $city == $dbCity and $state == $dbState and $zip == $dbZip) {
                exit;
            } else {
                $duplicateRecord = TRUE;
            }

            if ($duplicateRecord) {
                // Sets variables for duplicate record if records do not match
                $dupEmail = $email;

                if ($firstName != $dbFirstName) {
                    $dupFirstName = $firstName;
                }

                if ($lastName != $dbLastName) {
                    $dupLastName = $lastName;
                }

                if ($homePhone != $dbHomePhone) {
                    $dupHomePhone = $homePhone;
                }

                if ($workPhone != $dbWorkPhone) {
                    $dupWorkPhone = $workPhone;
                }

                if ($cellPhone != $dbCellPhone) {
                    $dupCellPhone = $cellPhone;
                }

                if ($addressOne != $dbAddressOne) {
                    $dupAddressOne = $addressOne;
                }

                if ($addressTwo != $dbAddressTwo) {
                    $dupAddressTwo = $addressTwo;
                }

                if ($city != $dbCity) {
                    $dupCity = $city;
                }

                if ($state != $dbState) {
                    $dupState = $state;
                }

                if ($zip != $dbZip) {
                    $dupZip = $zip;
                }
            }
        }

        /*
        // Check sent to trec global table
        // Execute a query that searches for emails existing in the database (duplicates)
        $query = "SELECT * FROM leads_sent_to_trecglobal WHERE email = '$email' ORDER BY date ASC, time ASC";
        $result = $database->runQuery($query);

        // If a duplicate is found grab all the information from the database
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                $trecDate = $row["date"];
                $trecTime = $row["time"];
                $trecFirstName = $row["first_name"];
                $trecMiddleName = $row["middle_name"];
                $trecLastName = $row["last_name"];
                $trecHomePhone = $row["home_phone"];
                $trecWorkPhone = $row["work_phone"];
                $trecCellPhone = $row["cell_phone"];
                $trecAddressOne = $row["address_one"];
                $trecAddressTwo = $row["address_two"];
                $trecCity = $row["city"];
                $trecState = $row["state"];
                $trecZip = $row["zip"];
            }

            $timeDifference = getTimeDifference($trecTime, $currentTime);

            // If no input variables changed from our most recent lead in the database then just exit and do nothing
            if ($currentDate == $trecDate and $timeDifference <= 60 and $firstName == $trecFirstName and $middleName == $trecMiddleName and $lastName == $trecLastName and $homePhone == $trecHomePhone and $workPhone == $trecWorkPhone and $cellPhone == $trecCellPhone and $addressOne == $trecAddressOne and $addressTwo == $trecAddressTwo and $city == $trecCity and $state == $trecState and $zip == $trecZip) {
                exit;
            }
        }
         *
         */

        /*
         * Database section
         */

        // Initialize some variables to no variable declared errors
        $fico = "NULL";
        $store = "NULL";
        $mailDate = "NULL";
        $mailType = "NULL";
        $dealerId = "NULL";
        $comment = "NULL";

        $family = $database->dbPrepare($_POST["FamilyTextBox"]);
        $friend = $database->dbPrepare($_POST["FriendTextBox"]);

        // New customer
        if (!empty($_POST["CustomerNumberTextBox"])) {
            $customerNumber = $_POST["CustomerNumberTextBox"];
        } else {
            $customerNumber = "000000";
        }

        if ($family == "'family'") {
            $comment = "'Family of Person Mailed'";
        } else if ($friend == "'friend'") {
            $comment = "'Friend of Person Mailed'";
        }

        // If a customer exists retreive variables from the customers table
        if ($customerNumber != "000000") {

            $customerNumber = $_POST["CustomerNumberTextBox"];

            $query = "SELECT fico, store, mail_date, mail_type, dealer_id FROM customers WHERE customer_number = $customerNumber";
            $result = $database->runQuery($query);

            while ($row = mysql_fetch_array($result)) {
                $fico = $database->dbPrepare($row["fico"]);
                $store = $database->dbPrepare($row["store"]);
                $mailDate = $database->dbPrepare($row["mail_date"]);
                $mailType = $database->dbPrepare($row["mail_type"]);
                $dealerId = $database->dbPrepare($row["dealer_id"]);
            }

            // if friend or family, only save the dealerId
            if ($family == "'family'" || $friend == "'friend'") {
                $fico = "NULL";
                $store = "NULL";
                $mailDate = "NULL";
                //Apparently the mail type should still be included on every lead so I commented this out on 04-15-2015
                //$mailType = "NULL";
            }
        }else {

	        //Attempt to find customer from lead info before declaring it an Orphan Lead
	        $query = "SELECT * FROM customers WHERE zip = '$zip' and address_one = '$addressOne' ORDER BY mail_date DESC LIMIT 0,1";
	        $result = $database->runQuery($query);

	        if(mysql_num_rows($result) > 0) {

	            while ($row = mysql_fetch_array($result)) {
	                $dealerId = $row['dealer_id'];
		            $fico = $row["fico"];
	                $store = ($row["store"]);
	                $mailDate = $row["mail_date"];
	                $mailType = $row["mail_type"];
	                $customerNumber = $row["customer_number"];
	            }

			}

        }

        // predetermined variables
        $date = $database->dbPrepare(date("Y-m-d"));
        $time = $database->dbPrepare(date("H:i:s"));
        $source = $database->dbPrepare("Web");
        $sourceType = $database->dbPrepare("drivenowcard.com");
        // new addition 11-26-14 by billy
        $callerId = "Web Lead";

        // variables from the form on the previous page
        $customerNumber = $database->dbPrepare($customerNumber);
        $firstName = $database->dbPrepare($_POST["FirstNameTextBox"], "ucfirst");
        $middleName = $database->dbPrepare($_POST["MiddleNameTextBox"], "ucfirst");
        $lastName = $database->dbPrepare($_POST["LastNameTextBox"], "ucfirst");
        $callerId = $database->dbPrepare($callerId);
        $homePhone = $database->dbPrepare($homePhone);
        $workPhone = $database->dbPrepare($workPhone);
        $cellPhone = $database->dbPrepare($cellPhone);
        $email = $database->dbPrepare($_POST["EmailTextBox"], "lower");
        $addressOne = $database->dbPrepare($_POST["AddressOneTextBox"], "ucwords");
        $addressTwo = $database->dbPrepare($_POST["AddressTwoTextBox"], "ucwords");
        $city = $database->dbPrepare($_POST["CityTextBox"], "ucwords");
        $state = $database->dbPrepare($_POST["StateDropDownList"], "lower");
        $zip = $database->dbPrepare($_POST["ZipTextBox"]);

        // Insert the lead to the normal leads table
        $query = "INSERT INTO leads (mail_date, customer_number, date, time, source, source_type, mail_type, fico, store, dealer_id, first_name, middle_name, last_name, home_phone, work_phone, cell_phone, email, address_one, address_two, city, state, zip, comment, caller_id) VALUES ($mailDate, $customerNumber, $date, $time, $source, $sourceType, $mailType, $fico, $store, $dealerId, $firstName, $middleName, $lastName, $homePhone, $workPhone, $cellPhone, $email, $addressOne, $addressTwo, $city, $state, $zip, $comment, $callerId)";
        $database->runQuery($query);

        // Depricated
        /*
        // Check if dealer has appointment hours
        $query = "SELECT dealer_id FROM dealer_appointment_hours WHERE dealer_id = $dealerId";
        $result = $database->runQuery($query);

        // If the dealer does NOT have appointment hours than parse the information to the database as normal
        if (1 === 1) {
            // Insert the lead to the normal leads table
            $query = "INSERT INTO leads (mail_date, customer_number, date, time, source, source_type, mail_type, fico, store, dealer_id, first_name, middle_name, last_name, home_phone, work_phone, cell_phone, email, address_one, address_two, city, state, zip, comment, caller_id) VALUES ($mailDate, $customerNumber, $date, $time, $source, $sourceType, $mailType, $fico, $store, $dealerId, $firstName, $middleName, $lastName, $homePhone, $workPhone, $cellPhone, $email, $addressOne, $addressTwo, $city, $state, $zip, $comment, $callerId)";
            $database->runQuery($query);

            // If the dealer does have appointment hours it's being sent to trecglobal
        } else {
            // insert into the trecglobal database
            $query = "INSERT INTO leads_sent_to_trecglobal (mail_date, customer_number, date, time, source, source_type, mail_type, fico, store, dealer_id, first_name, middle_name, last_name, home_phone, work_phone, cell_phone, email, address_one, address_two, city, state, zip, comment, caller_id) VALUES ($mailDate, $customerNumber, $date, $time, $source, $sourceType, $mailType, $fico, $store, $dealerId, $firstName, $middleName, $lastName, $homePhone, $workPhone, $cellPhone, $email, $addressOne, $addressTwo, $city, $state, $zip, $comment, $callerId)";
            $database->runQuery($query);
        }
         *
         */


        /*
         * Email section
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
        $family = stripQuotes($family);
        $friend = stripQuotes($friend);
        $date = stripQuotes($date);
        $time = stripQuotes($time);
        $comment = stripQuotes($comment);
        $sourceType = stripQuotes($sourceType);

        // Set advertising source
        $advertisingSource = "www.drivenowcard.com";

        if ($customerNumber != "000000") {

            // Include the email sender code
            require "req/send-lead-email.php";
        } else { // mail to these guys if customerNumber is 000000 (it's a new customer without friend/family)

            $emailTo = "leads@creditmailexperts.com";
            $emailSubject = "Lead Detail: CME/Credit Mail Experts";
            $emailFrom = "deliveryagent@creditmailexperts.com";
            $emailHeader = "From:" . $emailFrom;

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
                    . "Advertising Source = $advertisingSource";

            mail($emailTo, $emailSubject, $emailMessage, $emailHeader);
        }
        ?>
    </body>
</html>
