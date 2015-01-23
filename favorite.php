<?php
  session_start();
  if(!isset($_SESSION['user_id']) || !isset($_GET['sub'])){
    header('location:sub_list.php');
  }
  $con = mysqli_connect("localhost", "root", "", "ksb");
  $sub_id = mysqli_real_escape_string($con, $_GET['sub']);
  $user_id = $_SESSION['user_id'];
  //Check to see if the user already has it in their favorites, if so, unfavorite it.
  //maybe in the future, this could be two different scripts? I dunno.
  $query = "select * from user_sub_fav where user_id='$user_id' and sub_id='$sub_id';";
  $result = mysqli_query($con, $query);
  if(!$result){
    die(mysqli_error($con));
  }
  $count = mysqli_num_rows($result);
  if($count == 1){
    //remove from favorites
    $query = "delete from user_sub_fav where user_id='$user_id' and sub_id='$sub_id';";
    $result = mysqli_query($con, $query);
    if(!$result){
      die(mysqli_error($con));
    } else {
      header("location:sub_list.php?m=fav&user=$user_id");
    }
  } else {
    //add to favorites
    $mysql_time = date("Y-m-d H:i:s", time());
    $query = "insert into user_sub_fav (user_id, sub_id, create_date) values ('" . $_SESSION['user_id'] . "', '$sub_id', '$mysql_time');";
    $result = mysqli_query($con, $query);
    if(!$result){
      die(mysqli_error($con));
    } else {
      header("location:sub_list.php?m=fav&user=$user_id");
    }
  }
  mysqli_close($con);
?>