<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <?php include 'headComponent.php'?>
        <title>Create Post</title>
    </head>
<body>
    <?php include 'navbarComponent.php'?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="my-3">Create Post</h2>
                <form action='createPost.php' method='post' id='postForm' enctype='multipart/form-data'>
                    <div class="dropdown form-group">
                            <label for="SubTopic">Sub Topic</label><br>
                            <select class="btn btn-secondary dropdown-toggle" type="button" id="SubTopic" name="SubTopic" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="dropdown-menu" aria-labelledby="SubTopic">
                                <?php
                                    $allSubTopics = SubTopic::getAll();
                                    foreach($allSubTopics as $SubTopic){
                                    echo "<option class='dropdown-item' value=",$SubTopic->sub_id,">",$SubTopic->title,"</option >";
                                    }
                                ?>
                            </div>
                            </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="postTitle">Title</label>
                        <textarea class="form-control" name="postTitle" rows="1" placeholder="Enter post title" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="postContent">Content</label>
                        <textarea class="form-control" name="postContent" rows="8" required></textarea>
                    </div>
                    <input type="submit" name="submitPost" class="btn btn-primary"/>
                    <br>
                    <br>
                    <br>
                    <h4>Upload Photos</h4>
                    <?php 
                        if (isset($_SESSION["image_error_message"])) {
                            echo "<p style='color: red; width: 250px'>" . $_SESSION["image_error_message"] . "</p>";
                            unset($_SESSION["image_error_message"]);
                        }
                    ?>
                    <input type="file" name="postImages[]" multiple accept="image/*"/>
                </form>
            </div>
        </div>
    </div>
</body>
</html>