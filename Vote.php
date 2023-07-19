<?php
    session_start();
    include 'constants.php';
    include 'User.php';
    include 'Comment.php';
    include 'Post.php';
   
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $voteType = filter_input(INPUT_POST,"voteType");
        $id = filter_input(INPUT_POST,"id");
        $userID = filter_input(INPUT_POST,"userID");
        $upVote = filter_input(INPUT_POST,"upVote");
        $downVote = filter_input(INPUT_POST,"downVote");

        header('Content-Type: application/json');

        if($userID == 0){
            http_response_code(400);
            echo json_encode(array("status" => "error"));
            return;
        }

        $value = 0;
        if(isset($upVote)){
            $value = 1;
        }
        if(isset($downVote)){
            $value = -1;
        }
        $table = '';
        if($voteType == "comment"){
            $table = "comment_votes";
            $col = "comment_id";
        }
        if($voteType == "post"){
            $table = "post_votes";
            $col = "post_id";
        }
        
        User::vote($userID,$table,$col,$id,$value);

        echo json_encode(array("status" => "success"));
        return;
    }

?>