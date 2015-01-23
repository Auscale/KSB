<?php
  session_start();
  
  //if user is able to post news
  if(isset($_SESSION['user_id']) && isset($_SESSION['perm_post_news'])){
    $user_id = $_SESSION['user_id'];
    //if deleting
    if(isset($_GET['m']) && isset($_GET['news_id'])){
      if($_GET['m'] === 'delete'){
        $con = mysqli_connect("localhost", "root", "", "ksb");
        $news_id = mysqli_real_escape_string($con, $_GET['news_id']);
        $query = "delete from news where news_id='$news_id';";
        $result = mysqli_query($con, $query);
        if(!$result){
          die(mysqli_error($con));
        }
        $_SESSION['message'] = "News deleted.";
        header("location:news.php");
      }
    } else if(isset($_POST['news_title']) && isset($_POST['news_body'])){
      $con = mysqli_connect("localhost", "root", "", "ksb");
      $head = mysqli_real_escape_string($con, $_POST['news_title']);
      $body = mysqli_real_escape_string($con, $_POST['news_body']);
      $user_id = $_SESSION['user_id'];
      $mysql_date = date("Y-m-d H:i:s", time());
      $query = "insert into news (head, body, create_by, create_date) values ('$head','$body','$user_id','$mysql_date');";
      $result = mysqli_query($con, $query);
      if(!$result){
        die(mysqli_error($con));
      } else {
        header("location:news.php");
      }
    } else {
      $_SESSION['message'] = "Please enter both a title and body text.";
      header("location:post_news.php");
    }
  } else {
    //not logged on or no permissions
    if(!isset($_SESSION['user_id'])){
      $_SESSION['message'] = "You must be signed in to post news.";
      header("location:sign_in.php");
    } else {
      $_SESSION['message'] = "You do not have permission to post news.";
      header("location:sub_list.php");
    }
  }
  
?>