<?php
    session_start();
    include "headComponent.php";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
      $username = filter_input(INPUT_POST, "reg-username",FILTER_SANITIZE_STRING);
      $password = filter_input(INPUT_POST, "reg-password",FILTER_SANITIZE_STRING);
      $email = filter_input(INPUT_POST, "reg-email");

      $regex_password = "~^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$~";
      if (!preg_match($regex_password, $password)) {
        $_SESSION["register_error_message"] = "Invalid password.Must have:8 characters, 1 letter, 1 number,no symbols. Example: password123";
        header("location: loginPage.php");
        exit();
      }
    
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["register_error_message"] = "Invalid email address. Please enter a valid email address.";
        header("location: loginPage.php");
        exit();
      }

      if (User::userExists($username, $email)) {
        $_SESSION["register_error_message"] = "Username or email is already in use.";
        header("location: loginPage.php");
        exit();
      }

      User::insertNewUser($username, $email, $password);
      $_SESSION["sucess_message"] = "Registration Sucessful!";
    }
    header("location: loginPage.php");
?>