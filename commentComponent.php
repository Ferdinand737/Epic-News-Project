<!-- Props:
        $comment 
        $indent
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
    $sumVotes = User::sumVotes($user_id, "comment_votes", "comment_id", $comment->comment_id);
    
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
<div class='d-flex align-items-center'>
    <div style="width:
    <?php 
        $pix = $indent * 70;
        echo $pix;
    ?>px;">
    </div>
    <table class='table table-dark table-borderless table-bordered'>
        <tbody>
            <tr>
                <td class="align-middle comment-votes-td py-0" rowspan='3'>
                    <div class="d-flex flex-column align-items-center">
                        <form id="comment-vote-<?php echo $comment->comment_id;?>">
                           
                            <input type="hidden" name="voteType" value="comment" />
                            <input type="hidden" name="id" value="<?php echo $comment->comment_id;?>">
                            <input type="hidden" name="userID" value="<?php echo $user_id;?>">

                            <button class="btn btn-default" name="upVote" type="button" onclick="vote(<?php echo $comment->comment_id;?>, 'upVote','comment')"> <span class="up caret <?php echo $upClass?>"></span></button>
                            &nbsp; <?php echo $comment->votes?>
                            <button class="btn btn-default" name="downVote" type="button" onclick="vote(<?php echo $comment->comment_id;?>, 'downVote','comment')"> <span class="down caret <?php echo $downClass?>"></span></button>
                        </form>
                    </div>
                </td>
                <td class="text-muted">
                    <a href="<?php echo "profilePage.php?user=".$comment->user_id;?>">
                        <img src="imageHandler.php?user_id=<?php echo $comment->user_id;?>" class="profile-pic" width="30" height="30">
                        <?php
                            echo $comment->user->username; 
                        ?>
                    </a>
                    &nbsp; &nbsp; &nbsp; 
                    <?php
                        echo date("F j Y H:i:s",$comment->creation_date);
                    ?>
                </td>
            </tr>
            <tr>
                <td class="py-0">
                    <p>
                        <?php echo $comment->content;?>
                    </p>
                </td>
            </tr>
            <tr>
                <td class="pt-0">
                    <?php
                        if(isset($_SESSION['current_user'])){
                            echo "<a href='replyPage.php?comment=".$comment->comment_id."'>reply</a>";
                        }else{
                            echo "<a href='loginPage.php'>reply</a>";
                        }
                    ?> 
                </td>
            </tr>
        </tbody>
    </table>
</div>