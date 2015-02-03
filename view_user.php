<?php
  session_start();
  
  if(!isset($_GET['id'])){
    header('location:sub_list.php');
  }
  $con = mysqli_connect("localhost", "auscaledb", "124578", "ksb");
  
  //get user info
  $view_user_id = mysqli_real_escape_string($con, $_GET['id']);
  $query = "select u.username, u.create_date, s.filename, u.profile_header_offset from user u join sub s on s.sub_id = u.profile_header_sub_id where user_id='$view_user_id';";
  $result = mysqli_query($con, $query);
  if (!$result) {
    die(mysqli_error($con));
  }
  
  $row = mysqli_fetch_array($result);
  $view_username = $row[0];
  $view_user_date = $row[1];
  $profile_header_sub_filename = $row[2];
  if($row[3] == ''){
    $profile_header_offset = 0;
  } else {
    $profile_header_offset = $row[3];
  }
  
?>
<!doctype html>
<html>
  <head>
    <!--
    KSB
    Project started 1/12/14
    
    Notes:
      Nested comments are to be placed within the comment_parent class AS comment_parent.
      Add a way to edit tags from here, only for registered users.
    -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSB
    <?php
      echo(" - $view_username's Profile");
    ?>
    </title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
  </head>
  <body class="preload">
    <div id="wrapper">
      <ul id="navbar_small">
        <li>
          <a id="menu_link" href="javascript:void(0)">
            <i class="fa fa-bars fa-lg navbar_left"></i>
          </a>
        </li>
        <li>
          <a id="search_link" href="javascript:void(0)">
            <i id="search_icon" class="fa fa-search navbar_left"></i>
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
      <div id="navbar_search">
        <form>
          <input type="text" id="navbar_search_box" placeholder="Search...">
        </form>
      </div>
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
              <a href="view_user.php?id=' . $user_id . '">My Profile</a>
              <a href="sub_list.php?m=fav&user=' . $user_id . '">My Favorites</a>
              <a href="sub_list.php?m=upl&user=' . $user_id . '">My Uploads</a>
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
      <div id="profile_wrap">
        <div class="profile_header"
          <?php
            if($profile_header_sub_filename != ''){
              echo('style="background-image:url(\'uploads/lr/' . $profile_header_sub_filename . '.jpg\');background-position-y:-' . $profile_header_offset . 'px !important;"');
            }
          ?>
        >
          <div class="profile_namebox">
            <span class="profile_username"><?php echo($view_username); ?></span>
          </div>
        </div>
        <div class="profile_navbar">
          <ul>
            <li class="navbar_left navbar_glow"><a href="#">Message</a></li>
            <li class="navbar_left navbar_glow"><a href="#">Message</a></li>
            <?php
              //only show edit profile link if logged in as this user, or have permission
              if($_SESSION['user_id'] === $view_user_id){
                echo('<li class="navbar_right navbar_glow"><a href="edit_user.php">Edit Profile</a></li>');
              }
            ?>
          </ul>
        </div>
        <div class="profile_content">
          <div class="profile_sidebar">
            <span class="sidebar_title">User Stats</span>
            <table class="image_details">
              <tr><td class="image_details_attribute">Uploads:</td><td>102</td></tr>
              <tr><td class="image_details_attribute">Favorites:</td><td>901</td></tr>
              <tr><td class="image_details_attribute">Member Since:</td><td>June 14th 2001</td></tr>
            </table>
          </div>
          <div class="profile_main">
            Main
          </div>
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