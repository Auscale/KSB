<?php

  session_start();

  if(!isset($_POST['username']) || isset($_SESSION['user_id'])){
    header('location:index.php');
  } else {
    $con = mysqli_connect("localhost", "auscaledb", "124578", "ksb");
    
    if(mysqli_connect_errno()){
      echo "Database connection failed: " . mysqli_connect_error();
    }
    
    //Check user credentials
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $repeat_password = mysqli_real_escape_string($con, $_POST['repeat_password']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    if($password == $repeat_password){
      
      $check_user = "select * from user where username='$username';";
      
      $check_result = mysqli_query($con, $check_user);
      if (!$check_result) {
        die(mysqli_error($con));
      }
      
      $user_exists = mysqli_num_rows($check_result);
      if($user_exists == 0){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $mysql_time = date("Y-m-d H:i:s", time());
        //generate email confirm token_get_all
        $email_token = uniqid(mt_rand(),true);
        $email_token = str_replace(".", "_", $email_token);
        $create_user = "insert into user (username, password, email, create_date, register_ip, email_confirm_token) values ('$username', '$hash', '$email', '$mysql_time', '" . $_SERVER['REMOTE_ADDR'] . "','$email_token');";
        $create_result = mysqli_query($con, $create_user);
        //log user in afterwards?
        //  $_SESSION['username'] = $username;
        //  $_SESSION['user_id'] = mysqli_insert_id($con);
        //  header("location:sub_list.php");
        //prompt user to sign in - saves having to make changes to log in settings in two places.
        //send user an email and prevent sign on until activated? or email optional?
        header("location:sign_in.php");
      } else {
        echo("Username already exists.");
      }
    } else {
      echo("Passwords do not match.");
    }
    mysqli_close($con);
  }
?>