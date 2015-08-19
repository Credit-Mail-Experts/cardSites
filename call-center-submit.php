<?php
session_start();
ob_start();
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Call Center - Submission Successful</title>

        <?php
        require "req/head.php";
        ?>
    </head>

    <body>
        <?php require "req/header.php"; ?> 

        <div id="content">
            <section id="thank-you">
                <img src="img/thank-you.png" />
            </section>
            <br />
        </div>

        <?php require "req/footer.php"; ?>

        <?php
        ///////////////////////////////
        // duplicate avoidance section
        ///////////////////////////////
        // if noone is logged in send back to the login page
        if (!isset($employeeId)) {
            header('Location: call-center-login.php');
        }

        // if nothing was filled out on the form, send back to index
        if (empty($_POST["FirstNameTextBox"])) {
            header("Location: call-center-form.php");
            exit;
        }

        // grab variables that will be used to cross reference for duplicates  	
        $firstName = upcfirst($_POST["FirstNameTextBox"]);
        $middleName = upcfirst($_POST["MiddleNameTextBox"]);
        $lastName = upcfirst($_POST["LastNameTextBox"]);
        $callerId = $_POST["CallerIdTextBox"];
        $homePhone = $_POST["HomePhoneTextBox"];
        $workPhone = $_POST["WorkPhoneTextBox"];
        $cellPhone = $_POST["CellPhoneTextBox"];
        $addressOne = mysql_real_escape_string(strtolower($_POST["AddressOneTextBox"]));
        $addressTwo = upcwords($_POST["AddressTwoTextBox"]);
        $city = upcwords($_POST["CityTextBox"]);
        $state = $_POST["StateDropDownList"];
        $zip = $_POST["ZipTextBox"];
        $comment = $_POST["CommentTextArea"];
        $source = "Call Center";

        // convert the chunk's of phone number into one fluid number in the format (xxx)xxx-xxxx (ADF XML FORMAT)
        
        if(!empty($callerId)) {
            $callerId = "(" . substr($callerId, 0, 3) . ")" . substr($callerId, 3, 3) . "-" . substr($callerId, -4);
        } else {
            $callerId = NULL;
        }
        
        if (!empty($homePhone)) {
            $homePhone = "(" . substr($homePhone, 0, 3) . ")" . substr($homePhone, 3, 3) . "-" . substr($homePhone, -4);
        } else {
            $homePhone = NULL;
        }
        
        if (!empty($workPhone)) {
            $workPhone = "(" . substr($workPhone, 0, 3) . ")" . substr($workPhone, 3, 3) . "-" . substr($workPhone, -4);
        } else {
            $workPhone = NULL;
        }
        
        if (!empty($cellPhone)) {
            $cellPhone = "(" . substr($cellPhone, 0, 3) . ")" . substr($cellPhone, 3, 3) . "-" . substr($cellPhone, -4);
        } else {
            $cellPhone = NULL;
        }

        $currentDate = date("Y-m-d");
        $currentTime = date("H:i:s");

        // execute a query that searches for address_one's existing in the database (duplicates)
        $query = "SELECT * FROM leads WHERE address_one = '$addressOne' ORDER BY date ASC, time ASC";
        $result = $database->runQuery($query);

        // if a duplicate is found grab all the information from the database
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
                $dbAddressTwo = $row["address_two"];
                $dbCity = $row["city"];
                $dbState = $row["state"];
                $dbZip = $row["zip"];
                $dbComment = $row["comment"];
                $dbSource = $row["source"];
            }

            $timeDifference = getTimeDifference($dbTime, $currentTime);

            // if no input variables changed from our most recent lead in the database then just exit and do nothing
            if ($currentDate == $dbDate and $timeDifference <= 60 and $firstName == $dbFirstName and $middleName == $dbMiddleName and $lastName == $dbLastName and $homePhone == $dbHomePhone and $workPhone == $dbWorkPhone and $cellPhone == $dbCellPhone and $addressTwo == $dbAddressTwo and $city == $dbCity and $state == $dbState and $zip == $dbZip and $comment == $dbComment and $source == $dbSource) {
                header("Location: call-center-form.php?duplicate=$customerNumber");
                exit;
            }
        }

        /*
         * Database section
         */

        // initialize some variables to no variable declared errors
        $fico = "NULL";
        $store = "NULL";
        $mailDate = "NULL";
        $mailType = "NULL";
        $dealerId = "NULL";

        // new customer
        if (!empty($_POST["CustomerNumberTextBox"])) {
            $customerNumber = $_POST["CustomerNumberTextBox"];
        } else {
            $customerNumber = "000000";
        }

        // if a customer exists retreive variables from the customers table
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
        }

        switch ($_POST["SourceDropDownList"]) {
            case "Call Center":
                $sourceType = "ITM Outbound";
                break;
            case "Toll-Free":
                $sourceType = "ITM Inbound";
                break;
            case "Web":
                $sourceType = "apcardonline.com";
                break;
            default:
                $sourceType = "";
                break;
        }

        // predetermined variables
        $date = $database->dbPrepare(date("Y-m-d"));
        $time = $database->dbPrepare(date("H:i:s"));
        //$source = $database->dbPrepare("Call Center");
        //$sourceType = $database->dbPrepare("ITM Outbound");
        
        
        // variables from the form on the previous page
        $customerNumber = $database->dbPrepare($customerNumber);
        $firstName = $database->dbPrepare($_POST["FirstNameTextBox"], "ucfirst");
        $middleName = $database->dbPrepare($_POST["MiddleNameTextBox"], "ucfirst");
        $lastName = $database->dbPrepare($_POST["LastNameTextBox"], "ucfirst");
        $callerId = $database->dbPrepare($callerId);
        $homePhone = $database->dbPrepare($homePhone);
        $workPhone = $database->dbPrepare($workPhone);
        $cellPhone = $database->dbPrepare($cellPhone);
        $addressOne = $database->dbPrepare($_POST["AddressOneTextBox"], "ucwords");
        $addressTwo = $database->dbPrepare($_POST["AddressTwoTextBox"], "ucwords");
        $city = $database->dbPrepare($_POST["CityTextBox"], "ucwords");
        $state = $database->dbPrepare($_POST["StateDropDownList"], "lower");
        $zip = $database->dbPrepare($_POST["ZipTextBox"]);
        $warmTransfer = $database->dbPrepare($_POST["WarmTransferDropDownList"]);
        $source = $database->dbPrepare($_POST["SourceDropDownList"]);
        $sourceType = $database->dbPrepare($sourceType);
        $comment = $database->dbPrepare($_POST["CommentTextArea"]);
        $email = $database->dbPrepare($_POST["EmailTextBox"], "lower");

        // appointment variables, still from the form
        // We need to check if appointment information posted becuase it will NOT post if it is disabled i.e. the dealer did not take appointments
        if (isset($_POST["AppointmentDatePicker"]) || isset($_POST["AppointmentRefusalReasonTextArea"])) {
            if (strlen($_POST["AppointmentRefusalReasonTextArea"]) > 0) {
                $appointmentDate = "NULL";
            } else {
                $appointmentDate = $database->dbPrepare($_POST["AppointmentDatePicker"]);
            }



            // Grab the day of the week the appointment falls on so we can grab the correct time from the form
            $appointmentDayOfTheWeek = date("l", strtotime($_POST["AppointmentDatePicker"]));
            // Make sure we only grab appointment time data if a date exists and it's not set to "NULL"
            if (strlen($appointmentDate) > 5) {
                switch ($appointmentDayOfTheWeek) {
                    case "Monday":
                        $appointmentTime = $database->dbPrepare($_POST["MondayAppointmentTimeDropDownList"]);
                        break;
                    case "Tuesday":
                        $appointmentTime = $database->dbPrepare($_POST["TuesdayAppointmentTimeDropDownList"]);
                        break;
                    case "Wednesday":
                        $appointmentTime = $database->dbPrepare($_POST["WednesdayAppointmentTimeDropDownList"]);
                        break;
                    case "Thursday":
                        $appointmentTime = $database->dbPrepare($_POST["ThursdayAppointmentTimeDropDownList"]);
                        break;
                    case "Friday":
                        $appointmentTime = $database->dbPrepare($_POST["FridayAppointmentTimeDropDownList"]);
                        break;
                    case "Saturday":
                        $appointmentTime = $database->dbPrepare($_POST["SaturdayAppointmentTimeDropDownList"]);
                        break;
                    case "Sunday":
                        $appointmentTime = $database->dbPrepare($_POST["SundayAppointmentTimeDropDownList"]);
                        break;
                }
            } else {
                $appointmentTime = "NULL";
            }


            $appointmentRefusalReason = $database->dbPrepare($_POST["AppointmentRefusalReasonTextArea"]);
        } else {
            $appointmentDate = "NULL";
            $appointmentTime = "NULL";
            $appointmentRefusalReason = "NULL";
        }

        // insert everything into the database
        $query = "INSERT INTO leads (appointment_date, appointment_time, appointment_refusal_reason, warm_transfer, email, mail_date, customer_number, date, time, source, source_type, mail_type, fico, store, dealer_id, first_name, middle_name, last_name, home_phone, work_phone, cell_phone, address_one, address_two, city, state, zip, comment, caller_id) VALUES ($appointmentDate, $appointmentTime, $appointmentRefusalReason, $warmTransfer, $email, $mailDate, $customerNumber, $date, $time, $source, $sourceType, $mailType, $fico, $store, $dealerId, $firstName, $middleName, $lastName, $homePhone, $workPhone, $cellPhone, $addressOne, $addressTwo, $city, $state, $zip, $comment, $callerId)";
        $database->runQuery($query);

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
        $addressOne = stripQuotes($addressOne);
        $addressTwo = stripQuotes($addressTwo);
        $city = stripQuotes($city);
        $state = stripQuotes($state);
        $zip = stripQuotes($zip);

        // variables not from the form
        $email = stripQuotes($email);
        $fico = stripQuotes($fico);
        $store = stripQuotes($store);
        $mailDate = stripQuotes($mailDate);
        $mailType = stripQuotes($mailType);
        $dealerId = stripQuotes($dealerId);
        $date = stripQuotes($date);
        $time = stripQuotes($time);
        $comment = stripQuotes($comment);
        $sourceType = stripQuotes($sourceType);

        // appointment fields
        $appointmentDate = stripQuotes($appointmentDate);
        $appointmentTime = stripQuotes($appointmentTime);
        $appointmentRefusalReason = stripQuotes($appointmentRefusalReason);

        // Set advertising source
        //$advertisingSource = "CallCenter";

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
                    . "Caller ID = $callerId\n"
                    . "Advertising Source = $advertisingSource";

            mail($emailTo, $emailSubject, $emailMessage, $emailHeader);
        }
        // Return back to the form with a success get variable
        header("Location: call-center-form.php?success=$customerNumber");
        ?>
    </body>
</html>