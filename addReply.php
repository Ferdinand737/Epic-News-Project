<?php
    session_start();
    include "headComponent.php";
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $user_id = $_SESSION['current_user'];
        $comment_id = filter_input(INPUT_GET,"comment_id",FILTER_SANITIZE_NUMBER_INT);
        $reply_content = filter_input(INPUT_POST,"replyContent",FILTER_SANITIZE_STRING);

        $comment = Comment::getByID($comment_id);
          
        Comment::newReply($user_id,$comment->post_id,$comment_id,$reply_content);

        header("location: postContentPage.php?post_id=".$comment->post_id);
    }
?>