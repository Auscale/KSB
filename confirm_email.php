<?php
  session_start();
  /*if(isset($_SESSION['user_id'])){
    header('location:sub_list.php');
  }*/
  if(isset($_GET['token']) && isset($_GET['id'])){
    $con = mysqli_connect("localhost", "root", "", "ksb");
    $email_token = mysqli_real_escape_string($con, $_GET['token']);
    $user_id = mysqli_real_escape_string($con, $_GET['id']);
    //check token matches id in database
    $query = "select * from user where user_id='$user_id' and email_confirm_token='$email_token';";
    $result = mysqli_query($con, $query);
    if(!$result){
      die(mysqli_error($con));
    }
    $rows=mysqli_num_rows($result);
    if($rows == 1){
      //confirm email
      $query = "update user set email_confirm=1 where user_id='$user_id';";
      $result = mysqli_query($con, $query);
      if(!$result){
        die(mysqli_error($con));
      }
      $_SESSION['message'] = "Email confirmed. Thanks!";
      header('location:sub_list.php');
    } else {
      //no dice
      $_SESSION['message'] = "There was an error confirming your email address. Please try again.";
      header('location:sub_list.php');
    }
    mysqli_close($con);
  } else {
    header('location:sub_list.php');
  }
?>