<?php
  
  //size in pixels for the low-res version of the image
  $lr_size = 1000;
  $max_size = 20000000;
  session_start();
  $con = mysqli_connect("localhost", "auscaledb", "124578", "ksb");
  
  if(!isset($_SESSION['user_id'])){
    echo('You must be logged in to upload.');
  } else {
    if(isset($_POST['sub_type']) && isset($_POST['sub_title'])){
      if($_POST['sub_type'] == 'image'){
        if($_POST['online_link']){
          //requires allow_url_fopen set to true
          $uploadOk = 1;
          $file_name = uniqid();
          $url = $_POST['online_link'];
          $got_image = file_get_contents($url);
          if(!$got_image){
            die("Unable to fetch that URL.");
          }
          $filesize = strlen($got_image);
          $image_extention = exif_imagetype($url);
          $image_extention = image_type_to_extension($image_extention, false);
          $full_name = "uploads/" . $file_name . '.' . $image_extention;
          //check to make sure not duplicate image
          $file_hash = sha1_file($url);
          $query = 'select sub_id from sub where hash="' . $file_hash . '";';
          $result = mysqli_query($con,$query);
          if(!$result){
            die(mysqli_error($con));
          }
          $row = mysqli_fetch_array($result);
          $sub_id = $row[0];
          if(mysqli_num_rows($query_result) > 0){
            echo("Hmm. I think that file already exists. Click <a href='view_sub.php?sub=$sub_id'>here</a> to view it.");
            die();
          }
          //check image size to verify it's an image
          $image_resolution = getimagesize($url);
          if($image_resolution == 0){
            echo("Whoops. That doesn't look like an image to me... Maybe it's corrupt? Or maybe you're just being silly. Either way, something went wrong.");
            die();
          }
          //if file is too big...
          if($filesize > $max_size){
            echo("Sorry, your file is too large. Current max size is $max_size bytes.");
            die();
          }
          file_put_contents($full_name, $got_image);
        } else {
          $target_dir = "uploads/";
          $sub_title = mysqli_real_escape_string($con, $_POST['sub_title']);
          $sub_description = mysqli_real_escape_string($con, $_POST['sub_description']);
          if($_POST['rating'] == 0 || $_POST['rating'] == 1 || $_POST['rating'] == 2){
            $sub_rating = mysqli_real_escape_string($con, $_POST['rating']); 
          } else {
            echo('Hacking attempt logged...');
          }
          $uploadOk = 1;
          $image_extention = exif_imagetype($_FILES['file_upload']['tmp_name']);
          $image_extention = image_type_to_extension($image_extention, false);
          $file_name = uniqid();
          $new_name = $file_name . '.' . $image_extention;
          $full_name = $target_dir . $new_name;
          // Check if file already exists by calculating the hash of the file and looking in our database.
          $file_hash = sha1_file($_FILES['file_upload']['tmp_name']);
          $query = 'select sub_id from sub where hash="' . $file_hash . '";';
          $query_result = mysqli_query($con,$query);
          if(!$query_result){
            die(mysqli_error($con));
          }
          $row = mysqli_fetch_array($query_result);
          $sub_id = $row[0];
          //check resolution of the image.
          $image_resolution = getimagesize($_FILES['file_upload']['tmp_name']);
          if($image_resolution == 0){
            $uploadOk = 0;
            echo("Whoops. That doesn't look like an image to me... Maybe it's corrupt? Or maybe you're just being silly. Either way, something went wrong.");
            die();
          }
          if(mysqli_num_rows($query_result) > 0){
            echo("Hmm. I think that file already exists. Click <a href='view_sub.php?sub=$sub_id'>here</a> to view it.");
            die();
          }
          // Check file size
          if ($_FILES["file_upload"]["size"] > $max_size) {
            echo "Sorry, your file is too large. Current max size is $max_size bytes.";
            die();
          }
          // Check if $uploadOk is 1, and upload it
          if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["file_upload"]["tmp_name"], $full_name)) {
              //uploaded.
              echo "The file ". basename( $_FILES["file_upload"]["name"]). " has been uploaded.";
            }
          }
        }
        //create sql entry
        $sub_title = mysqli_real_escape_string($con,$_POST['sub_title']);
        $sub_desc = mysqli_real_escape_string($con,$_POST['sub_description']);
        $sub_rating = mysqli_real_escape_string($con,$_POST['rating']);
        $source = mysqli_real_escape_string($con, $_POST['sub_source']);
        $user_id = $_SESSION['user_id'];
        //insert new data
        $mysql_time = date("Y-m-d H:i:s", time());
        if($image_resolution[0] > $lr_size || $image_resolution[1] > $lr_size){
          $lr_exists = 1;
        } else {
          $lr_exists = 0;
        }
        $query = "insert into sub (title, type, description, rating, hash, filename, extention, source, create_date, create_by, resolution_w, resolution_h, upload_method, lr_exists) values ('" . $sub_title . "',1,'" . $sub_desc . "','" . $sub_rating . "','" . $file_hash . "','" . $file_name . "','" . $image_extention .  "','" . $source . "','" . $mysql_time . "','" . $user_id . "','" . $image_resolution[0] . "','" . $image_resolution[1] . "','file','" . $lr_exists . "');";
        //run query
        $query_result = mysqli_query($con, $query);
        if (!$query_result) {
          die(mysqli_error($con));
        }
        //get id for later
        $sub_id = mysqli_insert_id($con);
        //parse tags
        if(isset($_POST['sub_tags'])){
          $tag_string = mysqli_real_escape_string($con, $_POST['sub_tags']);
          if($tag_string === ''){
            $tag_string = "tag_me";
          }
          $tag_array = explode(" ", $tag_string);
        } else {
          header("location:sub_list.php");
          die();
        }
        //remove duplicate tags
        $tag_array = array_keys(array_flip($tag_array));
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
            if($count === 0){
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
        
        function create_thumb($max_output_size, $original_name, $new_thumb_name){
          //create thumbnail and small-res version
          list($width, $height, $image_type) = getimagesize($original_name);
          
          if($image_type == 2){
            $img = imagecreatefromjpeg($original_name);
          } else if($image_type == 1){
            $img = imagecreatefromgif($original_name);
          } else if($image_type == 3){
            $img = imagecreatefrompng($original_name);
          } else {
            echo("failed to find image type ");
            return false;
          }
          if ($img === false) {
            echo("failed to create img ");
            return false;
          }
          $aspect_ratio = $width / $height;
          if ($width <= $max_output_size && $height <= $max_output_size){
            $output_width = $max_output_size;
            $output_height = $max_output_size;
          } else if($aspect_ratio < 1) {
            $output_width = $max_output_size * $aspect_ratio;
            $output_height = $max_output_size;
          } else {
            $output_width = $max_output_size;
            $output_height = $max_output_size / $aspect_ratio;
          }
          
          $tmp_img = imagecreatetruecolor($output_width, $output_height);
          
          imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $output_width, $output_height, $width, $height);
          imagepng($tmp_img, $new_thumb_name . ".png");
          imagedestroy($tmp_img);
          imagedestroy($img);
          
          return true;
        }
        //create thumbnail
        $result = create_thumb(120, $full_name, "uploads/thumbs/" . $file_name);
        if(!$result){
          echo("Failed to create thumbnail...");
        } else {
          //create low res only if lr exists
          if($lr_exists === 1){
            $result = create_thumb($lr_size, $full_name, "uploads/lr/" . $file_name);
          }
          if(!$result){
            echo("Failed to create low-res version...");
            die();
          } else {
            //redirect to their shiny new upload
            header('location:view_sub.php?sub=' . $sub_id);
          }
        }
      }else if($_POST['sub_type'] == 'story'){
        $story_content = mysqli_real_escape_string($con, $_POST['story_upload']);
        //Could potentially strip ckeditor formatting and do a hash check for duplicates, but it seems a little unlikely that it'd work. For now, just upload without checking. Let admins/mods deal with duplicate stories.
        $sub_title = mysqli_real_escape_string($con,$_POST['sub_title']);
        if($sub_title == ""){
          $_SESSION['message'] = "You must specify a title for a story.";
          header("location:submit.php");
          echo("Something went wrong");
          die();
        }
        $sub_desc = mysqli_real_escape_string($con,$_POST['sub_description']);
        $sub_rating = mysqli_real_escape_string($con,$_POST['rating']);
        $source = mysqli_real_escape_string($con, $_POST['sub_source']);
        $file_name = uniqid();
        $text_extention = 'txt';
        $user_id = $_SESSION['user_id'];
        //insert new data
        $mysql_time = date("Y-m-d H:i:s", time());
        $query = "insert into sub (title, type, description, rating, filename, extention, source, create_date, create_by, upload_method) values ('" . $sub_title . "',0,'" . $sub_desc . "','" . $sub_rating . "','" . $file_name . "','" . $text_extention .  "','" . $source . "','" . $mysql_time . "','" . $user_id . "','CKE');";
        $result = mysqli_query($con, $query);
        if(!$result){
          die(mysqli_error($con));
        } else {
          //create file on server
          file_put_contents("uploads/" . $file_name . '.' . $text_extention, $story_content);
          $sub_id = mysqli_insert_id($con);
          //create and insert tags
          //parse tags
          $tag_string = mysqli_real_escape_string($con, $_POST['sub_tags']);
          if($tag_string == ''){
            $tag_string = "tag_me";
          }
          $tag_array = explode(" ", $tag_string);
          //check if tags exist, if not, add them.
          foreach($tag_array as $value){
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
          unset($value);
          header("location:view_sub.php?sub=$sub_id");
        }
      }
    }
  }
  
  mysqli_close($con);
  
?>