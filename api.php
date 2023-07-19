<?php
    session_start();
    //these includes have to be here for asyc to work
    include 'User.php';
    include 'Post.php';
    include 'Comment.php';
    include 'constants.php';
    if($_SERVER["REQUEST_METHOD"]=="GET"){
        $page = filter_input(INPUT_GET,"page",FILTER_SANITIZE_STRING);
        $chart = filter_input(INPUT_GET,"chart",FILTER_SANITIZE_STRING);

        if($page){
            if($page == "home"){
    
                ob_start();
                $source = "/project-Ferdinand737/homePage.php";
                $posts = Post::getAllPosts();
                include 'postListComponent.php';
                $response = ob_get_clean(); 
                header('Content-Type: text/html'); 
                echo $response;
                return;
            }
    
            if($page == "post"){
                
                $postID = filter_input(INPUT_GET,"id",FILTER_SANITIZE_NUMBER_INT);
                $content = filter_input(INPUT_GET,"content",FILTER_SANITIZE_STRING);
                $source = "/project-Ferdinand737/postContentPage.php?post_id=".$postID;
                if($content == "comments"){
    
                    ob_start();
                    include 'commentListComponent.php';
                    $response = ob_get_clean();  
                    header('Content-Type: text/html'); 
                    echo $response;
                    return;
    
                }else{
    
                    ob_start();
                    $post = Post::getByID($postID);
                    include 'postComponent.php';
                    $response = ob_get_clean(); 
                    header('Content-Type: text/html'); 
                    echo $response;
                    return;
                }
            }
        }
        if($chart){
            header('Content-Type: application/json');

            $data = Post::getTraffic($chart);
           
            $counts = [];
            $dates = [];

            foreach ($data as $row) {
                $counts[] = $row['count'];
                $dates[] = $row['date'];
            }

            echo json_encode(array("status" => "success", "counts" => $counts, "dates" => $dates));
            return;
        }
       
    }
?>