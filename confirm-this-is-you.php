<?php
session_start();
ob_start();
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>DriveNow Card - Confirm This Is You</title>

        <?php
        require "req/head.php";

//            if (isset($_COOKIE["user"])) {
//                header("Location: thank-you.php");
//            }
        ?>
    </head>

    <body>
        <?php
        $customerNumber = $_POST["CustomerNumberTextBox"];

        if (empty($customerNumber)) {
            header("Location: index.php");
        }

        $query = "SELECT first_name, last_name FROM customers WHERE customer_number = '$customerNumber'";
        $result = $database->runQuery($query);

        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                $firstName = $row["first_name"];
                $lastName = $row["last_name"];
            }
        } else {
            header("Location: index.php?customerNumber=$customerNumber");
        }
        ?>

        <?php require "req/header.php"; ?>

        <!-- jquery dialog boxes-->
        <div class="hide">
            <div id="customer-number-correct" title="Customer Number Correct?">
                <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>Double check the card that you received. Is <?php echo $customerNumber ?> your customer number?</p>
            </div>
            <div id="family" title="Family?">
                <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>Are you a family member of the card holder?</p>
            </div>
            <div id="friend" title="Friend?">
                <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>Are you a friend of the card holder?</p>
            </div>
        </div>

        <div id="content">
            <section id="step-2">
                <form name="CustomerNumberConfirmationForm" action="form.php" method="post">
                    <h1>Step 2:</h1>
                    <p>Is this your name on the card?</p>
                    <input id="customer-number" name="CustomerNumberTextBox" value="<?php echo $customerNumber ?>" readonly />
                    <p id="customer-name"><?php echo strtoupper($firstName) . " " . strtoupper($lastName) ?></p>

                    <input type="button" id="no" value="" onclick="notMe();" />
                    <input type="submit" id="yes" value="" />

                </form>
            </section>
        </div>

        <?php require "req/footer.php"; ?>
    </body>
</html>