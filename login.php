<?php
    session_start();
   include "headComponent.php";
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $username = filter_input(INPUT_POST,"login-username",FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST,"login-password",FILTER_SANITIZE_STRING);
        $current_user = User::getByLogin($username,$password);
 
        if($current_user->is_active === -1){
            $_SESSION["login_error_message"] = "This account has been banned";
            echo "<script>window.location.href = 'loginPage.php'</script>";
            exit();
        }

        if(isset($current_user->username)){      
            $_SESSION['current_user'] = $current_user -> user_id;
            $_SESSION['username'] = $current_user -> username;
            $_SESSION['isAdmin'] = $current_user -> is_admin;
            unset($_SESSION["comment-error-message"]);
            echo "<script>window.location.href = 'homePage.php'</script>";    
        }else{
            $_SESSION["login_error_message"] = "Invalid username or password, try again.";
            echo "<script>window.location.href = 'loginPage.php'</script>";
        }
    }
?>