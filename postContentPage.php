<?php
    session_start();
    $postID =  filter_input(INPUT_GET, "post_id");
    $userID =  $_SESSION['current_user'];
    if(!isset($_SESSION['current_user'])){
        $_SESSION["comment-error-message"] = "You must be logged in to comment.";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include 'headComponent.php'?>
        <title>Disscussion</title>
    </head>
    <body>
        <header>
            <?php include 'navbarComponent.php'?>
        </header>
        <div id="divMain" class="container-fluid">
            <span id="Top"></span>
            <div class="row w-100" style="flex-wrap:nowrap;">
                <?php include 'sidebarComponent.php'?>
                <div class="container-fluid divContentPosts">
                    <section id="secPost">
                        <?php
                            $post = Post::getByID($postID);
                            include 'postComponent.php';
                        ?>
                    </section>            
                    <div class="w-50">
                        <?php
                            if (isset($_SESSION["comment-error-message"])) {
                                echo "<p style='color: red; width: 250px'>" . $_SESSION["comment-error-message"] . "</p>";
                                unset($_SESSION["comment-error-message"]);
                            }else{ ?>
                           
                            <form  class="ml-5" action="postContentPage.php?post_id=<?php echo $postID; ?>" method='post' id='textArea' >    
                                <div class="form-group">
                                    <textarea class="form-control" name="mytext" rows="4" required placeholder="Comment Here"></textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <input type="submit" name="submit" class="btn btn-primary"/>
                                </div>
                            </form>
                
                            <br>
                            <br>
                        <?php } ?>
                    </div>
                    <div id="refreshComments">
                        <?php include 'commentListComponent.php'?>
                    </div>
                <div>
                    <?php
                      
                        if(isset($_POST['mytext'])){
                            unset($_POST['mytext']);
                            $textarea = filter_input(INPUT_POST,"mytext",FILTER_SANITIZE_STRING);                   
                            Comment::newComment($userID,$postID,$textarea);
                            echo "<script>
                            if ( window.history.replaceState ) {
                            window.history.replaceState( null, null, window.location.href );
                            }
                            $( document ).ready(function() {
                            $('body').load(window.location.href);
                            });
                            </script>";
                        }
                    ?>
                </div>    
            </div>
        </div>
        <span id="Bottom"></span>
    </body>
</html>