<?php
    session_start();
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $searchTitle = filter_input(INPUT_POST, "SearchTitle",FILTER_SANITIZE_STRING);
    }
?>
<html>
    <head>
        <?php include 'headComponent.php'?>
        <title>Search Results</title>
    </head>
    <body>
        <header>
            <?php include 'navbarComponent.php'?>
        </header>
        <div id="divMain" class="container-fluid ">
            <span id="Top"></span>
            <div class="row w-100" style="flex-wrap:nowrap;">     
                <?php include 'sidebarComponent.php'?>
                <div class="container-fluid divContentPosts">
                    <h3>Post Titles containing: <i><?php echo "'".$searchTitle."'"; ?></i></h3>
                    <?php $posts = Post::SearchByTitle($searchTitle); 
                        include 'postListComponent.php';
                    ?> 
                </div>
            </div>
            <span id="Bottom"></span>
        </div>
    </body>
</html>