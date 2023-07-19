<!-- Props:
        $post 
-->
<?php
    $upClass = "";
    $downClass = "";
    if(isset($_SESSION['current_user'])){
        $user_id = $_SESSION['current_user'];
    }
    else{
        $user_id = 0; //set a value to stop un-registerd voting
    }
    $sumVotes = User::sumVotes($user_id, "post_votes", "post_id", $post->post_id);
    
    if($sumVotes === 0){
        $upClass = "";
        $downClass = "";
    }
    if($sumVotes === 1){
        $upClass = "active-caret";
        $downClass = "";

    }
    if($sumVotes === -1){
        $upClass = "";
        $downClass = "active-caret";
    }
?>
<table id ="<?php echo $post->post_id;?>" class='table table-dark postComponent table-borderless table-bordered'>
    <span id="TopOfTable"></span>
        <tbody>
            <tr>
                <td class="align-middle post-votes-td" rowspan='3'>
                    <div class="d-flex flex-column align-items-center">
                        <form id="post-vote-<?php echo $post->post_id;?>">
                            <input type="hidden" name="voteType" value="post" />
                            <input type="hidden" name="id" value="<?php echo $post->post_id;?>">
                            <input type="hidden" name="userID" value="<?php echo $user_id;?>">
                            <button class="btn btn-default" name="upVote" type="button" onclick="vote(<?php echo $post->post_id;?>, 'upVote','post')"> <span class="up caret <?php echo $upClass?>"></span></button>
                            &nbsp; <?php echo $post->votes?>
                            <button class="btn btn-default" name="downVote" type="button" onclick="vote(<?php echo $post->post_id;?>, 'downVote','post')"> <span class="down caret <?php echo $downClass?>"></span></button>
                        </form>
                    </div>
                </td>
                <td class="pb-0" colspan='3'>
                    <div class='d-flex align-items-center'>   
                        <a href="<?php echo "postContentPage.php?post_id=".$post->post_id;?>"><h4><?php echo $post->title; ?></h4></a>
                    </div>
                </td>
            </tr>
            <tr> 
                <td class="py-0" colspan='2'>
                    <p>
                        <?php 
                            echo $post->content;
                        ?>
                    </p>
                    <?php
                        $images = $post->images;
                        if($images){
                            foreach($images as $image){
                                echo "<img src='data:image/jpeg;base64," . base64_encode($image) . "' style='max-width: 400px; max-height: 400px;' />";
                            }
                        }
                     ?>
                </td>
            </tr>
            <tr class='text-muted'>
                <td>
                    <a href="<?php echo "postContentPage.php?post_id=".$post->post_id;?>">
                        <p>
                            <?php
                                if($post->visible == 1){

                                $comments = Comment::getCommentsForPost($post->post_id);
                                $count = count($comments);
                                echo $count;
                                echo " comments";
                                }
                            ?>
                        </p>
                    </a>
                </td>
                <td><p><?php echo date("F j Y H:i:s",$post->creation_date); ?></p></td>
                <td class="post-user-td pt-0">
                    <a href="profilePage.php?user=<?php echo $post->user_id?>">
                        <p>
                            <img src="imageHandler.php?user_id=<?php echo $post->user_id;?>" class="profile-pic" width="30" height="30">
                            <?php
                                echo $post->user->username; 
                            ?>
                        </p>
                    </a> 
                    <?php
                        if(isset($_SESSION["isAdmin"]) && !strpos($_SERVER["REQUEST_URI"],"banPage.php")){
                            echo "<a href=banPage.php?post_id=".$post->post_id.">BAN/DELETE POST</a>";
                        }
                    ?>
                </td>
            </tr>
        </tbody>
</table>