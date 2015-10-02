<?php
session_start();
ob_start();
?>

<!DOCTYPE HTML>
<html>
    <head>

        <?php
        require "req/head.php";

        echo $site->html->title;

        // if the customer number didn't match anything in the database
        if (isset($_GET["customerNumber"])) {
            echo "<script> noMatch();</script>";
        }

//            if (isset($_COOKIE["user"])) {
//                header("Location: thank-you.php");
//            }
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
                <h1>Step 1:</h1>
                <p>Enter your activation number below and then click the "Next" button to proceed.</p>
                <form id="CustomerNumberForm" name="CustomerNumberForm" action="confirm-this-is-you.php" method="post" onsubmit="return customerNumberEmpty();">
                    <input id="customer-number" name="CustomerNumberTextBox" maxlength="7"/>
                    <input id="continue" name="ContinueButton" type="submit" value="" />
                </form>
            </section>
            <section id="no-customer-number">
                <h1>No Activation Number?:</h1>
                <p>That's okay! Click continue to move to the next step without one.
                <input type="button" id="no-customer-number" value="" onclick="location.href = 'form.php';" />
            </section>
        </div>

        <?php require "req/footer.php"; ?>
    </body>
</html>
