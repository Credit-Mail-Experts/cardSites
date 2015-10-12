<header>
    <?php if (isset($employeeId)) {
        echo "<a href='logout.php' style='float: right;'>Logout</a>";
    }

    if ($_SERVER['REQUEST_URI'] == '/call-center-login.php')  {
        echo "<h1 style='text-align: center; padding-top: 10px;'>CME Call Center</h1>";
    }

    ?>
</header>
