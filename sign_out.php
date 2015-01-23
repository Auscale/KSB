<?php
  session_start();
  //destory session
  session_unset();
  session_destroy();
  header("location:sub_list.php")
?>