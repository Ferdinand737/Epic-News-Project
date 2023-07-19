<?php
    session_start();
    $query = explode("=",($_SERVER['QUERY_STRING']));
    if($_SESSION['isAdmin']==0){
       header("location: homePage.php");
    }
?>
<html>
    <head>
        <?php include 'headComponent.php'?>
        <title>Search Results</title>
    </head>
    <body>
        <header>
            <?php 
                include 'navbarComponent.php';
            ?>
        </header>
        <div id="divMain" class="container-fluid">
            <div class="row w-100 mx-auto" style="flex-wrap:nowrap;">  
                <div class="content w-100 mx-auto">
                    <?php if($query[0] == "post_id"){ ?>

                        <div class="card bg-secondary text-light w-100">
					        <div class="card-header mx-auto"><h3>Manage post<h3></div>
						    <div class="card-body">
                            <?php 
                                $post = Post::AdminGetById($query[1]);
                                include 'postComponent.php';
                            ?>
                            <hr>
                            <div class="form-group">
                            <form action="banPage.php?post_id=<?php echo $query[1]; ?>" method="post">
                                   <?php if($post->visible == 1){
                                        echo "<button name='BanPost' onclick='return confirm(","'Are you sure you want to ban this post?'","); class='btn btn-warning'>Ban</button> &nbsp";
                                        }else{
                                        echo "<button name='BanPost' onclick='return confirm(","'Are you sure you want to unban this post?'","); class='btn btn-warning'>Unban</button> &nbsp";
                                        }
                                    ?>
                                <button type="submit" name="deletePost" onclick="return confirm('Are you sure you want to delete this post? This can not be undone');" class="btn btn-danger">Delete</button>
                            </form>
                            </div>
                        </div>
                        
                            <?php } 
                    elseif($query[0] == "user_id"){ ?>
                        <?php                        
                        $targetUser = User::getByID($query[1]);
                        ?>
                        <div class="card bg-secondary text-light w-100">
					        <div class="card-header mx-auto"><h3>Manage users<h3></div>
						    <div class="card-body">
                                <?php
                                    $user_id = $targetUser->user_id;
                                    include "userComponent.php";
                                ?>
                                <hr>
                                <h4>Comment History<h4>
                                <div class="scrollTable">
							        <table id="UserCommentTable" class="table-dark w-100" />
							        <tr><th>POST</th><th>DATE</th><th>COMMENT</th></tr>
							        <?php
                                    $allUserComments = Comment::AdminGetAllByUser($targetUser->user_id);
								    foreach($allUserComments as $comment){
								        echo "<tr><td>","<a href=","'postContentPage.php?post_id=",$comment->post->post_id,"'>",$comment->post->title,"</a>","</td><td>", date("F j Y H:i:s",$comment->creation_date)."</td><td>",$comment->content, "</td></tr>";
								    }
								    ?>
							        </table>
							    </div>
                                <hr>
                                <div class="form-group">
                                <form action="banPage.php?post_id=<?php echo $query[1]; ?>" method="post">
                                    <?php if($targetUser->is_active == 1){
                                        echo "<button type='submit' name='banUser' onclick='return confirm(","'Are you sure you want to ban this User?'","); class='btn btn-warning'>Ban</button> &nbsp";
                                        }else{
                                        echo "<button type='submit' name='banUser' onclick='return confirm(","'Are you sure you want to unban this User?'","); class='btn btn-warning'>Unban</button> &nbsp";
                                        }
                                    ?>
                                    <button type="submit" name="deleteUser" onclick="return confirm('Are you sure you want to delete this User? This can not be undone');" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                        <?php }
                        elseif($query[0] == "sub_id"){
                            $targetSub = SubTopic::getById($query[1]);
                            ?>
                            <div class="card bg-secondary text-light">
                                <div class="card-body mx-auto"><h4>DELETE TOPIC<h4></div>
                                    <div class="card mx-auto text-white bg-dark" style="width:70%;">
                                        <div class="card-header">
                                        Sub Topic Details
                                        </div>
                                            <ul class="list-group list-group-flush text-white bg-dark">
                                                <?php
                                                    echo "<li class='list-group-item bg-dark'>","Topic Title: ", $targetSub -> title,"</li>";
                                                    echo "<li class='list-group-item bg-dark'>","Topic Author: ", $targetSub -> created_by->username,"</li>";
                                                    echo "<li class='list-group-item bg-dark'>","Topic Creation Date: ", $targetSub -> creation_date,"</li>";
                                                ?>
                                            </ul>
                                        </div>
                                        <br>
                                        <div class="mx-auto">
                                        <form action="banPage.php?post_id=<?php echo $query[1]; ?>" method="post">
                                            <a href="TopicPage.php?topicID=<?php echo $query[1] ;?>" class="btn btn-info" role="button">View Sub</a>
                                            <button type="submit" name="deleteTopic" onclick="return confirm('Are you sure you want to delete this Topic? This can not be undone');" class="btn btn-danger">Delete</button>
                                        </form>
                                        </div>
                                    </div>
                        <?php } ?>
                    

                    <?php
                        
                        if(isset($_POST['BanPost']) ){  
                            Post::AdminToggleVisibilityByID($query[1]);
                            echo "<script>
                            alert('Post visibilty toggled.');
                            if ( window.history.replaceState ) {
                            window.history.replaceState( null, null, window.location.href );
                            }
                            window.location.replace('AdminPage.php');
                            </script>";
                        }
                        if(isset($_POST['deletePost'])){    
                            Post::AdminDeleteByID($query[1]);
                            echo "<script>
                            alert('Post deleted.');
                            if ( window.history.replaceState ) {
                            window.history.replaceState( null, null, window.location.href );
                            }
                            window.location.replace('AdminPage.php');
                            </script>";
                        }
                        if(isset($_POST['banUser'])){ 
                            User::AdminToggleActiveByID($query[1]);
                            echo "<script>
                            alert('User active state toggled.');
                            if ( window.history.replaceState ) {
                            window.history.replaceState( null, null, window.location.href );
                            }
                            window.location.replace('AdminPage.php');
                            </script>";
                        }
                        if(isset($_POST['deleteUser'])){
                            User::AdminDeleteByID($query[1]);
                            echo "<script>
                            alert('User deleted.');
                            if ( window.history.replaceState ) {
                            window.history.replaceState( null, null, window.location.href );
                            }
                            window.location.replace('AdminPage.php');
                            </script>";
                        }
                        if(isset($_POST['deleteTopic'])){
                            SubTopic::AdminDeleteByID($query[1]);
                            echo "<script>
                            alert('Topic deleted.');
                            if ( window.history.replaceState ) {
                            window.history.replaceState( null, null, window.location.href );
                            }
                            window.location.replace('AdminPage.php');
                            </script>";
                        }
                    ?>

                </div>
            </div>
        </div>
    </body>
</html>