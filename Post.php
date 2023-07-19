<?php
class Post {
 
    public $post_id;
    public $user_id;
    public $votes;
    public $title; 
    public $content; 
    public $visible; 
    public $creation_date;
    public $user;
    public $images;

  function __construct($dataRow){;
    $this -> post_id = $dataRow['post_id'];
    $this -> user_id = $dataRow['user_id'];
    if($dataRow['votes'] != null){
        $this -> votes = $dataRow['votes'];
    }else{
        $this -> votes = 0;
    }
    $this -> title = $dataRow['title'];
    $this -> content = $dataRow['content'];
    $this -> visible = $dataRow['visible'];
    $this -> creation_date = strtotime($dataRow['creation_date']);
    $this -> user = User::getByID($dataRow['user_id']);
    $this -> images = Post::getImages($dataRow['post_id']);
  }

  public static function getImages($post_id){
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $sql = "SELECT `img` FROM `post_img` WHERE `post_id`=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataTable = mysqli_fetch_all($result,MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    mysqli_close($link);

    $images = array();

    foreach($dataTable as $row){
        $images[] = $row['img'];
    }
    
    return $images;
  }

  public static function getByID($id) {
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $stmt = mysqli_prepare($link, "SELECT * FROM post left join (SELECT SUM(vote) as votes, post_id as vote_post_id from post_votes GROUP BY post_id) votes on votes.vote_post_id = post.post_id WHERE visible = 1 AND post.post_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataRow = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    return new Post($dataRow);
  }

  public static function AdminGetByID($id) {
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $stmt = mysqli_prepare($link, "SELECT * FROM post left join (SELECT SUM(vote) as votes, post_id as vote_post_id from post_votes GROUP BY post_id) votes on votes.vote_post_id = post.post_id WHERE post.post_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataRow = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    return new Post($dataRow);
  }

  public static function AdminGetAll() {
    $sql = "SELECT * FROM post left join (SELECT SUM(vote) as votes, post_id as vote_post_id from post_votes GROUP BY post_id) votes on votes.vote_post_id = post.post_id ORDER BY creation_date DESC";
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataTable = mysqli_fetch_all($result,MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    mysqli_close($link);

    $posts = array();

    foreach($dataTable as $row){
        $posts[] = new Post($row);
    }
    
    return $posts;
  }

    public static function GetBySub($subID) {
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $sql = 
    "SELECT * FROM post 
    left join (SELECT SUM(vote) as votes, post_id as vote_post_id from post_votes GROUP BY post_id) votes on votes.vote_post_id = post.post_id
    WHERE visible=1 AND post.parent_sub_id = ? ;";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i',$subID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataTable = mysqli_fetch_all($result,MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    $posts = array();

    foreach($dataTable as $row){
        $posts[] = new Post($row);
    }
    
    return $posts;
  }

  public static function SearchByTitle($title) {
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $sql = 
    "SELECT * FROM post 
    left join (SELECT SUM(vote) as votes, post_id as vote_post_id from post_votes GROUP BY post_id) votes on votes.vote_post_id = post.post_id
    WHERE visible=1 AND title LIKE '%$title%';";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataTable = mysqli_fetch_all($result,MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    $posts = array();

    foreach($dataTable as $row){
        $posts[] = new Post($row);
    }
    
    return $posts;
  }

  public static function getAllPosts(){
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $stmt = mysqli_prepare($link, 
    "SELECT * FROM post 
    left join (SELECT SUM(vote) as votes, post_id as vote_post_id from post_votes GROUP BY post_id) votes on votes.vote_post_id = post.post_id 
    WHERE visible = 1 
    ORDER BY post.post_id DESC");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataTable = mysqli_fetch_all($result,MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    mysqli_close($link);

    $posts = array();

    foreach($dataTable as $row){
        $posts[] = new Post($row);
    }
    return $posts;
  }

  public static function newPost($user_id,$sub_id, $title,$content){
    if(isset($user_id)==false || isset($title)==false || isset($content)==false || isset($sub_id)==false){
      return;
    }
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $sql = "INSERT INTO `post`( `user_id`,`parent_sub_id`, `title`, `content`, `visible`, `creation_date`) VALUES (?,?,?,?,1,NOW())";
    $stmt = mysqli_prepare($link,$sql);
    mysqli_stmt_bind_param($stmt,"iiss", $user_id,$sub_id,$title,$content);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($link);

    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $sql = "SELECT post_id FROM post WHERE user_id=? AND parent_sub_id=? AND title=? AND content=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'ssss', $user_id,$sub_id,$title,$content);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataRow = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    return $dataRow['post_id'];
  }

  public static function AdminToggleVisibilityByID($id) {
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $stmt = mysqli_prepare($link, "UPDATE post SET visible = (visible*-1) WHERE post_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
  }

  public static function AdminDeleteByID($id) {
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $stmt = mysqli_prepare($link, "DELETE FROM post WHERE post_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
  }

  public static function uploadImage($post_id,$image){
    $link = mysqli_connect(CONST_DB_HOST, CONST_DB_USERNAME, CONST_DB_PASSWORD, CONST_DB_NAME);
    $sql = "INSERT INTO `post_img` (`post_id`, `img`) VALUES (?,?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "is", $post_id, $image);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
  }

  public static function getTraffic($table){
    $link = mysqli_connect(CONST_DB_HOST, CONST_DB_USERNAME, CONST_DB_PASSWORD, CONST_DB_NAME);
    $sql = "SELECT DATE(creation_date) AS date, COUNT(*) AS count FROM ".$table." GROUP BY DATE(creation_date) ORDER BY date;";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataTable = mysqli_fetch_all($result,MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    return $dataTable;
  }
}
?>