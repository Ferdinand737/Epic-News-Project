<?php
    session_start();
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $searchUser = filter_input(INPUT_POST, "SearchName",FILTER_SANITIZE_STRING);
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
                    <h3>Users matching : <i><?php echo "'".$searchUser."'"; ?></i></h3>
                    <?php $users = User::searchByUsername($searchUser);
                        foreach($users as $targetUser){
                            include 'userComponent.php';
                        }
                    ?> 
                </div>
            </div>
            <span id="Bottom"></span>
        </div>
    </body>
</html>









