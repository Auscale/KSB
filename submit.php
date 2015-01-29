<?php
  
  session_start();
  
  if(!isset($_SESSION['user_id'])){
    $_SESSION['message'] = 'You must be logged in to submit something!';
    header('location:sign_in.php');
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
    <title>KSB Submit Post</title>
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
        <li class="tabs tabs_left tabs_page"><a href="sub_list.php">Posts</a></li>
        <li class="tabs tabs_left tabs_page" id="tab_active"><a href="submit.php">Submit</a></li>
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
        <div id="form_wrap">
          <form id="form_submit" method="post" action="scripts/sub_upload.php" enctype="multipart/form-data">
            <span class="sub_form_label">Submission Type</span>
            <input id="sub_type_image" type="radio" class="sub_form_radio" name="sub_type" value="image" checked>
            <span class="sub_form_desc">Image/Flash</span>
            <input id="sub_type_story" type="radio" class="sub_form_radio" name="sub_type" value="story">
            <span class="sub_form_desc">Story</span>
            <span id="sub_form_image_upload_label" class="sub_form_label">Choose a file or paste a URL</span>
            <input id="sub_form_image_upload_file" type="file" class="sub_form_file" name="file_upload" accept="image/*, .swf">
            <input id="sub_form_image_upload_url" type="text" class="sub_form_text" name="online_link" placeholder="OR Paste URL...">
            <div id="ckeditor_container">
              <label id="sub_form_story_paste_label" for="story_upload">Paste text</label>
              <textarea id="sub_form_story_paste_ta" class="sub_form_textarea ckeditor" name="story_upload"></textarea>
            </div>
            <label for="sub_tags">Tags</label>
            <textarea class="sub_form_textarea" id="sub_tags" name="sub_tags"></textarea>
            <span class="sub_form_label">Rating</span>
            <input type="radio" class="sub_form_radio" name="rating" value="0" checked>S
            <input type="radio" class="sub_form_radio" name="rating" value="1">M
            <input type="radio" class="sub_form_radio" name="rating" value="2">E
            <label for="sub_source">Source</label>
            <input type="text" class="sub_form_text" name="sub_source" id="sub_source">
            <label for="sub_title">Title</label>
            <input type="text" class="sub_form_text" id="sub_title" name="sub_title">
            <label for="sub_description">Description</label>
            <textarea class="sub_form_textarea" id="sub_description" name="sub_description"></textarea>
            <input type="submit" class="reg_submit" value="Upload">
            <?php
            if(isset($_SESSION['message'])){
              echo("<span class='error_message'>");
              echo($_SESSION['message']);
              echo("</span>");
              unset($_SESSION['message']);
            }
            ?>
          </form>
        </div>
      </div>
    </div>
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/ksb.js"></script>
  </body>
</html>