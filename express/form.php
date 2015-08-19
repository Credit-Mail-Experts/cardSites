<?php
session_start();
ob_start();
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Form</title>

        <?php
        require "req/head.php";
        ?>

        <!-- script to validate the form on this page -->
        <script type="text/javascript" src="js/validate-form.js"></script>
    </head>

    <body>
        <?php
        if (isset($_POST["CustomerNumberTextBox"])) {
            $customerNumber = $_POST["CustomerNumberTextBox"];

            $query = "SELECT * FROM customers WHERE customer_number = '$customerNumber'";
            $result = $database->runQuery($query);

            while ($row = mysql_fetch_array($result)) {
                $firstName = upcfirst($row["first_name"]);
                $middleName = upcfirst($row["middle_name"]);
                $lastName = upcfirst($row["last_name"]);
                $addressOne = upcwords($row["address_one"]);
                $addressTwo = upcwords($row["address_two"]);
                $city = upcwords($row["city"]);
                $state = strtolower($row["state"]);
                $zip = $row["zip"];
                $homePhone = $row["phone"];
            }
            if (!empty($homePhone)) {
                //$homePhone = "(" . substr($homePhone, 0, 3) . ")" . substr($homePhone, 3, 3) . "-" . substr($homePhone, 6, 4);
                $homePhoneOne = substr($homePhone, 0, 3);
                $homePhoneTwo = substr($homePhone, 3, 3);
                $homePhoneThree = substr($homePhone, 6, 4);
            }
        } else if (isset($_GET["customerNumber"])) {
            $customerNumber = $_GET["customerNumber"];

            // Added this on 10/28, all of the below used to not be here. Trying to allow for get data to be forwarded to this page for the report application to use to send test leads
            $query = "SELECT * FROM customers WHERE customer_number = '$customerNumber'";
            $result = $database->runQuery($query);

            while ($row = mysql_fetch_array($result)) {
                $firstName = upcfirst($row["first_name"]);
                $middleName = upcfirst($row["middle_name"]);
                $lastName = upcfirst($row["last_name"]);
                $addressOne = upcwords($row["address_one"]);
                $addressTwo = upcwords($row["address_two"]);
                $city = upcwords($row["city"]);
                $state = strtolower($row["state"]);
                $zip = $row["zip"];
                $homePhone = $row["phone"];
            }
            if (!empty($homePhone)) {
                //$homePhone = "(" . substr($homePhone, 0, 3) . ")" . substr($homePhone, 3, 3) . "-" . substr($homePhone, 6, 4);
                $homePhoneOne = substr($homePhone, 0, 3);
                $homePhoneTwo = substr($homePhone, 3, 3);
                $homePhoneThree = substr($homePhone, 6, 4);
            }
        }
        ?>

        <?php require "req/header.php"; ?>

        <div id="content">
            <section id="step-3">
                <h1>Step 3:</h1>
                <p>Please fill out the following form. We'll connect you with a local dealership.</p>
                <form id="CustomerInformationForm" name="CustomerInformationForm" action="thank-you.php" method="post">
                    <fieldset>
                        <legend>Customer Information</legend>
                        <p><a href="privacy.php" target="_blank">Privacy Notice</a></p>
                        <input type="text" id="FamilyTextBox" name="FamilyTextBox" class="hide" maxlength="64" value="<?php if (isset($_GET["family"])) echo "family" ?>" readonly />
                        <input type="text" id="FriendTextBox" name="FriendTextBox" class="hide" maxlength="64" value="<?php if (isset($_GET["friend"])) echo "friend" ?>" readonly />
                        <input type="text" id="CustomerNumberTextBox" name="CustomerNumberTextBox" class="hide" maxlength="64" value="<?php if (isset($customerNumber)) echo $customerNumber ?>" readonly />
                        <!-- First Column Div -->
                        <div id="form-column-one">
                            <label for="FirstNameTextBox">First Name:</label><input type="text" id="FirstNameTextBox" name="FirstNameTextBox" maxlength="64" value="<?php if (isset($firstName)) echo $firstName ?>" /><br/>
                            <span id="FirstNameError" class="hide"></span>

                            <!-- Middle Name -->
                            <label for="MiddleNameTextBox">Middle Name:</label>
                            <input type="text" id="MiddleNameTextBox" name="MiddleNameTextBox" maxlength="64" value="<?php if (isset($middleName)) echo $middleName ?>" /><br/>

                            <span id="MiddleNameError" class="hide"></span>
                            <!-- Middle Name End -->


                            <!-- Last Name -->
                            <label for="LastNameTextBox">Last Name:</label>
                            <input type="text" id="LastNameTextBox" name="LastNameTextBox" maxlength="64" value="<?php if (isset($lastName)) echo $lastName ?>" /><br/>

                            <span id="LastNameError" class="hide"></span>
                            <!-- Last Name End -->

                            <!-- Home Phone -->
                            <label for="HomePhoneTextBoxOne">Home Phone:</label>
                            <input type="text" id="HomePhoneTextBoxOne" name="HomePhoneTextBoxOne" maxlength="3" value="<?php if (isset($homePhoneOne)) echo $homePhoneOne ?>" style="width: 25px;" /> &nbsp; -

                            <input type="text" id="HomePhoneTextBoxTwo" name="HomePhoneTextBoxTwo" maxlength="3" value="<?php if (isset($homePhoneTwo)) echo $homePhoneTwo ?>" style="width: 25px;" /> &nbsp; -

                            <input type="text" id="HomePhoneTextBoxThree" name="HomePhoneTextBoxThree" maxlength="4" value="<?php if (isset($homePhoneThree)) echo $homePhoneThree ?>" style="width: 30px;" /><br/>

                            <span id="HomePhoneError" class="hide"></span>
                            <!-- Home Phone End -->

                            <!-- Work Phone -->
                            <label for="WorkPhoneTextBoxOne">Work Phone:</label>
                            <input type="text" id="WorkPhoneTextBoxOne" name="WorkPhoneTextBoxOne" maxlength="3" value="" style="width: 25px;" /> &nbsp; -
                            <input type="text" id="WorkPhoneTextBoxTwo" name="WorkPhoneTextBoxTwo" maxlength="3" value="" style="width: 25px;" /> &nbsp; -
                            <input type="text" id="WorkPhoneTextBoxThree" name="WorkPhoneTextBoxThree" maxlength="4" value="" style="width: 30px;" /><br/>

                            <span id="WorkPhoneError" class="hide"></span>
                            <!-- Work Phone End -->

                            <!-- Cell Phone -->
                            <label for="CellPhoneTextBoxOne">Cell Phone:</label>
                            <input type="text" id="CellPhoneTextBoxOne" name="CellPhoneTextBoxOne" maxlength="3" value="" style="width: 25px;" /> &nbsp; -
                            <input type="text" id="CellPhoneTextBoxTwo" name="CellPhoneTextBoxTwo" maxlength="3" value="" style="width: 25px;" /> &nbsp; -
                            <input type="text" id="CellPhoneTextBoxThree" name="CellPhoneTextBoxThree" maxlength="4" value="" style="width: 30px;" /><br/>
                            <span id="CellPhoneError" class="hide"></span>
                            <!-- Cell Phone End -->

                            <span id="PhonesError" class="hide"></span>
                            <!-- First Column Div End -->
                        </div>

                        <div id="form-column-two">
                            <label for="EmailTextBox">Email:</label>
                            <input type="text" id="EmailTextBox" name="EmailTextBox" maxlength="64" /><br/>
                            <span id="EmailError" class="hide"></span>
                            <label for="AddressOneTextBox">Address One:</label><input type="text" id="AddressOneTextBox" name="AddressOneTextBox" maxlength="128" value="<?php if (isset($addressOne)) echo $addressOne ?>" /><br/>
                            <span id="AddressOneError" class="hide"></span>
                            <label for="AddressTwoTextBox">Address Two:</label><input type="text" id="AddressTwoTextBox" name="AddressTwoTextBox" maxlength="128" value="<?php if (isset($addressTwo)) echo $addressTwo ?>" /><br/>
                            <span id="AddressTwoError" class="hide"></span>
                            <label for="CityTextBox">City:</label><input type="text" id="CityTextBox" name="CityTextBox" maxlength="64" value="<?php if (isset($city)) echo $city ?>" /><br/>
                            <span id="CityError" class="hide"></span>
                            <label for="StateDropDownList">State:</label>
                            <select id="StateDropDownList" name="StateDropDownList">
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
                            <label for="ZipTextBox">Zip:</label><input type="text" id="ZipTextBox" name="ZipTextBox" maxlength="5" value="<?php if (isset($zip)) echo $zip ?>" /><br/>
                            <span id="ZipError" class="hide"></span>
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