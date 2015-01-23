<?php
  session_start();
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
    <title>KSB Forum</title>
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
              <a href="view_profile.php?user=' . $user_id . '">My Profile</a>
              <a href="favorite_list.php?user=' . $user_id . '">My Favorites</a>
              <a href="upload_list.php?user=' . $user_id . '">My Uploads</a>
              <a href="mod_cp.php">Mod CP</a>
              <a href="admin_cp.php">Admin CP</a>
              <a href="sign_out.php">Sign Out</a>
            </div>');
          } else {
            echo('
            <form id="nav_login_form" method="post" action="sign_in_script.php">
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
        <li class="tabs tabs_left tabs_page" id="tab_active"><a href="forum.php">Forum</a></li>
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
        <table class="forum_categories">
          <tbody class="forum_category_header">
            <tr>
              <td></td>
              <td colspan="3">
                Forum Category
                <a class="forum_category_collapse" id="fcl_1" href="javascript:void(0)"><i class="fa fa-minus-square"></i></a>
              </td>
            </tr>
          </tbody>
          <tbody class="forum_category_content" id="fc_1">
            <tr>
              <td class="forum_forum_icon"><i class="fa fa-file-text"></i></td>
              <td><a href="view_forum.php">Forum Title</a></td>
              <td class="forum_forum_stats"><span>24 Posts in</span><span>12 Topics</span></td>
              <td class="forum_forum_recent">
                <span>Last post by <a href="view_user.php">Someguy</a></span>
                <span>in <a href="view_topic.php">Topic</a></span>
                <span>on 15/12/14</a></span>
              </td>
            </tr>
            <tr>
              <td class="forum_forum_icon"><i class="fa fa-file-text"></i></td>
              <td><a href="view_forum.php">Forum Title</a></td>
              <td class="forum_forum_stats"><span>24 Posts in</span><span>12 Topics</span></td>
              <td class="forum_forum_recent">
                <span>Last post by <a href="view_user.php">Someguy</a></span>
                <span>in <a href="view_topic.php">Topic</a></span>
                <span>on 15/12/14</a></span>
              </td>
            </tr>
            <tr>
              <td class="forum_forum_icon"><i class="fa fa-file-text-o"></i></td>
              <td><a href="view_forum.php">Forum Title</a></td>
              <td class="forum_forum_stats"><span>24 Posts in</span><span>12 Topics</span></td>
              <td class="forum_forum_recent">
                <span>Last post by <a href="view_user.php">Someguy</a></span>
                <span>in <a href="view_topic.php">Topic</a></span>
                <span>on 15/12/14</a></span>
              </td>
            </tr>
            <tr>
              <td class="forum_forum_icon"><i class="fa fa-file-text-o"></i></td>
              <td><a href="view_forum.php">Forum Title</a></td>
              <td class="forum_forum_stats"><span>24 Posts in</span><span>12 Topics</span></td>
              <td class="forum_forum_recent">
                <span>Last post by <a href="view_user.php">Someguy</a></span>
                <span>in <a href="view_topic.php">Topic</a></span>
                <span>on 15/12/14</a></span>
              </td>
            </tr>
          </tbody>
          <tbody class="forum_category_divider">
            <tr>
              <td colspan="4"></td>
            </tr>
          </tbody>
          <tbody class="forum_category_header">
            <tr>
              <td colspan="4">
                Forum Category
                <a class="forum_category_collapse" id="fcl_2" href="javascript:void(0)"><i class="fa fa-minus-square"></i></a>
              </td>
            </tr>
          </tbody>
          <tbody class="forum_category_content" id="fc_2">
            <tr>
              <td class="forum_forum_icon"><i class="fa fa-file-text"></i></td>
              <td><a href="view_forum.php">Forum Title</a></td>
              <td class="forum_forum_stats"><span>24 Posts in</span><span>12 Topics</span></td>
              <td class="forum_forum_recent">
                <span>Last post by <a href="view_user.php">Someguy</a></span>
                <span>in <a href="view_topic.php">Topic</a></span>
                <span>on 15/12/14</a></span>
              </td>
            </tr>
          </tbody>
          <tbody class="forum_category_divider">
            <tr>
              <td colspan="4"></td>
            </tr>
          </tbody>
          <tbody class="forum_category_header">
            <tr>
              <td colspan="4">
                Forum Category
                <a class="forum_category_collapse" id="fcl_3" href="javascript:void(0)"><i class="fa fa-minus-square"></i></a>
              </td>
            </tr>
          </tbody>
          <tbody class="forum_category_content" id="fc_3">
            <tr>
              <td class="forum_forum_icon"><i class="fa fa-file-text-o"></i></td>
              <td><a href="view_forum.php">Forum Title</a></td>
              <td class="forum_forum_stats"><span>24 Posts in</span><span>12 Topics</span></td>
              <td class="forum_forum_recent">
                <span>Last post by <a href="view_user.php">Someguy</a></span>
                <span>in <a href="view_topic.php">Topic</a></span>
                <span>on 15/12/14</a></span>
              </td>
            </tr>
            <tr>
              <td class="forum_forum_icon"><i class="fa fa-file-text"></i></td>
              <td><a href="view_forum.php">Forum Title</a></td>
              <td class="forum_forum_stats"><span>24 Posts in</span><span>12 Topics</span></td>
              <td class="forum_forum_recent">
                <span>Last post by <a href="view_user.php">Someguy</a></span>
                <span>in <a href="view_topic.php">Topic</a></span>
                <span>on 15/12/14</a></span>
              </td>
            </tr>
            <tr>
              <td class="forum_forum_icon"><i class="fa fa-file-text"></i></td>
              <td><a href="view_forum.php">Forum Title</a></td>
              <td class="forum_forum_stats"><span>24 Posts in</span><span>12 Topics</span></td>
              <td class="forum_forum_recent">
                <span>Last post by <a href="view_user.php">Someguy</a></span>
                <span>in <a href="view_topic.php">Topic</a></span>
                <span>on 15/12/14</a></span>
              </td>
            </tr>
          </tbody>
          <tbody class="forum_category_divider">
            <tr>
              <td colspan="4"></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/ksb.js"></script>
  </body>
</html>