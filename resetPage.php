<?php
    session_start();
    $email = filter_input(INPUT_GET,"key",FILTER_VALIDATE_EMAIL);
    $old_password = filter_input(INPUT_GET,"reset");
?>

<!DOCTYPE html>
<html>
    <head>
        <?php
            include 'headComponent.php';
            if($_SERVER["REQUEST_METHOD"]=="GET"){
                $user = User::getByEmail($email);  
                if($user->password != $old_password){
                    $_SESSION["forgot_error"] = "Something went wrong :/, Try again.";
                    header("location: forgotPage.php");
                    exit();
                }
            }
            ?>
        <title>Epic News</title>
    </head>
    <body>
        <?php include 'navbarComponent.php'?>
        <div class="container mt-5 d-flex justify-content-around">
            <form onsubmit='validateResetPassword()' class='border border-rounded shadow' action="resetPassword.php" method="post">
                <?php
                    if (isset($_SESSION["reset_error"])) {
                        echo "<p style='color: red; width: 250px'>" . $_SESSION["reset_error"] . "</p>";
                        unset($_SESSION["reset_error"]);
                    }
                    ?>
                <input type="hidden" name="email" value="<?php echo $email;?>">
                <input type="hidden" name="old-password" value="<?php echo $old_password;?>">
                <div class="pt-3 px-3 mb-3">
                    <label class='form-label' for="email">New Password</label>
                    <input class='form-control' type="password" name="new-password" id="new-password" required>
                </div>
                <div class="pb-3 px-3 mb-3">
                    <input class='btn btn-primary form-control' type="submit" value="Confirm New Password">
                </div>
            </form>
        </div>
    </body>
</html>
