<?php
    session_start();
    $comment_id = filter_input(INPUT_GET,"comment",FILTER_SANITIZE_STRING);
?>
<html>
    <head>
        <?php include 'headComponent.php'?>
        <title>Reply</title>
    </head>
    <body>
        <header>
            <?php include 'navbarComponent.php'?>
        </header>
        <div id="divMain" class="container-fluid">
            <div class="row w-100" style="flex-wrap:nowrap;">
                <div class="container-fluid divContentPosts">
                    <?php
                        $comment = Comment::getByID($comment_id);
                        $indent = 0;
                        include 'commentComponent.php';
                    ?>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="<?php echo 'addReply.php?comment_id='.$comment_id ;?>" method='post' id='postForm'>
                        <div class="form-group">
                            <textarea class="form-control" name="replyContent" rows="4" required></textarea>
                        </div>
                        <input type="submit" name="submitReply" class="btn btn-primary">  
                    </form>
                </div>
            </div>
        </div>
    </body>
<html>