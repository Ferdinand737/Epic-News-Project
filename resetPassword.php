<?php
    session_start();
    include 'constants.php';
    include 'User.php';
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $email = filter_input(INPUT_POST,"email",FILTER_VALIDATE_EMAIL);
        $new_password = filter_input(INPUT_POST,"new-password");
        $old_password = filter_input(INPUT_POST,"old-password");
        $regex_password = "~^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$~";
        file_put_contents('debug.txt', $new_password."\n");
        
        if (!preg_match($regex_password, $new_password)) {
            $_SESSION["reset_error"] = "Invalid password. Please enter a password with at least 8 characters, 1 letter, and 1 number.";
            header("location: resetPage.php?key=".$email."&".$old_password);
            exit();
        }

        User::updatePassword($email,$old_password,$new_password);
        header("location: loginPage.php");
    }
?>