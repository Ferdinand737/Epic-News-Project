<!-- $props: 
        $targetUser
 -->
<div class="col-md-8 p-5 secUserProfile" style="margin-top:66px;">
    <div class="d-flex align-items-start">
        <img src="imageHandler.php?user_id=<?php echo $targetUser->user_id;?>" alt="Profile Picture" class="profile-pic"  width="200" height="200">
        <div class="ml-5"id="user-info" style="max-width:50%;">
            <div>
                <h4><?php echo $targetUser->username?></h4>
                <p>Member since: <span class="text-muted"><?php echo date("F j Y H:i:s",$targetUser->creation_date); ?></span></p>
                <p><?php echo "Karma: ".$targetUser->karma?></p>
                <p class="about-me-text">About Me: <?php echo $targetUser -> about_me ?></p>
            </div>
            <div>
                <h5>My Posts</h5>
                <?php
                    $allPosts = Post::getAllPosts();
                    foreach($allPosts as $post){
                      
                        if($post->user_id == $targetUser->user_id){
                            echo "<a href='postContentPage.php?post_id=".$post->post_id."'>".$post->title."</a>";
                            echo "<br>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>
    <?php
        if(isset($_SESSION["isAdmin"]) && !strpos($_SERVER["REQUEST_URI"],"banPage.php")){
            echo "<a href=banPage.php?user_id=".$targetUser->user_id.">BAN/DELETE USER</a>";
        }
    ?>
</div> 