<?php
   session_start();
   include "headComponent.php";
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $userID =  $_SESSION['current_user'];
        $sub_id = filter_input(INPUT_POST,"SubTopic",FILTER_SANITIZE_STRING);
        
        
        $title = filter_input(INPUT_POST,"postTitle",FILTER_SANITIZE_STRING);
        $content = filter_input(INPUT_POST,"postContent",FILTER_SANITIZE_STRING);
        
        if(isset($userID)){
            $post_id = Post::newPost($userID,$sub_id,$title,$content);
        }
        
        
        if(!empty(array_filter($_FILES['postImages']['name']))){
            var_dump($_FILES['postImages']['name']);
            $allowed_types = array('jpg', 'png', 'jpeg', 'gif');

            foreach($_FILES['postImages']['tmp_name'] as $key => $value){                
                
                $file_tmpname = $_FILES['postImages']['tmp_name'][$key];
                $file_name = $_FILES['postImages']['name'][$key];
                $file_size = $_FILES['postImages']['size'][$key];
                $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                
                $error = !in_array(strtolower($file_ext), $allowed_types) || ($file_size > 500000);
               
                if ($error) {
                       $_SESSION["image_error_message"] = "Image either too large or wrong file type.";
                       header("location: createPage.php");
                       exit();
                }

                $image = file_get_contents($file_tmpname);
                Post::uploadImage($post_id,$image);
            }
        }  
        header("location: homePage.php");
        exit();
    }
?>