<?php
session_start();
ob_start();
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Call Center - Employee Login</title>

        <?php
        require "req/call-center-head.php";

        // if the customer number didn't match anything in the database
        if (isset($_GET["customerNumber"])) {
            echo "<script> noMatch();</script>";
        }
        ?>
    </head>

    <body>
        <?php require "req/header.php"; ?>
        <div id="content">
            <section id="step-3">
                <form class="<?php if (isset($_SESSION['employeeId'])) echo "hide"; ?>" name="LoginForm" method="post">
                    <fieldset>
                        <legend>Login</legend>
                        <label for="EmployeeIdTextBox">Employee ID:</label><input type="text" name="EmployeeIdTextBox" /><br/>
                        <label for="PasswordTextBox">Password:</label><input type="password" name="PasswordTextBox"/><br/>
                        <input type="submit" id="submit" name="SubmitButton" value=""/>
                    </fieldset>
                </form>

                <?php
                if ($_POST) {
                    $employeeId = mysql_real_escape_string($_POST["EmployeeIdTextBox"]);
                    $password = mysql_real_escape_string($_POST["PasswordTextBox"]);
                    $query = "SELECT password FROM logins WHERE login_id = '$employeeId' and type='call-center'";
                    $result = $database->runQuery($query);

                    while ($row = mysql_fetch_array($result)) {
                        $savedPassword = $row['password'];
                    }

                    if (empty($savedPassword)) {
                        echo "<span style='color: red; font-weight: bold;'>Username Doesn't Exist!</span>";
                    } else {
                        if ($savedPassword == md5($password . $salt)) {
                            $_SESSION['employeeId'] = $employeeId;
                            header('Location: call-center-form.php');
                        } else {
                            echo "<span style='color: red; font-weight: bold;'>Incorrect Password!</span>";
                        }
                    }
                }
                ?>
            </section>
        </div>

        <?php require "req/footer.php"; ?>
    </body>
</html>