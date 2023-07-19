<?php
class Comment {
  // Properties
  public $comment_id;
  public $user_id;
  public $post_id;
  public $parent_id;
  public $votes;
  public $content;
  public $creation_date;
  public $visible;
  public $user;
  public $post;
  public $voted;

  function __construct($dataRow){
    if($dataRow!=null){
        $this -> comment_id = $dataRow['comment_id'];
        $this -> user_id = $dataRow['user_id'];
        $this -> post_id = $dataRow['post_id'];
        $this -> parent_id = $dataRow['parent_id'];
        if($dataRow['votes'] != null){
                $this -> votes = $dataRow['votes'];
        }else{
            $this -> votes = 0;
        }
        $this -> content = $dataRow['content'];
        $this -> creation_date = strtotime($dataRow['creation_date']);
        $this -> user = User::getByID($dataRow['user_id']);
        $this -> post = Post::getByID($dataRow['post_id']);
      }
  }

  // Methods
  public static function getByID($id) {
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $sql = 
    "SELECT * FROM comment 
    left join (SELECT SUM(vote) as votes, comment_id as vote_comment_id from comment_votes GROUP BY comment_id) votes on votes.vote_comment_id = comment.comment_id 
    WHERE comment_id=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataRow = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    return new Comment($dataRow);
  }
  
  public static function getAllChildren($parent_id){
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $sql = 
    "SELECT * FROM comment 
    left join (SELECT SUM(vote) as votes, comment_id as vote_comment_id from comment_votes GROUP BY comment_id) votes on votes.vote_comment_id = comment.comment_id 
    WHERE parent_id=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $parent_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataTable = mysqli_fetch_all($result,MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    mysqli_close($link);

    $children = array();

    foreach($dataTable as $row){
        $children[] = new Comment($row);
    }
    
    return $children;
  }
  
  public static function getCommentsForPost($post_id){
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $sql = "SELECT * FROM comment 
    left join (SELECT SUM(vote) as votes, comment_id as vote_comment_id from comment_votes GROUP BY comment_id) votes on votes.vote_comment_id = comment.comment_id 
    WHERE post_id=? AND parent_id IS NULL AND visible=1";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataTable = mysqli_fetch_all($result,MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    mysqli_close($link);

    $comments = array();

    foreach($dataTable as $row){
        $comments[] = new Comment($row);
    }
    
    return $comments;
  }

  public static function AdminGetAllByUser($user_id){
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $sql = "SELECT * FROM comment 
    left join (SELECT SUM(vote) as votes, comment_id as vote_comment_id from comment_votes GROUP BY comment_id) votes on votes.vote_comment_id = comment.comment_id 
    WHERE user_id=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataTable = mysqli_fetch_all($result,MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    mysqli_close($link);

    $comments = array();

    foreach($dataTable as $row){
        $comments[] = new Comment($row);
    }
    
    return $comments;
  }
  public static function newReply($user,$post,$parentComment,$text){
    if(isset($user)==false || isset($text)==false ){
        return;
    }
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $sql = "INSERT INTO comment (user_id,post_id, parent_id,content) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "iiis", $user, $post, $parentComment, $text);
    mysqli_stmt_execute($stmt);  
    mysqli_stmt_close($stmt);
    mysqli_close($link);
  }

  public static function newComment($user,$post,$text){
    if(isset($user)==false || isset($post)==false || isset($text)==false ){
        return;
    }
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $sql = "INSERT INTO comment (user_id,post_id, parent_id,content,creation_date,visible) VALUES (?, ?, NULL, ?,NOW(),1);";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "iis", $user, $post, $text);
    mysqli_stmt_execute($stmt);  
    mysqli_stmt_close($stmt);
    mysqli_close($link);
  } 
 }
?>