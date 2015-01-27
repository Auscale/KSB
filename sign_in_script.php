<?php
  session_start();
  //establish connection
  $con = mysqli_connect("localhost", "auscaledb", "124578", "ksb");
  
  if(mysqli_connect_errno()){
    echo "Database connection failed: " . mysqli_connect_error();
  }
  
  //Check user credentials
  if(isset($_POST['username'])){
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    
    $sel_user = "select user_id, username, password from user where username='$username';";
    
    $run_user = mysqli_query($con, $sel_user);
    if (!$run_user) {
      die(mysqli_error($con));
    }
    
    $row = mysqli_fetch_array($run_user);
    $user_id = $row[0];
    $username = $row[1];
    $hash = $row[2];
    
    if(password_verify($password, $hash)){
      $_SESSION['user_id'] = $user_id;
      $_SESSION['username'] = $username;
      //set permissions
      //temp permissions
      $_SESSION['perm_post_news'] = 1;
      $_SESSION['perm_delete_sub'] = 1;
      header("location:sub_list.php");
    } else {
      echo("Invalid Username or Password");
    }
    
  } else {
    echo("No credentials supplied.");
  }
  
  mysqli_close($con);

?>