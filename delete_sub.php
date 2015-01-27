<?php

  session_start();
  
  if($_SESSION['perm_delete_sub'] == 1 && isset($_GET['sub'])){
    $con = mysqli_connect("localhost", "auscaledb", "124578", "ksb");
    $sub_id = mysqli_real_escape_string($con, $_GET['sub']);
    //get file information
    $query = "select filename, extention, type, lr_exists from sub where sub_id='$sub_id';";
    $result = mysqli_query($con, $query);
    if(!$result){
      die(mysqli_error($con));
    }
    $row = mysqli_fetch_array($result);
    $filename  = $row[0];
    $extention = $row[1];
    $type      = $row[2];
    $lr_exists = $row[3];
    $query = "delete from sub where sub_id='$sub_id';";
    $result = mysqli_query($con, $query);
    if(!$result){
      die(mysqli_error($con));
    }
    //remove tags
    $query = "delete from sub_tag where sub_id='$sub_id';";
    $result = mysqli_query($con, $query);
    if(!$result){
      die(mysqli_error($con));
    }
    //remove favorites
    $query = "delete from user_sub_fav where sub_id='$sub_id';";
    $result = mysqli_query($con, $query);
    if(!result){
      die(mysqli_error($con));
    }
    //remove local file and any LR or thumbs that may or may not be present
    unlink('uploads/' . $filename . '.' . $extention);
    if ($type == 1){
      //image, so delete thumbs
      unlink('uploads/thumbs/' . $filename . '.png');
      if($lr_exists == 1){
        unlink('uploads/LR/' . $filename . '.png');
      }
    }
    //add delete audit table.
    $_SESSION['message'] = "Post deleted successfully.";
    header("location:sub_list.php");
  }
  
?>