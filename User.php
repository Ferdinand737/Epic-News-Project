<?php
class User {
  // Properties
  public $user_id;
  public $username;
  public $email;
  public $password;
  public $is_admin;
  public $creation_date;
  public $profile_picture;
  public $is_active;
  public $karma;
  public $about_me;

  function __construct($row){
    $this -> user_id = $row['user_id'];
    $this -> username = $row['username'];
    $this -> email = $row['email'];
    $this -> password = $row['password'];
    $this -> is_admin = $row['is_admin'] === 0 ? null:1;
    $this -> creation_date = strtotime($row['creation_date']);
    $this -> profile_picture = $row['profile_picture'];
    $this -> is_active = $row['is_active'];
    $this -> karma = User::getKarma($row['user_id']) !== null ? User::getKarma($row['user_id']) : 0;
    $this -> about_me = $row['about_me'];
  }


  public static function sumVotes($user_id, $table, $column, $content_id){
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $sql = "SELECT * FROM ".$table." WHERE ".$column." = ? AND user_id = ?;";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $content_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataRow = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    
    return $dataRow['vote'];
  }
  
  public static function vote($user_id, $table, $column, $content_id, $value){
       
    $hasVoted = 1;
    $sumVotes = User::sumVotes($user_id, $table, $column, $content_id);

    
    if($sumVotes === null){
      $sumVotes = 0;
      $hasVoted = 0;
    }
    
    $newValue = $sumVotes + $value;
    
    $canVote = ($newValue <= 1) && ($newValue >= -1) ? 1:0;
    
    // file_put_contents('debug.txt', "\nhasVoted:".$hasVoted."\n", FILE_APPEND);
    // file_put_contents('debug.txt', "sumVotes:".$sumVotes."\n", FILE_APPEND);
    // file_put_contents('debug.txt', "value:".$value."\n", FILE_APPEND);
    // file_put_contents('debug.txt', "newValue:".$newValue."\n", FILE_APPEND);
    // file_put_contents('debug.txt', "canVote:".$canVote."\n", FILE_APPEND);
    
    if($canVote){
      
      if($hasVoted){
        $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
        $sql = "UPDATE ".$table." SET vote = ? WHERE ".$column." = ? AND user_id = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $newValue, $content_id, $user_id);
        mysqli_stmt_execute($stmt);       
        mysqli_stmt_close($stmt);
        mysqli_close($link);
      }else{
        $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
        $sql = "INSERT INTO ".$table." (".$column.",user_id,vote) VALUES (?,?,?)";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "iii",$content_id, $user_id, $newValue);
        mysqli_stmt_execute($stmt);       
        mysqli_stmt_close($stmt);
        mysqli_close($link);
      }
    }
    return;
  }

  public static function getKarma($user_id){
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $sql = "SELECT SUM(vote_sum) AS total_votes
    FROM (
        SELECT SUM(vote) AS vote_sum
        FROM post_votes
        WHERE post_id IN (SELECT post_id FROM post WHERE user_id = ?)
        UNION ALL
        SELECT SUM(vote) AS vote_sum
        FROM comment_votes
        WHERE comment_id IN (SELECT comment_id FROM comment WHERE user_id = ?)
    ) AS combined_votes;
    ";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $user_id,$user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataRow = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    return $dataRow['total_votes'] !== NULL ? $dataRow['total_votes']:0; 
  }

  public static function searchByUsername($name) {
    $link = mysqli_connect(CONST_DB_HOST, CONST_DB_USERNAME, CONST_DB_PASSWORD, CONST_DB_NAME);
    $sql = "SELECT * FROM user WHERE username LIKE CONCAT('%', ?, '%')";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataTable = mysqli_fetch_all($result,MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    mysqli_close($link);

    $users = array();

    foreach($dataTable as $row){
        $users[] = new User($row);
    }

    return $users;
  }

  public static function getByID($id) {
    $link = mysqli_connect(CONST_DB_HOST, CONST_DB_USERNAME, CONST_DB_PASSWORD, CONST_DB_NAME);
    $sql = "SELECT * FROM user WHERE user_id=?;";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataRow = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    return new User($dataRow);
  }

  public static function getByLogin($login_user,$password){
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $stmt = mysqli_prepare($link,"SELECT * FROM user WHERE username = ? AND password = md5(?)");
    mysqli_stmt_bind_param($stmt,"ss",$login_user,$password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    return new User($row);
  }

  public static function getByEmail($email){
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    return new User($row);
  }
  
  public static function userExists($username, $email) {
    $link = mysqli_connect(CONST_DB_HOST, CONST_DB_USERNAME, CONST_DB_PASSWORD, CONST_DB_NAME);
    $sql = "SELECT user_id FROM user WHERE username=? OR email=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    return mysqli_num_rows($result) == 1;
  }

  //Update functions
  public static function updatePassword($email,$old_password,$new_password){
    $link = mysqli_connect(CONST_DB_HOST, CONST_DB_USERNAME, CONST_DB_PASSWORD, CONST_DB_NAME);
    $sql = "UPDATE user SET `password`=md5(?) WHERE `email`=? AND `password`=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $new_password, $email,$old_password);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
  }

  public static function updateEmail($user_id, $new_email){
    $link = mysqli_connect(CONST_DB_HOST, CONST_DB_USERNAME, CONST_DB_PASSWORD, CONST_DB_NAME);
    $sql = "UPDATE user SET `email`=? WHERE `user_id`=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "si", $new_email,$user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
  }

    public static function updateImage($image, $user_id) {
    $link = mysqli_connect(CONST_DB_HOST, CONST_DB_USERNAME, CONST_DB_PASSWORD, CONST_DB_NAME);
    $sql = "UPDATE user SET `profile_picture`=? WHERE `user_id`=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "si", $image, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
  }

    public static function insertNewUser($new_user, $email, $password) {
    
    $default_image = file_get_contents("database/img_user/default.jpg");

    $link = mysqli_connect(CONST_DB_HOST, CONST_DB_USERNAME, CONST_DB_PASSWORD, CONST_DB_NAME);
    $sql = "INSERT INTO user (`username`, `email`, `password`, `profile_picture`) VALUES (?, ?, md5(?),?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $new_user, $email, $password, $default_image);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
  }

  public static function updateAboutMe($user_id, $about_me){
    $link = mysqli_connect(CONST_DB_HOST, CONST_DB_USERNAME, CONST_DB_PASSWORD, CONST_DB_NAME);
    $sql = "UPDATE user SET `about_me`=? WHERE `user_id`=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "si", $about_me,$user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
  }

  public static function AdminToggleActiveByID($id) {
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $stmt = mysqli_prepare($link, "UPDATE user SET is_active = (is_active*-1) WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
  }

  public static function AdminDeleteByID($id) {
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $stmt = mysqli_prepare($link, "DELETE FROM user WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $stmt = mysqli_prepare($link, "DELETE FROM post WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
  }

  public static function AdminGetAll() {
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $sql = "SELECT * FROM user ORDER BY user_id ASC;";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataTable = mysqli_fetch_all($result,MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    $users = array();
    foreach($dataTable as $row){
        $users[] = new User($row);
    }
    return $users;
  }
}
?>