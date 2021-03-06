<?php
  session_start();
  
  $con = mysqli_connect("localhost", "auscaledb", "124578", "ksb");
  
  if(isset($_SESSION['perm_post_news'])){
    $perm_post_news = $_SESSION['perm_post_news'];
  } else {
    $perm_post_news = 0;
  }
  
  
?>
<!doctype html>
<html>
  <head>
    <!--
    KSB
    Project started 1/12/14
    
    Notes:
    This page shows as if you were logged in, for demonstration purposes.
    -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSB News</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
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
              <a href="view_user.php?id=' . $user_id . '">My Profile</a>
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
        <li class="tabs tabs_left tabs_page"><a href="submit.php">Submit</a></li>
        <li class="tabs tabs_left tabs_page"><a href="forum.php">Forum</a></li>
        <li class="tabs tabs_left tabs_page" id="tab_active"><a href="news.php">News</a></li>
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
      <a href="post_news.php">Post News</a>
      <?php
        $query = "select n.head, n.body, n.create_by, u.username, n.create_date, n.news_id from news n join user u on u.user_id = n.create_by order by n.create_date desc;";
        $result = mysqli_query($con, $query);
        if(!$result){
          die(mysqli_error($con));
        }
        if (mysqli_num_rows($result) != 0){
          while ($row = mysqli_fetch_array($result)){
            echo('
              <div class="news_item">
                <div class="news_header">
                  <span>' . $row[0] . '</span>
                  <span class="news_details">Posted by: <a class="header_link" href="view_user.php?id=' . $row[2] . '">' . $row[3] . '</a>');
                  if($perm_post_news === 1){
                    echo('<a class="header_link" href="scripts/post_news_script.php?m=delete&news_id=' . $row[5] . '">Delete</a></span>');
                  }
                  echo('</span><span class="news_date news_details">' . $row[4] . '</span>
                </div>
                <div class="news_body">
                  <span>' . $row[1] . '</span>
                </div>
              </div>
            ');
          }
        }
      ?>
        
      </div>
    </div>
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/ksb.js"></script>
  </body>
</html>
<?php
  mysqli_close($con);
  unset($_SESSION['message']);
?>