<?php
  session_start();
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
    <title>KSB</title>
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
        <table class="forum_forum">
          <tbody class="forum_forum_header">
            <tr>
              <td class="forum_header_author">Author</td>
              <td>Topic: Topic Title Goes Here!</td>
            </tr>
          </tbody>
          <tbody class="forum_forum_content">
            <tr>
              <td class="forum_user_info">
                <img class="forum_user_avatar" src="images/120x120.gif">
                <span><a href="view_user.php">Someguy</a></span>
                <span>Administrator</span>
                <span>Posts: 1024</span>
                <div class="forum_post_vote">
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                </div>
                <span class="forum_post_positive">+4</span>
              </td>
              <td>
                <div class="forum_post_header">
                  <a class="forum_tools_small" href="javascript:void(0)">Tools...</a>
                  <ul>
                    <li><a href="#"><i class="fa fa-quote-left forum_post_header_tools"></i>Quote</a></li>
                    <li><a href="#"><i class="fa fa-pencil-square-o forum_post_header_tools"></i>Modify</a></li>
                    <li><a href="#"><i class="fa fa-close forum_post_header_tools"></i>Remove</a></li>
                    <li><a href="#"><i class="fa fa-code-fork forum_post_header_tools"></i>Split Topic</a></li>
                  </ul>
                </div>
                <div class="forum_post_body">
                  <span>Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah.</span>
                </div>
                <div class="forum_post_footer">
                  <div class="forum_post_edits">
                    <span><a href="#">Last Edit:</a> December 17th, 2014, 15:15 by <a href="#">Administrator</a></span>
                  </div>
                  <div class="forum_post_tools">
                    
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td class="forum_user_info">
                <img class="forum_user_avatar" src="images/120x120.gif">
                <span>Someguy</span>
                <span>Administrator</span>
                <span>Posts: 1024</span>
                <div class="forum_post_vote">
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                </div>
                <span class="forum_post_negative">-4</span>
              </td>
              <td>
                <div class="forum_post_header">
                  <a class="forum_tools_small" href="javascript:void(0)">Tools...</a>
                  <ul>
                    <li><a href="#"><i class="fa fa-quote-left forum_post_header_tools"></i>Quote</a></li>
                    <li><a href="#"><i class="fa fa-pencil-square-o forum_post_header_tools"></i>Modify</a></li>
                    <li><a href="#"><i class="fa fa-close forum_post_header_tools"></i>Remove</a></li>
                    <li><a href="#"><i class="fa fa-code-fork forum_post_header_tools"></i>Split Topic</a></li>
                  </ul>
                </div>
                <div class="forum_post_body">
                  <span>Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah.</span>
                </div>
                <div class="forum_post_footer">
                  <div class="forum_post_edits">
                    <span><a href="#">Last Edit:</a> December 17th, 2014, 15:15 by <a href="#">Administrator</a></span>
                  </div>
                  <div class="forum_post_tools">
                    
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td class="forum_user_info">
                <img class="forum_user_avatar" src="images/120x120.gif">
                <span>Someguy</span>
                <span>Administrator</span>
                <span>Posts: 1024</span>
                <div class="forum_post_vote">
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                </div>
              </td>
              <td>
                <div class="forum_post_header">
                  <a class="forum_tools_small" href="javascript:void(0)">Tools...</a>
                  <ul>
                    <li><a href="#"><i class="fa fa-quote-left forum_post_header_tools"></i>Quote</a></li>
                    <li><a href="#"><i class="fa fa-pencil-square-o forum_post_header_tools"></i>Modify</a></li>
                    <li><a href="#"><i class="fa fa-close forum_post_header_tools"></i>Remove</a></li>
                    <li><a href="#"><i class="fa fa-code-fork forum_post_header_tools"></i>Split Topic</a></li>
                  </ul>
                </div>
                <div class="forum_post_body">
                  <span>Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah.</span>
                </div>
                <div class="forum_post_footer">
                  <div class="forum_post_edits">
                    <span><a href="#">Last Edit:</a> December 17th, 2014, 15:15 by <a href="#">Administrator</a></span>
                  </div>
                  <div class="forum_post_tools">
                    
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td class="forum_user_info">
                <img class="forum_user_avatar" src="images/120x120.gif">
                <span>Someguy</span>
                <span>Administrator</span>
                <span>Posts: 1024</span>
                <div class="forum_post_vote">
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                </div>
              </td>
              <td>
                <div class="forum_post_header">
                  <a class="forum_tools_small" href="javascript:void(0)">Tools...</a>
                  <ul>
                    <li><a href="#"><i class="fa fa-quote-left forum_post_header_tools"></i>Quote</a></li>
                    <li><a href="#"><i class="fa fa-pencil-square-o forum_post_header_tools"></i>Modify</a></li>
                    <li><a href="#"><i class="fa fa-close forum_post_header_tools"></i>Remove</a></li>
                    <li><a href="#"><i class="fa fa-code-fork forum_post_header_tools"></i>Split Topic</a></li>
                  </ul>
                </div>
                <div class="forum_post_body">
                  <span>Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah.</span>
                </div>
                <div class="forum_post_footer">
                  <div class="forum_post_edits">
                    <span><a href="#">Last Edit:</a> December 17th, 2014, 15:15 by <a href="#">Administrator</a></span>
                  </div>
                  <div class="forum_post_tools">
                    
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td class="forum_user_info">
                <img class="forum_user_avatar" src="images/120x120.gif">
                <span>Someguy</span>
                <span>Administrator</span>
                <span>Posts: 1024</span>
                <div class="forum_post_vote">
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                </div>
              </td>
              <td>
                <div class="forum_post_header">
                  <a class="forum_tools_small" href="javascript:void(0)">Tools...</a>
                  <ul>
                    <li><a href="#"><i class="fa fa-quote-left forum_post_header_tools"></i>Quote</a></li>
                    <li><a href="#"><i class="fa fa-pencil-square-o forum_post_header_tools"></i>Modify</a></li>
                    <li><a href="#"><i class="fa fa-close forum_post_header_tools"></i>Remove</a></li>
                    <li><a href="#"><i class="fa fa-code-fork forum_post_header_tools"></i>Split Topic</a></li>
                  </ul>
                </div>
                <div class="forum_post_body">
                  <span>Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah.</span>
                </div>
                <div class="forum_post_footer">
                  <div class="forum_post_edits">
                    <span><a href="#">Last Edit:</a> December 17th, 2014, 15:15 by <a href="#">Administrator</a></span>
                  </div>
                  <div class="forum_post_tools">
                    
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td class="forum_user_info">
                <img class="forum_user_avatar" src="images/120x120.gif">
                <span>Someguy</span>
                <span>Administrator</span>
                <span>Posts: 1024</span>
                <div class="forum_post_vote">
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                </div>
              </td>
              <td>
                <div class="forum_post_header">
                  <a class="forum_tools_small" href="javascript:void(0)">Tools...</a>
                  <ul>
                    <li><a href="#"><i class="fa fa-quote-left forum_post_header_tools"></i>Quote</a></li>
                    <li><a href="#"><i class="fa fa-pencil-square-o forum_post_header_tools"></i>Modify</a></li>
                    <li><a href="#"><i class="fa fa-close forum_post_header_tools"></i>Remove</a></li>
                    <li><a href="#"><i class="fa fa-code-fork forum_post_header_tools"></i>Split Topic</a></li>
                  </ul>
                </div>
                <div class="forum_post_body">
                  <span>Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah.</span>
                </div>
                <div class="forum_post_footer">
                  <div class="forum_post_edits">
                    <span><a href="#">Last Edit:</a> December 17th, 2014, 15:15 by <a href="#">Administrator</a></span>
                  </div>
                  <div class="forum_post_tools">
                    
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td class="forum_user_info">
                <img class="forum_user_avatar" src="images/120x120.gif">
                <span>Someguy</span>
                <span>Administrator</span>
                <span>Posts: 1024</span>
                <div class="forum_post_vote">
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                </div>
              </td>
              <td>
                <div class="forum_post_header">
                  <a class="forum_tools_small" href="javascript:void(0)">Tools...</a>
                  <ul>
                    <li><a href="#"><i class="fa fa-quote-left forum_post_header_tools"></i>Quote</a></li>
                    <li><a href="#"><i class="fa fa-pencil-square-o forum_post_header_tools"></i>Modify</a></li>
                    <li><a href="#"><i class="fa fa-close forum_post_header_tools"></i>Remove</a></li>
                    <li><a href="#"><i class="fa fa-code-fork forum_post_header_tools"></i>Split Topic</a></li>
                  </ul>
                </div>
                <div class="forum_post_body">
                  <span>Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah.</span>
                </div>
                <div class="forum_post_footer">
                  <div class="forum_post_edits">
                    <span><a href="#">Last Edit:</a> December 17th, 2014, 15:15 by <a href="#">Administrator</a></span>
                  </div>
                  <div class="forum_post_tools">
                    
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td class="forum_user_info">
                <img class="forum_user_avatar" src="images/120x120.gif">
                <span><a href="view_user.php">Someguy</a></span>
                <span>Administrator</span>
                <span>Posts: 1024</span>
                <div class="forum_post_vote">
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                </div>
              </td>
              <td>
                <div class="forum_post_header">
                  <a class="forum_tools_small" href="javascript:void(0)">Tools...</a>
                  <ul>
                    <li><a href="#"><i class="fa fa-quote-left forum_post_header_tools"></i>Quote</a></li>
                    <li><a href="#"><i class="fa fa-pencil-square-o forum_post_header_tools"></i>Modify</a></li>
                    <li><a href="#"><i class="fa fa-close forum_post_header_tools"></i>Remove</a></li>
                    <li><a href="#"><i class="fa fa-code-fork forum_post_header_tools"></i>Split Topic</a></li>
                  </ul>
                </div>
                <div class="forum_post_body">
                  <span>Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah. Post content goes here blah blah.</span>
                </div>
                <div class="forum_post_footer">
                  <div class="forum_post_edits">
                    <span><a href="#">Last Edit:</a> December 17th, 2014, 15:15 by <a href="#">Administrator</a></span>
                  </div>
                  <div class="forum_post_tools">
                    
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <div id="pag_cont">
          <ul class="pag">
            <li><a href="#">First</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#" id="pag_cur">4</a></li>
            <li><a href="#">5</a></li>
            <li><a href="#">6</a></li>
            <li><a href="#">Last</a></li>
          </ul>
        </div>
      </div>
    </div>
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/ksb.js"></script>
  </body>
</html>