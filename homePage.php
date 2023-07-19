<?php
    session_start();
    ?>
<html>
    <head>
        <?php include 'headComponent.php'?>
        <title>Epic News</title>
    </head>
    <body>
        <header>
            <?php include 'navbarComponent.php'?>
        </header>
        <div id="divMain" class="container-fluid ">
            <span id="Top"></span>
            <div class="row w-100" style="flex-wrap:nowrap;">     
                <?php include 'sidebarComponent.php'?>
                <div id="postList" class="container-fluid divContentPosts">
                    <?php
                        $source = $_SERVER['REQUEST_URI'];
                        $posts = Post::getAllPosts();
                        include 'postListComponent.php'
                    ?>
                </div>
            </div>
            <span id="Bottom"></span>
        </div>
    </body>
</html>