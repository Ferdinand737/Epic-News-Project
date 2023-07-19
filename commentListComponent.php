<!-- Props:
        $postID 
-->

<section id="secComments">
    <?php
        function traverseChildren($comment,$indent){
            
            $children = Comment::getAllChildren($comment->comment_id);
            
            include 'commentComponent.php';
            
            if(count($children)==0){
                return;
            }

            foreach($children as $child){
                traverseChildren($child,$indent + 1);
            }   
        }
        
        $comments = array_reverse(Comment::getCommentsForPost($postID));

        foreach($comments as $comment){
            traverseChildren($comment,0);
        }
    ?>
</section>