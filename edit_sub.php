<?php
  
  session_start();
  
  if(!isset($_SESSION['user_id'])){
    $_SESSION['error_msg'] = 'You must be logged in to edit a submission!';
    header('location:sign_in.php');
  }
  
  $con = mysqli_connect("localhost", "auscaledb", "124578", "ksb");
  
  if(!isset($_GET['sub'])){
    header('location:sub_list.php');
  } else {
    $sub_id = mysqli_real_escape_string($con, $_GET['sub']);
  }
?>

<!doctype html>
<html>
  <head>
    <!--
    KSB
    Project started 1/12/14
    -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSB - Edit Post</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <script src="ckeditor/ckeditor.js"></script>
  </head>
  <body class="preload">
    <div id="wrapper">
      <ul id="navbar_small">
        <li>
          <a id="menu_link" href="javascript:void(0)">
            <i id="menu_icon" class="fa fa-bars fa-lg navbar_left"></i>
          </a>
        </li>
        <li>
          <a id="user_link" href="javascript:void(0)">
            <i id="user_icon" class="fa fa-user fa-lg navbar_right"></i>
          </a>
        </li>
      </ul>
      <ul id="navbar_menu">
        <li><a href="sub_list.php">Posts</a></li>
        <li><a href="submit.php">Submit</a></li>
        <li><a href="forum.php">Forum</a></li>
        <li><a href="news.php">News</a></li>
      </ul>
      <ul id="navbar_menu">
        <li><a href="sub_list.php">Posts</a></li>
        <li><a href="submit.php">Submit</a></li>
        <li><a href="forum.php">Forum</a></li>
        <li><a href="news.php">News</a></li>
      </ul>
      <div id="navbar_search">
        <form>
          <input type="text" id="navbar_search_box" placeholder="Search...">
        </form>
      </div>
      <div id="navbar_user">
      
        <?php
          if(isset($_SESSION['user_id'])){
            $user_id = $_SESSION['user_id'];
            echo('
            <div id="nav_user_links">
              <a href="view_profile.php?user=' . $user_id . '">My Profile</a>
              <a href="favorite_list.php?user=' . $user_id . '">My Favorites</a>
              <a href="upload_list.php?user=' . $user_id . '">My Uploads</a>
              <a href="mod_cp.php">Mod CP</a>
              <a href="admin_cp.php">Admin CP</a>
              <a href="scripts/sign_out.php">Sign Out</a>
            </div>');
          } else {
            echo('
            <form id="nav_login_form" method="post" action="scripts/sign_in_script.php">
              <input class="nav_form_text" type="text" name="username" placeholder="Username">
              <input class="nav_form_text" type="password" name="password" placeholder="Password">
              <div id="form_buttons">
                <input type="submit" class="nav_button" name="login" value="Sign In">
                <a href="register.php" id="nav_register_link">Register</a>
              </div>
            </form>');
          }
        ?>
        
      </div>
      <ul id="tab_container">
        <li class="tabs tabs_left tabs_page" id="tab_active"><a href="sub_list.php">Posts</a></li>
        <li class="tabs tabs_left tabs_page"><a href="submit.php">Submit</a></li>
        <li class="tabs tabs_left tabs_page"><a href="forum.php">Forum</a></li>
        <li class="tabs tabs_left tabs_page"><a href="news.php">News</a></li>
        <li class="tabs tabs_right tabs_drop">
        <?php
          if(isset($_SESSION['username'])){
            echo('<a id="user_tab" href="javascript:void(0)">' . $_SESSION['username'] . '</a>');
          } else {
            echo('<a id="sign_in_tab" href="javascript:void(0)">Sign In</a>');
          }
        ?></li>
      </ul>
      <div id="page">
        <?php
          //get current sub info from database
          $query = "select title, description, rating, source, type, filename, extention from sub where sub_id='$sub_id';";
          $result = mysqli_query($con, $query);
          if(!$result){
            die(mysqli_error($con));
          } else {
            $row = mysqli_fetch_array($result);
            $title = $row[0];
            $desc = $row[1];
            $rating = $row[2];
            $source = $row[3];
            $type = $row[4];
            $file_name = $row[5];
            $extention = $row[6];
          }
          //get current tags
          $query = "select t.name from sub_tag st join tag t on t.tag_id = st.tag_id where st.sub_id='$sub_id'";
          $result = mysqli_query($con, $query);
          if(!$result){
            die(mysqli_error($con));
          } else {
            $tag_string = '';
            //loop through results
            while ($row = mysqli_fetch_array($result)){
              $tag_string .= $row[0] . " ";
            }
            $tag_string = trim($tag_string);
          }
          //if submission is a story, allow editing.
          if($type == 0){
            $story_content = file_get_contents('uploads/' . $file_name . '.' . $extention);
          }
        ?>
        <div id="form_wrap">
          <div class="thumb_box">
            <a <?php echo('href="uploads/' . $file_name . '.' . $extention . '" target="_blank"'); ?>>
              <img <?php echo('src="uploads/lr/' . $file_name . '.jpg" alt="' . $title . '" class="img_const"'); ?>>
            </a>
          </div>
          <form id="form_submit" method="post" action="scripts/edit_script.php">
            <?php
              if($type==0){
                echo('<textarea id="sub_form_story_paste_ta" class="sub_form_textarea ckeditor" id="story_upload" name="story_upload">' . $story_content . '</textarea>');
              }
            ?>
            <label for="sub_tags">Tags</label>
            <textarea class="sub_form_textarea" id="sub_tags" name="sub_tags"><?php echo($tag_string); ?></textarea>
            <span class="sub_form_label">Rating</span>
            <input type="radio" class="sub_form_radio" name="rating" value="0" <?php if($rating==0){echo('checked');} ?>>S
            <input type="radio" class="sub_form_radio" name="rating" value="1" <?php if($rating==1){echo('checked');} ?>>M
            <input type="radio" class="sub_form_radio" name="rating" value="2" <?php if($rating==2){echo('checked');} ?>>E
            <label for="sub_source">Source</label>
            <input type="text" name="sub_source" id="sub_source" class="sub_form_text" value=<?php echo('"' . $source . '"'); ?>>
            <label for="sub_title">Title</label>
            <input type="text" class="sub_form_text" id="sub_title" value=<?php echo('"' . $title . '"'); ?> name="sub_title">
            <label for="sub_description">Description</label>
            <textarea class="sub_form_textarea" id="sub_description" name="sub_description"><?php echo($desc); ?></textarea>
            <input type="hidden" name="sub_id" value=<?php echo('"' . $sub_id . '"'); ?>>
            <input type="submit" class="reg_submit" value="Update">
          </form>
        </div>
      </div>
    </div>
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/ksb.js"></script>
  </body>
</html>
<?php
  mysqli_close($con);
?>