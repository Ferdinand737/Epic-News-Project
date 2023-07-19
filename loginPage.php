<?php
  session_start();
?>
<!DOCTYPE html>
<html>
    <head>
    <?php include 'headComponent.php'?>
    </head>
    <body>    
        <?php include 'navbarComponent.php'?>
        <center>
            <?php
                if (isset($_SESSION["sucess_message"])) {
                    echo "<h2 style='color: green;'>" . $_SESSION["sucess_message"] . "</h2>";
                    unset($_SESSION["sucess_message"]);
                }
            ?>
        </center>
        <div class="container mt-5 d-flex justify-content-around">
            <form onsubmit='validateLogin()' class='border border-rounded shadow' action="login.php" method="post">
                <?php
                    if (isset($_SESSION["login_error_message"])) {
                        echo "<p style='color: red; width: 250px'>" . $_SESSION["login_error_message"] . "</p>";
                        unset($_SESSION["login_error_message"]);
                    }
                ?>
                <h1 class="text-center">Login</h1>
                <div class="pt-3 px-3 mb-3">
                    <label class='form-label' for="username">Username:</label>
                    <input class='form-control' type="text" name="login-username" id="login-username" required>
                </div>
                <div class="px-3 mb-3">
                    <label class='form-label' for="password">Password:</label>
                    <input class='form-control' type="password" name="login-password" id="login-password" required>
                </div>
                <div class="px-3 mb-3">
                    <a href="forgotPage.php">Forgot password?</a>
                </div>
                <div class="pb-3 px-3 mb-3">
                    <input class='btn btn-primary form-control' type="submit" name="login" id="login" value="Login">
                </div>
            </form>
        </div>
        <div class="container mt-5 d-flex justify-content-around">
            <form onsubmit='validateRegister()'class='border border-rounded shadow' action="register.php" method="post">
                <?php
                    if (isset($_SESSION["register_error_message"])) {
                        echo "<p style='color: red; width: 250px'>" . $_SESSION["register_error_message"] . "</p>";
                        unset($_SESSION["register_error_message"]);
                    }
                ?>
                <h1 class="text-center">Register</h1>
                <div class="pt-3 px-3 mb-3">
                    <label class='form-label' for="username">Username:</label>
                    <input id='register-username' class='form-control' type="text" name="reg-username" id="reg-username" required>
                </div>
                <div class="px-3 mb-3">
                    <label class='form-label' for="email">Email:</label>
                    <input id='register-email' class='form-control' type="email" name="reg-email" id="reg-email" required>
                </div>
                <div class="px-3 mb-3">
                    <label class='form-label' for="password">Password:</label>
                    <input id='register-password' class='form-control' type="password" name="reg-password" id="reg-password" required>
                </div>
                <div class="pb-3 px-3 mb-3">
                    <input class='btn btn-primary form-control' type="submit" name="register" id="register" value="Register">
                </div>
            </form>
        </div>
    </body>
</html>