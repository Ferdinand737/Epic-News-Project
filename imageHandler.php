<?php
    session_start();
    include 'constants.php';
    include 'User.php';
    if($_SERVER["REQUEST_METHOD"]=="GET"){
        $user_id = filter_input(INPUT_GET, "user_id",FILTER_SANITIZE_NUMBER_INT);
        header("Content-type: image/jpeg");
        echo User::getById($user_id)->profile_picture;
    }
    
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $user_id = $_SESSION['current_user'];
        $allowed_types = array('jpg', 'png', 'jpeg', 'gif');

        $image = file_get_contents($_FILES['profile-pic']['tmp_name']);

        $detectedType = pathinfo($_FILES['profile-pic']['name'], PATHINFO_EXTENSION);

        $error = !in_array(strtolower($detectedType), $allowed_types) || ($_FILES["profile-pic"]["size"] > 500000);

        if ($error) {
            $_SESSION["image_error_message"] = "Image either too large or wrong file type.";
            header("location: profilePage.php?user=".$user_id);
            exit();
        }

        User::updateImage($image,$user_id);
        header("location: profilePage.php?user=".$user_id);         
    }
?>