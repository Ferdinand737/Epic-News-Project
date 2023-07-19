<?php
    session_start();
    include "headComponent.php";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $user_id = filter_input(INPUT_POST, "user_id",FILTER_SANITIZE_NUMBER_INT);
        $new_email = filter_input(INPUT_POST, "email");

        if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION["email_error_message"] = "Invalid email address. Please enter a valid email address.";
            header("location: profilePage.php?user=".$user_id);
            exit();
        }
        
        User::updateEmail($user_id, $new_email);

        $_SESSION["email_success_message"] = "Sucessfully changed email!";
        header("location: profilePage.php?user=".$user_id);
    }
?>