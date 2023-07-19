<?php
class SubTopic{
  // Properties
    public $sub_id;
    public $title; 
    public $visible; 
    public $created_by;
    public $creation_date;

  function __construct($dataRow){;
    $this -> sub_id = $dataRow['sub_id'];
    $this -> title = $dataRow['title'];
    $this -> visible = $dataRow['visible'];
    $this -> creation_date = $dataRow['creation_date'];
    $this -> created_by = User::getByID($dataRow['created_by']);
  }

  public static function getAll(){
    $sql = "SELECT * FROM sub where visible = 1";
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataTable = mysqli_fetch_all($result,MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    $subTopic = array();
    foreach($dataTable as $row){
        $subTopic[] = new SubTopic($row);
    }
    return $subTopic;
  }

    public static function getById($subID){
    $sql = "SELECT * FROM sub where visible = 1 and sub_id = ?";
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $subID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataRow = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    return new SubTopic($dataRow);
  }

    public static function AdminGetAll(){
    $sql = "SELECT * FROM sub";
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataTable = mysqli_fetch_all($result,MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
    $subTopic = array();
    foreach($dataTable as $row){
        $subTopic[] = new SubTopic($row);
    }
    return $subTopic;
  }

    public static function AdminNewSubTopic($subTopicTitle, $user_id){
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $sql = "INSERT INTO `sub`(`title`, `visible`, `created_by`, `creation_date`) VALUES (?,1,?,NOW());";
    $stmt = mysqli_prepare($link,$sql);
    mysqli_stmt_bind_param($stmt,"si",$subTopicTitle ,$user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
  }

    public static function AdminDeleteByID($id) {
    $link = mysqli_connect(CONST_DB_HOST,CONST_DB_USERNAME,CONST_DB_PASSWORD,CONST_DB_NAME);
    $stmt = mysqli_prepare($link, "DELETE FROM sub WHERE sub_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($link);
  }

}

?>