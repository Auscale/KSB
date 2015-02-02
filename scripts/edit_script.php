<?php
  
  session_start();
  
  $con = mysqli_connect("localhost", "auscaledb", "124578", "ksb");
  
  if(!isset($_SESSION['user_id']) || !isset($_POST['sub_id'])){
    header('location:/sub_list.php');
  }
  
  if(isset($_POST['story_upload'])){
    $story_content = mysqli_real_escape_string($con, $_POST['story_upload']);
  } else {
    //makes it possible to submit an edit with no story in the textarea. Probably not ideal, but it prevents an error.
    $story_content = '';
  }
  
  $sub_id     = mysqli_real_escape_string($con, $_POST['sub_id']);
  $title      = mysqli_real_escape_string($con, $_POST['sub_title']);
  $desc       = mysqli_real_escape_string($con, $_POST['sub_description']);
  $rating     = mysqli_real_escape_string($con, $_POST['rating']);
  $tag_string = mysqli_real_escape_string($con, $_POST['sub_tags']);
  $source     = mysqli_real_escape_string($con, $_POST['sub_source']);
  $tag_string = strtolower(trim($tag_string));
  $user_id    = $_SESSION['user_id'];
  $mysql_time = date("Y-m-d H:i:s", time());
  
  //if story, update txt file
  $query = "select filename, extention, type, title, description, rating, source from sub where sub_id='$sub_id';";
  $result = mysqli_query($con, $query);
  if(!$result){
    die(mysqli_error($con));
  } else {
    $row = mysqli_fetch_row($result);
    $file_name  = $row[0];
    $extention  = $row[1];
    $type       = $row[2];
    $old_title  = $row[3];
    $old_desc   = $row[4];
    $old_rating = $row[5];
    $old_source = $row[6];
    if($type == 0){
      //copy old text file to backup folder.
      //generate new filename
      $new_file_name = uniqid();
      //create new file
      file_put_contents("uploads/" . $new_file_name . '.' . $extention, $story_content);
    } else {
      $new_file_name = $file_name;
    }
  }
  //add audit table copy here.
  //build tag string from current tags
  $query = "select t.name from tag t join sub_tag st on st.tag_id = t.tag_id where st.sub_id='$sub_id';";
  $result = ,mysqli_query($con, $query);
  if(!$result){
    die(mysqli_error($con));
  }
  while($row = mysqli_fetch_array($result)){
    $tag_string = "whatever";
  }
  //remember in this context, create_by is the person who did the edit that replaced this version.
  $query = "insert into sub_audit (sub_id, title, description, rating, source, filename, tags, create_date, create_by) values ('$sub_id','$old_title','$old_desc','$old_rating','$old_source','$file_name','$tag_string','$mysql_date','$user_id');";
  //update submission table
  $query = "update sub set title='$title', description='$desc', rating='$rating', source='$source', filename='$new_file_name' where sub_id='$sub_id';";
  $result = mysqli_query($con, $query);
  if(!$result){
    die(mysqli_error($con));
  } else {
    //remove all tags for this sub
    $query = "delete from sub_tag where sub_id='$sub_id';";
    $result = mysqli_query($con, $query);
    if(!$result){
      die(mysqli_error($con));
    } else {
      //then delete any tags that no longer have a sub attached
      $query = "delete from tag where tag_id not in (select distinct tag_id from sub_tag);";
      $result = mysqli_query($con, $query);
      if(!$result){
        die(mysqli_error($con));
      } else {
        //update tags
        if($tag_string != ''){
          $tag_array = explode(" ", $tag_string);
          //check if tags exist, if not, add them.
          foreach($tag_array as $value){
            //skip tags with silly characters like : and -
            if(strpos($value, ':') === false && strpos($value, '-') === false){
              $query = "select tag_id from tag where name='$value';";
              $result = mysqli_query($con, $query);
              if(!$result){
                die(mysqli_error($con));
              }
              $count = mysqli_num_rows($result);
              if($count != 1){
                //don't exist, add it.
                $query = "insert into tag (name, type) values ('$value',0)";
                $result = mysqli_query($con, $query);
                if(!$result){
                  die(mysqli_error($con));
                }
                $tag_id = mysqli_insert_id($con);
              } else {
                //get id of tag
                $row = mysqli_fetch_array($result);
                $tag_id = $row[0];
              }
              //record tag for this sub
              $query = "insert into sub_tag (sub_id, tag_id, create_date, create_by) values ('$sub_id', '$tag_id', '$mysql_time', '$user_id');";
              $result = mysqli_query($con, $query);
              if(!$result){
                die(mysqli_error($con));
              }
            }
          }
          unset($value);
        }
      }
    }
  }
  
  mysqli_close($con);
  
  header('location:/view_sub.php?sub=' . $sub_id);
  //todo: add audit table to track changes
?>