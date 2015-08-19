<?php
session_start();
ob_start();
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Call Center - Form</title>

        <?php
        require "req/head.php";

        // Make sure an employee is actually logged in, if not send them to the login page
        if (!isset($employeeId)) {
            header('Location: call-center-login.php');
        }
        ?>

        <!-- script to validate the form on this page -->
        <script type="text/javascript" src="js/validate-call-center-form.js"></script>
    </head>

    <body>
        <?php
        // If we've looked a customer up
        if (isset($_POST["CustomerNumberEditableTextBox"]) && strlen($_POST["CustomerNumberEditableTextBox"] > 0)) {
            

            // Boolean for logic on the form
            $pagePosted = true;
            $customerNumber = $_POST["CustomerNumberEditableTextBox"];

            $query = "SELECT first_name, last_name FROM customers WHERE customer_number = '$customerNumber'";
            $result = $database->runQuery($query);

            // If the customer number posted doesn't exist in the database do the following and exit parsing
            if (mysql_num_rows($result) == 0) {
                header("Location: call-center-form.php?customerNumber=$customerNumber");
                exit;
            }

            // Grab all the information for the customer so we can pre-populate the forms
            $query = "SELECT * FROM customers WHERE customer_number = '$customerNumber'";
            $result = $database->runQuery($query);

            // Array of search characters to replace in the phone number pulled from database
            $phoneCharactersToReplace = array("(", "-", ")");
            
            while ($row = mysql_fetch_array($result)) {
                $firstName = upcfirst($row["first_name"]);
                $middleName = upcfirst($row["middle_name"]);
                $lastName = upcfirst($row["last_name"]);
                $addressOne = upcwords($row["address_one"]);
                $addressTwo = upcwords($row["address_two"]);
                $city = upcwords($row["city"]);
                $state = strtolower($row["state"]);
                $zip = $row["zip"];
                $homePhone = str_replace($phoneCharactersToReplace, "", $row["phone"]);

                // dealer id added for the appointment addition
                $dealerId = $row["dealer_id"];
            }
            
            // If the page posted grab the information that we need for the dealer information form
            $query = "SELECT first_name, last_name, dealership_name, phone, street_address, city, state, zip FROM dealer_contacts WHERE dealer_id = '$dealerId'";
            $result = $database->runQuery($query);

            while ($row = mysql_fetch_array($result)) {
                $dealerFirstName = upcfirst($row["first_name"]);
                $dealerLastName = upcfirst($row["last_name"]);
                $dealerName = upcfirst($row["dealership_name"]);
                $dealerPhone = upcfirst($row["phone"]);
                $dealerStreetAddress = upcfirst($row["street_address"]);
                $dealerCity = upcfirst($row["city"]);
                $dealerState = upcfirst($row["state"]);
                $dealerZip = upcfirst($row["zip"]);
            }
            
            
            
            // If a customer number exists in the get variable do the following
            // We use the customerNumber get variable if the customer number is not found when we search it
        } else if (isset($_GET["customerNumber"])) {
            $customerNumber = $_GET["customerNumber"];
            // This variable locks out the form from being submitted
            $customerNumberNotFound = true;
            $dealerId = null;


            // If we have no customer number that has been posted and we have no customer number in the get variable do the following
        } else {
            //header("Location: call-center-form.php");
            //exit;
            // Added this to keep an error from popping up
            $dealerId = null;
            // This variable locks out the form from being submitted
            $customerNumberNotFound = true;
            $selectACustomerNumber = true;
        }

        // Check to see if the parse page returned a successful parse
        if (isset($_GET["success"])) {
            $customerNumberSuccess = $_GET["success"];
        }

        // Check to see if the parse page returned a duplicate lead error
        if (isset($_GET["duplicate"])) {
            $duplicateLead = $_GET["duplicate"];
        }

        // Grab the days of the week that the dealer takes appointments (if any)
        $query = "SELECT day FROM dealer_appointment_hours WHERE dealer_id = '$dealerId'";
        $result = $database->runQuery($query);

        // If the dealer does not take appointments set a boolean to false continue
        if (mysql_num_rows($result) == 0) {
            $doesNotTakeAppointments = true;


            // If the dealer does take appointments than grab all of the appointment information we need
        } else {
            while ($row = mysql_fetch_array($result)) {
                $appointmentDaysOfTheWeek[] = $row["day"];
            }

            // Grab an array of dates for each day of the week a dealer takes appointments. We use the upcoming day of the week and then add a month to each one.
            // The system currently does not have a system for skipping holidays, or other specefic dates that the dealer does not want to use.
            foreach ($appointmentDaysOfTheWeek as $value) {
                if ($value == "monday") {
                    $appointmentDays[] = date("Y-n-j", strtotime("next monday"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next monday + 1 week"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next monday + 2 weeks"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next monday + 3 weeks"));
                }
                if ($value == "tuesday") {
                    $appointmentDays[] = date("Y-n-j", strtotime("next tuesday"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next tuesday + 1 week"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next tuesday + 2 weeks"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next tuesday + 3 weeks"));
                }
                if ($value == "wednesday") {
                    $appointmentDays[] = date("Y-n-j", strtotime("next wednesday"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next wednesday + 1 week"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next wednesday + 2 weeks"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next wednesday + 3 weeks"));
                }
                if ($value == "thursday") {
                    $appointmentDays[] = date("Y-n-j", strtotime("next thursday"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next thursday + 1 week"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next thursday + 2 weeks"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next thursday + 3 weeks"));
                }
                if ($value == "friday") {
                    $appointmentDays[] = date("Y-n-j", strtotime("next friday"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next friday + 1 week"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next friday + 2 weeks"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next friday + 3 weeks"));
                }
                if ($value == "saturday") {
                    $appointmentDays[] = date("Y-n-j", strtotime("next saturday"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next saturday + 1 week"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next saturday + 2 weeks"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next saturday + 3 weeks"));
                }
                if ($value == "sunday") {
                    $appointmentDays[] = date("Y-n-j", strtotime("next sunday"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next sunday + 1 week"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next sunday + 2 weeks"));
                    $appointmentDays[] = date("Y-n-j", strtotime("next sunday + 3 weeks"));
                }
            }



            // Be sure to include today if the day of the week is in the array of appiontment days for the dealer
            if (in_array(strtolower(date("l")), $appointmentDaysOfTheWeek)) {
                $appointmentDays[] = date("Y-n-j");
            }

            // Take the array of dates and convert it to a csv style string that can be inserted into the jquery calendar
            $appointmentDaysCSV = "";
            for ($i = 0; $i < count($appointmentDays); $i++) {
                if ($i != count($appointmentDays) - 1) {
                    $appointmentDaysCSV .= "\"$appointmentDays[$i]\",";
                } else {
                    $appointmentDaysCSV .= "\"$appointmentDays[$i]\"";
                }
            }

            // Grab the start and end times for each day of the week the dealer takes appointments and assign them to arrays
            foreach ($appointmentDaysOfTheWeek as $value) {
                $query = "SELECT start_time, end_time FROM dealer_appointment_hours WHERE dealer_id = '2869' AND day = '$value'";
                $result = $database->runQuery($query);

                while ($row = mysql_fetch_array($result)) {
                    $appointmentStartTimes[$value] = $row["start_time"];
                    $appointmentEndTimes[$value] = $row["end_time"];
                }
            }

            // https://stackoverflow.com/questions/4834202/convert-hhmmss-to-seconds-only
            // Using a dictionary key create an incremented array of times for the difference between start and end times for each day the dealer takes appointments
            if (isset($appointmentStartTimes["monday"])) {
                $lower = $appointmentStartTimes["monday"];
                $upper = $appointmentEndTimes["monday"];
                $mondayAppointmentTimes = getIncrementedTimes(strtotime("1970-01-01 $lower UTC"), strtotime("1970-01-01 $upper UTC"), 1800);
            }

            if (isset($appointmentStartTimes["tuesday"])) {
                $lower = $appointmentStartTimes["tuesday"];
                $upper = $appointmentEndTimes["tuesday"];
                $tuesdayAppointmentTimes = getIncrementedTimes(strtotime("1970-01-01 $lower UTC"), strtotime("1970-01-01 $upper UTC"), 1800);
            }

            if (isset($appointmentStartTimes["wednesday"])) {
                $lower = $appointmentStartTimes["wednesday"];
                $upper = $appointmentEndTimes["wednesday"];
                $wednesdayAppointmentTimes = getIncrementedTimes(strtotime("1970-01-01 $lower UTC"), strtotime("1970-01-01 $upper UTC"), 1800);
            }

            if (isset($appointmentStartTimes["thursday"])) {
                $lower = $appointmentStartTimes["thursday"];
                $upper = $appointmentEndTimes["thursday"];
                $thursdayAppointmentTimes = getIncrementedTimes(strtotime("1970-01-01 $lower UTC"), strtotime("1970-01-01 $upper UTC"), 1800);
            }

            if (isset($appointmentStartTimes["friday"])) {
                $lower = $appointmentStartTimes["friday"];
                $upper = $appointmentEndTimes["friday"];
                $fridayAppointmentTimes = getIncrementedTimes(strtotime("1970-01-01 $lower UTC"), strtotime("1970-01-01 $upper UTC"), 1800);
            }

            if (isset($appointmentStartTimes["saturday"])) {
                $lower = $appointmentStartTimes["saturday"];
                $upper = $appointmentEndTimes["saturday"];
                $saturdayAppointmentTimes = getIncrementedTimes(strtotime("1970-01-01 $lower UTC"), strtotime("1970-01-01 $upper UTC"), 1800);
            }

            if (isset($appointmentStartTimes["sunday"])) {
                $lower = $appointmentStartTimes["sunday"];
                $upper = $appointmentEndTimes["sunday"];
                $sundayAppointmentTimes = getIncrementedTimes(strtotime("1970-01-01 $lower UTC"), strtotime("1970-01-01 $upper UTC"), 1800);
            }
        }


        /*
         * Grab the hours for the dealer information table if they exist
         */

        $query = "SELECT start_time, end_time FROM dealer_appointment_hours WHERE dealer_id = '$dealerId' AND day = 'monday'";
        $result = $database->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                $mondayOpen = date('h:i a', strtotime(upcfirst($row["start_time"])));
                $mondayClose = date('h:i a', strtotime(upcfirst($row["end_time"])));
            }
        } else {
            $mondayOpen = "N/A";
            $mondayClose = "N/A";
        }

        $query = "SELECT start_time, end_time FROM dealer_appointment_hours WHERE dealer_id = '$dealerId' AND day = 'tuesday'";
        $result = $database->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                $tuesdayOpen = date('h:i a', strtotime(upcfirst($row["start_time"])));
                $tuesdayClose = date('h:i a', strtotime(upcfirst($row["end_time"])));
            }
        } else {
            $tuesdayOpen = "N/A";
            $tuesdayClose = "N/A";
        }

        $query = "SELECT start_time, end_time FROM dealer_appointment_hours WHERE dealer_id = '$dealerId' AND day = 'wednesday'";
        $result = $database->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                $wednesdayOpen = date('h:i a', strtotime(upcfirst($row["start_time"])));
                $wednesdayClose = date('h:i a', strtotime(upcfirst($row["end_time"])));
            }
        } else {
            $wednesdayOpen = "N/A";
            $wednesdayClose = "N/A";
        }

        $query = "SELECT start_time, end_time FROM dealer_appointment_hours WHERE dealer_id = '$dealerId' AND day = 'thursday'";
        $result = $database->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                $thursdayOpen = date('h:i a', strtotime(upcfirst($row["start_time"])));
                $thursdayClose = date('h:i a', strtotime(upcfirst($row["end_time"])));
            }
        } else {
            $thursdayOpen = "N/A";
            $thursdayClose = "N/A";
        }

        $query = "SELECT start_time, end_time FROM dealer_appointment_hours WHERE dealer_id = '$dealerId' AND day = 'friday'";
        $result = $database->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                $fridayOpen = date('h:i a', strtotime(upcfirst($row["start_time"])));
                $fridayClose = date('h:i a', strtotime(upcfirst($row["end_time"])));
            }
        } else {
            $fridayOpen = "N/A";
            $fridayClose = "N/A";
        }

        $query = "SELECT start_time, end_time FROM dealer_appointment_hours WHERE dealer_id = '$dealerId' AND day = 'saturday'";
        $result = $database->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                $saturdayOpen = date('h:i a', strtotime(upcfirst($row["start_time"])));
                $saturdayClose = date('h:i a', strtotime(upcfirst($row["end_time"])));
            }
        } else {
            $saturdayOpen = "N/A";
            $saturdayClose = "N/A";
        }

        $query = "SELECT start_time, end_time FROM dealer_appointment_hours WHERE dealer_id = '$dealerId' AND day = 'sunday'";
        $result = $database->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                $sundayOpen = date('h:i a', strtotime(upcfirst($row["start_time"])));
                $sundayClose = date('h:i a', strtotime(upcfirst($row["end_time"])));
            }
        } else {
            $sundayOpen = "N/A";
            $sundayClose = "N/A";
        }
        ?>

        <script language="javascript" type="text/javascript">
            /*
             * Code to create a jquery calendar using a php array as an input
             */
           
            var availableDates = [<?php echo $appointmentDaysCSV; ?>];

            function available(date) {
                //dmy = date.getDate() + "-" + (date.getMonth()+1) + "-" + date.getFullYear();
                ymd = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
                if ($.inArray(ymd, availableDates) != -1) {
                    return [true, "","Available"];
                } else {
                    return [false,"","unAvailable"];
                }
            }

            // Jquery calendar
            $(function() {
                $("#AppointmentDatePicker").datepicker({ beforeShowDay: available, dateFormat: "yy-mm-dd" });
            });
            
        </script>

        <?php require "req/header.php"; ?>

        <div id="content">
            <section id="step-3">
                <!--<img id="step-1" src="img/step-3.png" />-->
                <br />
                <form id="CustomerLookupForm" name="CustomerLookupForm" action="call-center-form.php" method="post">
                    <fieldset>
                        <legend>Customer Lookup</legend>
                        <p style="color: red;"><?php if (isset($customerNumberNotFound) && !isset($selectACustomerNumber)) echo "Customer number does not exist in database!" ?></p>
                        <p><?php if (isset($selectACustomerNumber)) echo "Enter a customer number to begin" ?></p>
                        <p style="color: green;"><?php if (isset($customerNumberSuccess)) echo "Lead for customer $customerNumberSuccess successfully parsed!" ?></p>
                        <p style="color: red;"><?php if (isset($duplicateLead)) echo "Lead for $customerNumber was a duplicate lead and was NOT parsed!" ?></p>
                        <label for="CustomerNumberEditableTextBox">Cust Number:</label><input type="text" id="CustomerNumberEditableTextBox" name="CustomerNumberEditableTextBox" maxlength="64" value="<?php if (isset($customerNumber)) echo $customerNumber ?>" /><br/>

                        <input type="submit" id="submit" value="">
                    </fieldset>
                </form>

                <div class="<?php if (isset($doesNotTakeAppointments)) echo "hide"; ?>">
                    <br /><br />
                    <fieldset>
                        <legend>Dealer Information</legend>
                        <?php
                        echo "<p>Dealership Name: $dealerName</p>";
                        echo "<p>Contact Name: $dealerFirstName $dealerLastName</p>";
                        echo "<p>Address: $dealerStreetAddress, $dealerCity, $dealerState $dealerZip</p>";
                        echo "<p>Phone: $dealerPhone</p>";

                        if (isset($doesNotTakeAppointments)) {
                            echo "<div class='hide'>";
                        }


                        echo "<table>";
                        echo "<caption>Store Hours</caption>";
                        echo "<tr>";
                        echo "<th>Day</th>";
                        echo "<th>Open</th>";
                        echo "<th>Close</th>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td>Monday</td>";
                        echo "<td>$mondayOpen</td>";
                        echo "<td>$mondayClose</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td>Tuesday</td>";
                        echo "<td>$tuesdayOpen</td>";
                        echo "<td>$tuesdayClose</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td>Wednesday</td>";
                        echo "<td>$wednesdayOpen</td>";
                        echo "<td>$wednesdayClose</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td>Thursday</td>";
                        echo "<td>$thursdayOpen</td>";
                        echo "<td>$thursdayClose</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td>Friday</td>";
                        echo "<td>$fridayOpen</td>";
                        echo "<td>$fridayClose</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td>Saturday</td>";
                        echo "<td>$saturdayOpen</td>";
                        echo "<td>$saturdayClose</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td>Sunday</td>";
                        echo "<td>$sundayOpen</td>";
                        echo "<td>$sundayClose</td>";
                        echo "</tr>";
                        echo "</table>";

                        if (isset($doesNotTakeAppointments)) {
                            echo "</div>";
                        }
                        ?>


                    </fieldset>
                </div>

                <br /><br />
                <form id="CustomerInformationForm" name="CustomerInformationForm" action="call-center-submit.php" method="post">
                    <fieldset>
                        <legend>Customer Information</legend>
                        <p>* Indicates a required field.</p>
                        <p><a href="privacy.php" target="_blank">Privacy Notice</a></p>
                        <input type="text" id="CustomerNumberTextBox" name="CustomerNumberTextBox" class="hide" maxlength="64" value="<?php if (isset($customerNumber)) echo $customerNumber ?>" readonly />
                        <div id="form-column-one">
                            <label for="FirstNameTextBox">First Name:*</label><input type="text" id="FirstNameTextBox" name="FirstNameTextBox" maxlength="64" value="<?php if (isset($firstName)) echo $firstName ?>" <?php if (isset($customerNumberNotFound)) echo "disabled" ?>/><br/>
                            <span id="FirstNameError" class="hide"></span>
                            <label for="MiddleNameTextBox">Middle Name:</label><input type="text" id="MiddleNameTextBox" name="MiddleNameTextBox" maxlength="64" value="<?php if (isset($middleName)) echo $middleName ?>" <?php if (isset($customerNumberNotFound)) echo "disabled" ?>/><br/>
                            <span id="MiddleNameError" class="hide"></span>
                            <label for="LastNameTextBox">Last Name:*</label><input type="text" id="LastNameTextBox" name="LastNameTextBox" maxlength="64" value="<?php if (isset($lastName)) echo $lastName ?>" <?php if (isset($customerNumberNotFound)) echo "disabled" ?>/><br/>
                            <span id="LastNameError" class="hide"></span>
                            
                            <label for="CallerIdTextBox">Caller ID:</label><input type="text" id="CallerIdTextBox" name="CallerIdTextBox" maxlength="10" value=""  <?php if (isset($customerNumberNotFound)) echo "disabled" ?>/><br/>
                            <span id="CallerIdError" class="hide"></span>                       
                            
                            <label for="HomePhoneTextBox">Home Phone:</label><input type="text" id="HomePhoneTextBox" name="HomePhoneTextBox" maxlength="10" value="<?php if (isset($homePhone)) echo $homePhone ?>"  <?php if (isset($customerNumberNotFound)) echo "disabled" ?>/><br/>
                            <span id="HomePhoneError" class="hide"></span>
                            
                            <label for="WorkPhoneTextBox">Work Phone:</label><input type="text" id="WorkPhoneTextBox" name="WorkPhoneTextBox" maxlength="10" value=""  <?php if (isset($customerNumberNotFound)) echo "disabled" ?>/><br/>
                            <span id="WorkPhoneError" class="hide"></span>
                            
                            <label for="CellPhoneTextBox">Cell Phone:</label><input type="text" id="CellPhoneTextBox" name="CellPhoneTextBox" maxlength="10" value=""  <?php if (isset($customerNumberNotFound)) echo "disabled" ?>/><br/>
                            <span id="CellPhoneError" class="hide"></span>
                            
                            <span id="PhonesError" class="hide"></span>

                            <p>Appointment Section</p>
                            <p style="color: red;"><?php if (isset($doesNotTakeAppointments)) echo "This dealer does not take appointments!" ?></p>
                            <label for="AppointmentDatePicker">Date:</label><input type="text" id="AppointmentDatePicker" name="AppointmentDatePicker" maxlength="10" <?php if (isset($doesNotTakeAppointments)) echo "disabled" ?> /><br/>
                            <span id="AppointmentDatePicker" class="hide"></span>

                            <div id="MondayTime" class="hide">    
                                <label for="MondayAppointmentTimeDropDownList">Time:</label>
                                <select id="MondayAppointmentTimeDropDownList" name="MondayAppointmentTimeDropDownList">
                                    <?php
                                    foreach ($mondayAppointmentTimes as $key => $value) {
                                        echo "<option value='$key'>$value</option>";
                                    }
                                    ?>
                                </select><br />
                            </div>
                            <div id="TuesdayTime" class="hide"> 
                                <label for="TuesdayAppointmentTimeDropDownList">Time:</label>
                                <select id="TuesdayAppointmentTimeDropDownList" name="TuesdayAppointmentTimeDropDownList">
                                    <?php
                                    foreach ($tuesdayAppointmentTimes as $key => $value) {
                                        echo "<option value='$key'>$value</option>";
                                    }
                                    ?>
                                </select><br />
                            </div>
                            <div id="WednesdayTime" class="hide"> 
                                <label for="WednesdayAppointmentTimeDropDownList">Time:</label>
                                <select id="WednesdayAppointmentTimeDropDownList" name="WednesdayAppointmentTimeDropDownList">
                                    <?php
                                    foreach ($wednesdayAppointmentTimes as $key => $value) {
                                        echo "<option value='$key'>$value</option>";
                                    }
                                    ?>
                                </select><br />
                            </div>
                            <div id="ThursdayTime" class="hide"> 
                                <label for="ThursdayAppointmentTimeDropDownList">Time:</label>
                                <select id="ThursdayAppointmentTimeDropDownList" name="ThursdayAppointmentTimeDropDownList">
                                    <?php
                                    foreach ($thursdayAppointmentTimes as $key => $value) {
                                        echo "<option value='$key'>$value</option>";
                                    }
                                    ?>
                                </select><br />
                            </div>
                            <div id="FridayTime" class="hide"> 
                                <label for="FridayAppointmentTimeDropDownList">Time:</label>
                                <select id="FridayAppointmentTimeDropDownList" name="FridayAppointmentTimeDropDownList">
                                    <?php
                                    foreach ($fridayAppointmentTimes as $key => $value) {
                                        echo "<option value='$key'>$value</option>";
                                    }
                                    ?>
                                </select><br />
                            </div>
                            <div id="SaturdayTime" class="hide"> 
                                <label for="SaturdayAppointmentTimeDropDownList">Time:</label>
                                <select id="SaturdayAppointmentTimeDropDownList" name="SaturdayAppointmentTimeDropDownList">
                                    <?php
                                    foreach ($saturdayAppointmentTimes as $key => $value) {
                                        echo "<option value='$key'>$value</option>";
                                    }
                                    ?>
                                </select><br />
                            </div>
                            <div id="SundayTime" class="hide"> 
                                <label for="SundayAppointmentTimeDropDownList">Time:</label>
                                <select id="SundayAppointmentTimeDropDownList" name="SundayAppointmentTimeDropDownList">
                                    <?php
                                    foreach ($sundayAppointmentTimes as $key => $value) {
                                        echo "<option value='$key'>$value</option>";
                                    }
                                    ?>
                                </select><br />
                            </div>

                            <label for="AppointmentRefusalReasonTextArea">Refusal?:</label>
                            <textarea id="AppointmentRefusalReasonTextArea" name="AppointmentRefusalReasonTextArea" maxlength="255" <?php if (isset($doesNotTakeAppointments)) echo "disabled" ?>></textarea><br />
                        </div>
                        <div id="form-column-two">
                            <label for="EmailTextBox">Email:</label><input type="text" id="EmailTextBox" name="EmailTextBox" maxlength="64" <?php if (isset($customerNumberNotFound)) echo "disabled" ?>/><br/>
                            <label for="AddressOneTextBox">Address One:*</label><input type="text" id="AddressOneTextBox" name="AddressOneTextBox" maxlength="128" value="<?php if (isset($addressOne)) echo $addressOne ?>" <?php if (isset($customerNumberNotFound)) echo "disabled" ?>/><br/>
                            <span id="AddressOneError" class="hide"></span>
                            <label for="AddressTwoTextBox">Address Two:</label><input type="text" id="AddressTwoTextBox" name="AddressTwoTextBox" maxlength="128" value="<?php if (isset($addressTwo)) echo $addressTwo ?>" <?php if (isset($customerNumberNotFound)) echo "disabled" ?>/><br/>
                            <span id="AddressTwoError" class="hide"></span>
                            <label for="CityTextBox">City:*</label><input type="text" id="CityTextBox" name="CityTextBox" maxlength="64" value="<?php if (isset($city)) echo $city ?>" <?php if (isset($customerNumberNotFound)) echo "disabled" ?>/><br/>
                            <span id="CityError" class="hide"></span>
                            <label for="StateDropDownList">State:*</label>
                            <select id="StateDropDownList" name="StateDropDownList" <?php if (isset($customerNumberNotFound)) echo "disabled" ?>>
                                <?php
                                foreach ($states as $key => $value) {
                                    if ($state == $key) {
                                        echo "<option value='$key' selected>$value</option>";
                                    } else {
                                        echo "<option value='$key'>$value</option>";
                                    }
                                }
                                ?>
                            </select><br />
                            <span id="StateError" class="hide"></span>
                            <label for="ZipTextBox">Zip:*</label><input type="text" id="ZipTextBox" name="ZipTextBox" maxlength="5" value="<?php if (isset($zip)) echo $zip ?>" <?php if (isset($customerNumberNotFound)) echo "disabled" ?>/><br/>
                            <span id="ZipError" class="hide"></span>
                            <label for="WarmTransferDropDownList">Warm Xfer:*</label>
                            <select id="WarmTransferDropDownList" name="WarmTransferDropDownList" <?php if (isset($customerNumberNotFound)) echo "disabled" ?>>
                                <?php
                                foreach ($warmLeads as $key => $value) {
                                    echo "<option value='$key'>$value</option>";
                                }
                                ?>
                            </select><br />
                            <label for="SourceDropDownList">Source:*</label>
                            <select id="SourceDropDownList" name="SourceDropDownList" <?php if (isset($customerNumberNotFound)) echo "disabled" ?>>
                                <?php
                                foreach ($callCenterSources as $key => $value) {
                                    echo "<option value='$key'>$value</option>";
                                }
                                ?>
                            </select><br />
                            <label for="CommentTextArea">Comment:</label>
                            <textarea id="CommentTextArea" name="CommentTextArea" maxlength="255" <?php if (isset($customerNumberNotFound)) echo "disabled" ?>></textarea><br />
                            <br />
                        </div>
                        <input type="submit" id="submit" value="">
                    </fieldset>
                </form>
            </section>
        </div>
        

        <?php require "req/footer.php"; ?>
    </body>
</html>