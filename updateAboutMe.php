<?php
    session_start();
    include "headComponent.php";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $user_id = filter_input(INPUT_POST, "user_id",FILTER_SANITIZE_NUMBER_INT);
        $about_me = filter_input(INPUT_POST, "about-me");
        
        User::updateAboutMe($user_id, $about_me);

        header("location: profilePage.php?user=".$user_id);
    }
?>