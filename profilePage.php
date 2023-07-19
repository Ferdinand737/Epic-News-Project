<?php
    session_start();
    $user_id =  filter_input(INPUT_GET, "user",FILTER_SANITIZE_NUMBER_INT);
    $current_user = $_SESSION['current_user'];
    $isMe = $user_id == $current_user;
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
        <div class="container-fluid ">     
            <div class="container-fluid d-flex flex-column align-items-center">
               
                <?php
                 $targetUser = User::getByID($user_id);
                include 'userComponent.php'; ?>
                <?php if($isMe){include 'editProfileComponent.php';}?>
            </div>        
        </div>
    </body>
</html>