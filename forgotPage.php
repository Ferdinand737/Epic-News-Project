<?php
  session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include 'headComponent.php'?>
        <title>Epic News</title>
    </head>
    <body>
        <?php include 'navbarComponent.php'?>
        <div class="container mt-5 d-flex justify-content-around">
            <form onsubmit='validateForgotEmail()' class='border border-rounded shadow' action="sendPasswordLink.php" method="post">
                <?php
                    if (isset($_SESSION["forgot_error"])) {
                        echo "<p style='color: red; width: 250px'>" . $_SESSION["forgot_error"] . "</p>";
                        unset($_SESSION["forgot_error"]);
                    }
                ?>
                <div class="pt-3 px-3 mb-3">
                    <label class='form-label' for="email">Email:</label>
                    <input class='form-control' type="email" name="forgot-email" id="forgot-email" required>
                </div>
                <div class="pb-3 px-3 mb-3">
                    <input class='btn btn-primary form-control' type="submit" name="reset-password" id="reset-password" value="Send reset Email">
                </div>
            </form>
        </div>
    </body>
</html>