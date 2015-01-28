<?php
  session_start();
  
  if(!isset($_GET['sub'])){
    header('location:sub_list.php');
  }
  $con = mysqli_connect("localhost", "auscaledb", "124578", "ksb");
  
  //get filename from GET ID
  $sub_id = mysqli_real_escape_string($con, $_GET['sub']);
  if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
  }
  $query = "select s.filename, s.extention, s.title, s.description, s.type, s.score, s.rating, s.create_date, u.username, s.resolution_w, s.resolution_h, s.source, s.lr_exists from sub s join user u on u.user_id = s.create_by where sub_id='$sub_id'";
  $result = mysqli_query($con, $query);
  if (!$result) {
    die(mysqli_error($con));
  }
  
  $row = mysqli_fetch_array($result);
  $filename = $row[0];
  $extention = $row[1];
  $title = $row[2];
  $description = $row[3];
  $type_id = $row[4];
  if($row[4]==0){
    $type = "Written";
  }else{
    $type = "Image";
  }
  $score = $row[5];
  if($row[6]==0){
    $rating = "S";
  }else if($row[5]==1){
    $rating = "M";
  }else if($row[5]==2){
    $rating = "E";
  }else{
    $rating = $row[5];
  }
  $create_date = $row[7];
  $create_by = $row[8];
  $resolution_w = $row[9];
  $resolution_h = $row[10];
  $source = $row[11];
  $lr_exists = $row[12];
  
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
    <title>KSB - View Post
    <?php
      if($title){
        echo(" - $title");
      }
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
              <a href="view_profile.php?user=' . $user_id . '">My Profile</a>
              <a href="sub_list.php?m=fav&user=' . $user_id . '">My Favorites</a>
              <a href="sub_list.php?m=upl&user=' . $user_id . '">My Uploads</a>
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
      <div id="content">
        <div id="sidebar">
          <span class="sidebar_title"><?php if($title){echo($title);} ?></span>
          <form id="search" action="sub_list.php" method="GET">
            <input type="text" id="search_box" name="tags" placeholder="Search..." autofocus>
          </form>
          <div id="tag_container">
            <ul id="tag_list">
              <?php
                //get tags from database
                $query ="select t.name, st.tag_id from sub_tag st join tag t on t.tag_id = st.tag_id where st.sub_id='" . $sub_id . "' order by t.name asc;";
                $result = mysqli_query($con, $query);
                if(!$result){
                  die(mysqli_error($con));
                }
                if(mysqli_num_rows($result)>0){
                  while ($row = mysqli_fetch_array($result)){
                    echo('<li><a class="tag" href="sub_list.php?tags=' . $row[0] . '">' . $row[0] . '</a></li>');
                  }
                }
              ?>
            </ul>
          </div>
          <table class="image_details">
            <tr><td>Type:</td><td><?php echo($type); ?></td></tr>
            <tr><td>Rating:</td><td><?php echo($rating); ?></td></tr>
            <tr><td>Score:</td><td><?php echo($score); ?></td></tr>
            <?php if($source != ''){ echo("<tr><td>Source:</td><td>" . $source . "</td></tr>"); } ?>
            <?php
              if($type_id != 0){
                echo('<tr><td>Resolution:</td><td>' . $resolution_w . "x" . $resolution_h . '</td></tr>');
              }
            ?>
            <tr><td>Uploaded By:</td><td><?php echo($create_by); ?></td></tr>
            <tr><td>Uploaded On:</td><td><?php echo($create_date); ?></td></tr>
          </table>
          <div id="sub_controls">
            <a href=<?php echo('"edit_sub.php?sub=' . $sub_id . '"'); ?>>Edit Submission</a>
          </div>
        </div>
        <div id="main">
        <?php
        if($type_id == 0){
          //if story
          $story_contents = file_get_contents('uploads/' . $filename . '.' . $extention);
          echo('
          <div id="story_sub_wrap">
            <ul id="sub_controls">
              <li><a id="cozy_mode" href="javascript:void(0)">Cozy Mode</a></li>');
          if(isset($_SESSION['user_id'])){
            echo('<li><a href="favorite.php?sub=' . $sub_id . '">');
            //check favorite status
            $query = "select * from user_sub_fav where sub_id='$sub_id' and user_id='$user_id';";
            $result = mysqli_query($con, $query);
            if(!$result){
              die(mysqli_error($con));
            }
            $exists = mysqli_num_rows($result);
            if($exists == 1){
              echo('Unfavorite');
            } else {
              echo('Favorite');
            }
          }
        echo('<li><a href="flag.php">Flag</a></li>
              <li><a href="#comments">Comments</a></li>');
              if($_SESSION['perm_delete_sub'] == 1){
                echo('<li><a href="javascript:deleteSub(' . $sub_id . ')">Delete</a></li>');
              }
              echo('
            </ul>
            <span id="story_title">' . $title . '</span>
            <span id="story_desc">' . $description . '</span>
            <div id="story_sub">
              ' . $story_contents . '
            </div>
        </div>');
        } else {
          //else image
          echo('
          <div id="image_sub"><img src="');
            if($lr_exists == 1){
              echo('uploads/lr/' . $filename . '.png"');
            } else {
              echo('uploads/' . $filename . '.' . $extention . '"');
            }
            echo(' alt="' . $title . '"><ul id="sub_controls">
              <li><a href="uploads/' . $filename . '.' . $extention . '">Full Size</a></li>
              ');
          if(isset($_SESSION['user_id'])){
            echo('<li><a href="favorite.php?sub=' . $sub_id . '">');
            //check favorite status
            $query = "select * from user_sub_fav where sub_id='$sub_id' and user_id='$user_id';";
            $result = mysqli_query($con, $query);
            if(!$result){
              die(mysqli_error($con));
            }
            $exists = mysqli_num_rows($result);
            if($exists == 1){
              echo('Unfavorite');
            } else {
              echo('Favorite');
            }
            echo('</a></li><li><a href="flag.php?sub_id=' . $sub_id . '">Flag</a></li>');
            if($_SESSION['perm_delete_sub'] == 1){
              echo('<li><a href="javascript:deleteSub(' . $sub_id . ')">Delete</a></li>');
            }
          }
        echo('
              
              
            </ul>
          </div>');
        }
        ?> 
          <span id="comments">Comments</span>
          <?php
            if(isset($_SESSION['user_id'])){
              echo('
                <form id="submit_comment">
                  <textarea id="comment_textarea"></textarea>
                  <input type="submit" value="Submit" id="comment_submit">
                </form>
          ');
            }
          ?>
          <div id="sub_comments">
          
          <?php
            //echo top rated comment
              //echo top rated comment with ^ as parent
                //echo top rated comment with ^ as parent
                  //echo top rated comment with ^ as parent
                  //how far down do we go?
                  ?>
            <div class="comment">
              <div class="comment_details">
                <div class="comment_vote">
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                  <span>10</span>
                  <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                </div>
                <img class="user_comment_image" src="images/120x120.gif" alt="Someguy's Avatar">
                <a href="view_user.php?id=1">Someguy</a>
                <span>Member</span>
                <span>10/12/14</span>
              </div>
              <div class="comment_body">
                This image is really good. I like the parts that are good. This image is really good. I like the parts that are good. This image is really good. I like the parts that are good. This image is really good. I like the parts that are good. This image is really good. I like the parts that are good. This image is really good. I like the parts that are good. This image is really good. I like the parts that are good. This image is really good. I like the parts that are good. This image is really good. I like the parts that are good. This image is really good. I like the parts that are good. This image is really good. I like the parts that are good. This image is really good. I like the parts that are good. This image is really good. I like the parts that are good. This image is really good. I like the parts that are good. This image is really good. I like the parts that are good. This image is really good. I like the parts that are good. This image is really good. I like the parts that are good. This image is really good. I like the parts that are good.
                <?php if(isset($_SESSION['user_id'])){ echo('<div class="comment_footer"><a href="javascript:void(0);" class="reply_link">Reply</a></div>');} ?>
                </div>
              <div class="comment">
                <div class="comment_details">
                                  <div class="comment_vote">
                    <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                    <span>10</span>
                    <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                  </div>
                  <img class="user_comment_image" src="images/120x120.gif" alt="Someguy's Avatar">
                  <a href="view_user.php?id=1">Someguy</a>
                  <span>Member</span>
                  <span>10/12/14</span>
                </div>
                <div class="comment_body">
                  This image is really good. I like the parts that are good.
                </div>
                <div class="comment">
                  <div class="comment_details">
                    <img class="user_comment_image" src="images/120x120.gif" alt="Someguy's Avatar">
                    <a href="view_user.php?id=1">Someguy</a>
                    <span>Member</span>
                    <span>10/12/14</span>
                  </div>
                  <div class="comment_body">
                    This image is really good. I like the parts that are good.
                    <div class="comment_vote">
                      <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                      <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                      <span>10 points</span>
                    </div>
                  </div>
                  <div class="comment">
                    <div class="comment_details">
                      <img class="user_comment_image" src="images/120x120.gif" alt="Someguy's Avatar">
                      <a href="view_user.php?id=1">Someguy</a>
                      <span>Member</span>
                      <span>10/12/14</span>
                    </div>
                    <div class="comment_body">
                      This image is really good. I like the parts that are good.
                      <div class="comment_vote">
                        <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                        <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                        <span>10 points</span>
                      </div>
                    </div>
                    <div class="comment">
                    <div class="comment_details">
                      <img class="user_comment_image" src="images/120x120.gif" alt="Someguy's Avatar">
                      <a href="view_user.php?id=1">Someguy</a>
                      <span>Member</span>
                      <span>10/12/14</span>
                    </div>
                    <div class="comment_body">
                      This image is really good. I like the parts that are good.
                      <div class="comment_vote">
                        <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                        <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                        <span>10 points</span>
                      </div>
                    </div>
                    <div class="comment">
                    <div class="comment_details">
                      <img class="user_comment_image" src="images/120x120.gif" alt="Someguy's Avatar">
                      <a href="view_user.php?id=1">Someguy</a>
                      <span>Member</span>
                      <span>10/12/14</span>
                    </div>
                    <div class="comment_body">
                      This image is really good. I like the parts that are good.
                      <div class="comment_vote">
                        <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                        <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                        <span>10 points</span>
                      </div>
                    </div>
                    <div class="comment">
                    <div class="comment_details">
                      <img class="user_comment_image" src="images/120x120.gif" alt="Someguy's Avatar">
                      <a href="view_user.php?id=1">Someguy</a>
                      <span>Member</span>
                      <span>10/12/14</span>
                    </div>
                    <div class="comment_body">
                      This image is really good. I like the parts that are good.
                      <div class="comment_vote">
                        <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                        <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                        <span>10 points</span>
                      </div>
                    </div>
                    <div class="comment">
                    <div class="comment_details">
                      <img class="user_comment_image" src="images/120x120.gif" alt="Someguy's Avatar">
                      <a href="view_user.php?id=1">Someguy</a>
                      <span>Member</span>
                      <span>10/12/14</span>
                    </div>
                    <div class="comment_body">
                      This image is really good. I like the parts that are good.
                      <div class="comment_vote">
                        <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                        <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                        <span>10 points</span>
                      </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="comment">
                <div class="comment_details">
                  <img class="user_comment_image" src="images/120x120.gif" alt="Someguy's Avatar">
                  <a href="view_user.php?id=1">Someguy</a>
                  <span>Member</span>
                  <span>10/12/14</span>
                </div>
                <div class="comment_body">
                  This image is really good. I like the parts that are good.
                  <div class="comment_vote">
                    <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                    <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                    <span>10 points</span>
                  </div>
                </div>
              </div>
              <div class="comment">
                <div class="comment_details">
                  <img class="user_comment_image" src="images/120x120.gif" alt="Someguy's Avatar">
                  <a href="view_user.php?id=1">Someguy</a>
                  <span>Member</span>
                  <span>10/12/14</span>
                </div>
                <div class="comment_body">
                  This image is really good. I like the parts that are good.
                  <div class="comment_vote">
                    <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                    <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                    <span>10 points</span>
                  </div>
                </div>
                <div class="comment">
                  <div class="comment_details">
                    <img class="user_comment_image" src="images/120x120.gif" alt="Someguy's Avatar">
                    <a href="view_user.php?id=1">Someguy</a>
                    <span>Member</span>
                    <span>10/12/14</span>
                  </div>
                  <div class="comment_body">
                    This image is really good. I like the parts that are good.
                    <div class="comment_vote">
                      <a href="javascript:void(0);"><i class="fa fa-thumbs-up fa-lg thumbs_up"></i></a>
                      <a href="javascript:void(0);"><i class="fa fa-thumbs-down fa-lg thumbs_down"></i></a>
                      <span>10 points</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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