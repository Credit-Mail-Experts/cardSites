<?php
session_start();
ob_start();
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Walk-In - Main Page</title>

        <?php
        require "req/head.php";

        if (!isset($employeeId)) {
            header('Location: walkin-login.php');
        } else {
            // We deprecated this page, as long as they are logged in send them to the form
            header('Location: walkin-form.php');
        }

        // if the customer number didn't match anything in the database
        if (isset($_GET["customerNumber"])) {
            echo "<script> noMatch();</script>";
        }
        ?>
    </head>

    <body>
        <?php require "req/header.php"; ?>

        <div class="hide">
            <div id="enter-customer-number" title="Please Enter Your Customer Number">
                <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>Please enter your customer number or click the "No Customer #" button to continue without a customer number.</p>
            </div>
            <div id="no-match-found" title="No Match Found!">
                <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>No match was found for customer number <?php if (isset($_GET["customerNumber"])) echo $_GET["customerNumber"]; ?>. If the number is correct please click "Continue Without a Customer Number" or else click "Re-enter My Customer Number" to reenter your customer number.</p>
            </div>
        </div>

        <div id="content">
            <section id="step-1">
                <form id="CustomerNumberForm" name="CustomerNumberForm" action="walkin-form.php" method="post" onsubmit="return customerNumberEmpty();">
                    <img src="img/step-1.png" />
                    <input id="customer-number" name="CustomerNumberTextBox" maxlength="7"/>
                    <input id="continue" name="ContinueButton" type="submit" value="" />
                </form>
            </section>
        </div>
        <?php require "req/footer.php"; ?>
    </body>
</html>