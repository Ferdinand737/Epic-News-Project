<?php
    include 'constants.php';
    include 'User.php';
    include 'Post.php';
    $db_files = array('database/database.db', 'database/databaseTest.db');

    foreach ($db_files as $db_file) {
        $link = mysqli_connect(CONST_DB_HOST, CONST_DB_USERNAME, CONST_DB_PASSWORD, CONST_DB_NAME);

        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        $sql = file_get_contents($db_file);
        $queries = preg_split('/;\s*\n/', $sql);

        foreach ($queries as $query) {
            if (trim($query) !== '') {
                $result = mysqli_query($link, $query);

                if (!$result) {
                    printf("Error executing query: %s\nQuery: %s\n", mysqli_error($link), $query);
                    exit();
                }
            }
        }

        $link->close();
    }

    $dir = "database/img_user/";
    $images = array($dir . "1.jpg", $dir . "2.jpg", $dir . "3.jpg", $dir . "4.jpg", $dir . "5.jpg", $dir . "6.jpg");

    $user_id = 1;
    foreach ($images as $image) {
        $fileContent = file_get_contents($image);
        User::updateImage($fileContent, $user_id);
        $user_id++;
    }
    $dir = "database/img_post/";

    $fileContent = file_get_contents($dir . "3.jpg");
    Post::uploadImage(3,$fileContent);

    $fileContent = file_get_contents($dir . "4.gif",);
    Post::uploadImage(4,$fileContent);

    $fileContent = file_get_contents($dir . "8.0.jpg");
    Post::uploadImage(8,$fileContent);

    $fileContent = file_get_contents($dir . "8.1.jpg");
    Post::uploadImage(8,$fileContent);

    $fileContent = file_get_contents($dir . "7.jpg");
    Post::uploadImage(7,$fileContent);
    
    header("location: logout.php");
?>