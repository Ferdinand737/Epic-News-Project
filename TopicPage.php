<?php
    session_start();
?>
<html>
    <head>
        <?php 
            include 'headComponent.php';
            $subID =  $_GET['topicID'];
            $sub = SubTopic::getByID($subID);
        ?>
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
                    <h3>TOPIC: <i><?php echo $sub->title; ?></i></h3>
                    <?php $posts = Post::GetBySub($subID); 
                        include 'postListComponent.php';
                    ?>  
                </div>
            </div>
            <span id="Bottom"></span>
        </div>
    </body>
</html>