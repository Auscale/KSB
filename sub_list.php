<?php
  //tags are still a little wonky. revisit later.
  //tags are fuckin solid. Don't even worry.
  session_start();
  //Variables
  $load_subs = 5;
  
  $con = mysqli_connect("localhost", "auscaledb", "124578", "ksb");
  
  if(!isset($_GET['m'])){
    $_GET['m'] = 'a';
  }
  
  //setup pagination
  if(!isset($_GET['page'])){
    $page = 1;
  } else {
    $page = mysqli_real_escape_string($con, $_GET['page']);
    if($page <= 0){
      $page = 1;
    }
  }
  
  $pag_from = ($page - 1) * $load_subs;
  
  if(mysqli_connect_errno()){
    echo "Database connection failed: " . mysqli_connect_error();
  }
  if(isset($_GET['tags'])){
    $get_tag_string = strtolower(trim(mysqli_real_escape_string($con, $_GET['tags'])));
    if($get_tag_string === ''){
      $tag_search = 0;
    } else {
      $tag_search = 1;
    }
  } else {
    $get_tag_string ='';
    $tag_search = 0;
  }
  //turn tag string into an array
  $tag_array = explode(' ', $get_tag_string);
  //remove duplicate tags
  $tag_array = array_keys(array_flip($tag_array));
  $pos_tag = '';
  $neg_tag = '';
  $pos_tag_count = 0;
  $additional_where = '';
  foreach($tag_array as $value){
    //check for special tags containing :
    $special_tag_pos = strpos($value, ":");
    if($special_tag_pos !== false){
      //is special tag. check for - at start.
      if(substr($value, 0, 1) == '-'){
        //is negative special tag
        $special_tag_type = 1;
        //cut - off start of string
        $tag_str = substr($value, 1);
        $special_tag_pos--;
      } else {
        //is positive special tag
        $special_tag_type = 0;
        $tag_str = $value;
      }
      //find out what special tag it is. get first part of tag
      if(substr($tag_str, 0, $special_tag_pos) == "rating"){
        //find what rating to filter by
        $rating_string = substr($tag_str, $special_tag_pos+1);
        if($rating_string === 's'){
          if($special_tag_type === 1){
            $additional_where = " and s.rating!='0'";
          } else {
            $additional_where = " and s.rating='0'";
          }
        } else if($rating_string === 'm'){
          if($special_tag_type === 1){
            $additional_where = " and s.rating!='1'";
          } else {
            $additional_where = " and s.rating='1'";
          }
        } else if($rating_string === 'e'){
          if($special_tag_type === 1){
            $additional_where = " and s.rating!='2'";
          } else {
            $additional_where = " and s.rating='2'";
          }
        } else {
          //incorrect 2nd part of tag
          $additional_where = " and 1=2";
        }
      } else if(substr($tag_str, 0, $special_tag_pos) == "order"){
        //get what we're ordering by
        $order_string = substr($tag_str, $special_tag_pos+1);
        if($order_string === 'score'){
          //set ordering to score
          $ordering = "score desc";
        }
      } else {
        //incorrect tag. What do?
        $additional_where = " and 1=2";
      }
    } else {
      if(substr($value, 0, 1) == '-'){
        //neg tag - remove minus character from start.
        $trim_tag = substr($value,1);
        $neg_tag .= "'$trim_tag',";
      } else {
        //pos tag
        $pos_tag .= "'$value',";
        $pos_tag_count++;
      }
    }
  }
  unset($value);
  //trim trailing comma from query tag strings
  $neg_tag = substr($neg_tag, 0, -1);
  $pos_tag = substr($pos_tag, 0, -1);
  //if no tags, replace with '' for sql syntax reasons
  if($neg_tag == ''){
    $neg_tag = "''";
  }
  if($pos_tag == ''){
    $pos_tag = "''";
  }
  
  if(isset($_GET['user'])){
    $user_id = mysqli_real_escape_string($con, $_GET['user']);
    //get username from database
    $query = "select username from user where user_id='$user_id';";
    $result = mysqli_query($con, $query);
    if(!$result){
      die(mysqli_error($con));
    }
    $row = mysqli_fetch_array($result);
    $username = $row[0];
  } else {
    $user_id = '';
    $username = '';
    $mode = '';
  }
  if(isset($_GET['m'])){
    $mode = $_GET['m'];
  } else {
    $mode = '';
  }
  //set ordering default to new first if no other ordering has been done
  if(!isset($ordering)){
    $ordering = "create_date desc";
  }
  //set queries based on mode
  if($tag_search === 1){
    if($mode === 'fav'){
      //searching favorites by tag
      $tag_query = "select t.name, st.tag_id, count(st.tag_id) from sub_tag st join tag t on t.tag_id = st.tag_id join sub s on s.sub_id = st.sub_id where exists(select 1 from(select s.sub_id, sum(case when t.name in ($pos_tag) then 1 else 0 end) positive, sum(case when t.name in ($neg_tag) then 1 else 0 end) negative from sub_tag st join sub s on s.sub_id = st.sub_id join tag t on t.tag_id = st.tag_id join user_sub_fav usf on usf.sub_id = s.sub_id where usf.user_id='$user_id'$additional_where group by s.sub_id) res where res.positive >= $pos_tag_count and res.negative = 0 and res.sub_id = st.sub_id) group by t.name order by count(t.name) desc, t.name asc;";
      $tag_result = mysqli_query($con, $tag_query);
      if(!$tag_result){
        die(mysqli_error($con));
      }
      $sub_query = "select res.sub_id, res.type, res.title, res.score, res.rating, res.filename from(select s.sub_id, s.type, s.title, s.score, s.rating, s.filename, s.create_date, sum(case when t.name in ($pos_tag) then 1 else 0 end) positive, sum(case when t.name in ($neg_tag) then 1 else 0 end) negative from sub_tag st join sub s on s.sub_id = st.sub_id join tag t on t.tag_id = st.tag_id join user_sub_fav usf on usf.sub_id = s.sub_id where usf.user_id='$user_id'$additional_where group by s.sub_id)res where res.positive >= $pos_tag_count and res.negative = 0 order by res.$ordering limit $pag_from, $load_subs;";
      $sub_result = mysqli_query($con, $sub_query);
      if(!$sub_result){
        die(mysqli_error($con));
      }
      $count_query = "select count(*) from(select sum(case when t.name in ($pos_tag) then 1 else 0 end) positive, sum(case when t.name in ($neg_tag) then 1 else 0 end) negative from sub_tag st join sub s on s.sub_id = st.sub_id join tag t on t.tag_id = st.tag_id join user_sub_fav usf on usf.sub_id = s.sub_id where usf.user_id='$user_id'$additional_where group by s.sub_id)res where res.positive >= $pos_tag_count and res.negative = 0;";
      $count_result = mysqli_query($con, $count_query);
      if(!$count_result){
        die(mysqli_error($con));
      }
      $sub_count = mysqli_fetch_array($count_result)[0];
    } else if($mode === 'upl'){
      //searching uploads by tag
      $tag_query = "select t.name, st.tag_id, count(st.tag_id) from sub_tag st join tag t on t.tag_id = st.tag_id join sub s on s.sub_id = st.sub_id where exists(select 1 from(select s.sub_id, sum(case when t.name in ($pos_tag) then 1 else 0 end) positive, sum(case when t.name in ($neg_tag) then 1 else 0 end) negative from sub_tag st join sub s on s.sub_id = st.sub_id join tag t on t.tag_id = st.tag_id where 1=1$additional_where group by s.sub_id) res where res.positive >= $pos_tag_count and res.negative = 0 and res.sub_id = st.sub_id)and s.create_by='$user_id' group by t.name order by count(t.name) desc, t.name asc;";
      $tag_result = mysqli_query($con, $tag_query);
      if(!$tag_result){
        die(mysqli_error($con));
      }
      $sub_query = "select res.sub_id, res.type, res.title, res.score, res.rating, res.filename from(select s.sub_id, s.type, s.title, s.score, s.rating, s.filename, s.create_date, sum(case when t.name in ($pos_tag) then 1 else 0 end) positive, sum(case when t.name in ($neg_tag) then 1 else 0 end) negative from sub_tag st join sub s on s.sub_id = st.sub_id join tag t on t.tag_id = st.tag_id where s.create_by='$user_id'$additional_where group by s.sub_id)res where res.positive >= $pos_tag_count and res.negative = 0 order by res.$ordering limit $pag_from, $load_subs;";
      $sub_result = mysqli_query($con, $sub_query);
      if(!$sub_result){
        die(mysqli_error($con));
      }
      $count_query = "select count(*) from(select sum(case when t.name in ($pos_tag) then 1 else 0 end) positive, sum(case when t.name in ($neg_tag) then 1 else 0 end) negative from sub_tag st join sub s on s.sub_id = st.sub_id join tag t on t.tag_id = st.tag_id where s.create_by='$user_id'$additional_where group by s.sub_id)res where res.positive >= $pos_tag_count and res.negative = 0;";
      $count_result = mysqli_query($con, $count_query);
      if(!$count_result){
        die(mysqli_error($con));
      }
      $sub_count = mysqli_fetch_array($count_result)[0];
    } else {
      //searching all by tag
      $tag_query = "select t.name, st.tag_id, count(st.tag_id) from sub_tag st join tag t on t.tag_id = st.tag_id where exists(select 1 from(select s.sub_id, sum(case when t.name in ($pos_tag) then 1 else 0 end) positive, sum(case when t.name in ($neg_tag) then 1 else 0 end) negative from sub_tag st join sub s on s.sub_id = st.sub_id join tag t on t.tag_id = st.tag_id where 1=1$additional_where group by s.sub_id) res where res.positive >= $pos_tag_count and res.negative = 0 and res.sub_id = st.sub_id) group by t.name order by count(t.name) desc, t.name asc;";
      $tag_result = mysqli_query($con, $tag_query);
      if(!$tag_result){
        die(mysqli_error($con));
      }
      $sub_query = "select res.sub_id, res.type, res.title, res.score, res.rating, res.filename from(select s.sub_id, s.type, s.title, s.score, s.rating, s.filename, s.create_date, sum(case when t.name in ($pos_tag) then 1 else 0 end) positive, sum(case when t.name in ($neg_tag) then 1 else 0 end) negative from sub_tag st join sub s on s.sub_id = st.sub_id join tag t on t.tag_id = st.tag_id where 1=1$additional_where group by s.sub_id)res where res.positive >= $pos_tag_count and res.negative = 0 order by res.$ordering limit $pag_from, $load_subs;";
      $sub_result = mysqli_query($con, $sub_query);
      if(!$sub_result){
        die(mysqli_error($con));
      }
      $count_query = "select count(*) from(select sum(case when t.name in ($pos_tag) then 1 else 0 end) positive, sum(case when t.name in ($neg_tag) then 1 else 0 end) negative from sub_tag st join sub s on s.sub_id = st.sub_id join tag t on t.tag_id = st.tag_id where 1=1$additional_where group by s.sub_id)res where res.positive >= $pos_tag_count and res.negative = 0;";
      $count_result = mysqli_query($con, $count_query);
      if(!$count_result){
        die(mysqli_error($con));
      }
      $sub_count = mysqli_fetch_array($count_result)[0];
    }
  } else {
    if($mode === 'fav'){
      //all favorites by user
      $tag_query = "select t.name, st.tag_id, count(st.tag_id) from sub_tag st join tag t on t.tag_id = st.tag_id join user_sub_fav usf on usf.sub_id = st.sub_id where usf.user_id='$user_id' group by st.tag_id order by count(st.tag_id) desc, t.name asc;";
      $tag_result = mysqli_query($con, $tag_query);
      if(!$tag_result){
        die(mysqli_error($con));
      }
      $order_table = 'usf';
      if($ordering === 'score desc'){
        $order_table = 's';
      }
      $sub_query = "select s.sub_id, s.type, s.title, s.score, s.rating, s.filename from sub s join user_sub_fav usf on usf.sub_id = s.sub_id where usf.user_id='$user_id' order by $order_table.$ordering limit $pag_from, $load_subs;";
      $sub_result = mysqli_query($con, $sub_query);
      if(!$sub_result){
        die(mysqli_error($con));
      }
      $count_query = "select count(*) from user_sub_fav where user_id='$user_id';";
      $count_result = mysqli_query($con, $count_query);
      if(!$count_result){
        die(mysqli_error($con));
      }
      $sub_count = mysqli_fetch_array($count_result)[0];
    } else if($mode === 'upl'){
      //all uploads by user
      $tag_query = "select t.name, st.tag_id, count(st.tag_id) from sub_tag st join tag t on t.tag_id = st.tag_id join sub s on s.sub_id = st.sub_id where s.create_by='$user_id' group by st.tag_id order by count(st.tag_id) desc, t.name asc;";
      $tag_result = mysqli_query($con, $tag_query);
      if(!$tag_result){
        die(mysqli_error($con));
      }
      $sub_query = "select sub_id, type, title, score, rating, filename from sub where create_by='$user_id' order by $ordering limit $pag_from, $load_subs;";
      $sub_result = mysqli_query($con, $sub_query);
      if(!$sub_result){
        die(mysqli_error($con));
      }
      $count_query = "select count(*) from sub where create_by='$user_id';";
      $count_result = mysqli_query($con, $count_query);
      if(!$count_result){
        die(mysqli_error($con));
      }
      $sub_count = mysqli_fetch_array($count_result)[0];
    } else {
      //all posts
      $tag_query ="select t.name, st.tag_id, count(st.tag_id) from sub_tag st join tag t on t.tag_id = st.tag_id group by st.tag_id order by count(st.tag_id) desc;";
      $tag_result = mysqli_query($con, $tag_query);
      if(!$tag_result){
        die(mysqli_error($con));
      }
      $sub_query = "select sub_id, type, title, score, rating, filename from sub order by $ordering limit $pag_from, $load_subs;";
      $sub_result = mysqli_query($con, $sub_query);
      if(!$sub_result){
        die(mysqli_error($con));
      }
      $count_query = "select count(*) from sub;";
      $count_result = mysqli_query($con, $count_query);
      if(!$count_result){
        die(mysqli_error($con));
      }
      $sub_count = mysqli_fetch_array($count_result)[0];
    }
  }
?>
<!doctype html>
<html>
  <head>
    <!--
    KSB
    Project started 1/12/14
    
    Notes:
      
    -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KSB - 
    <?php
      if($mode === 'upl'){
        echo("$username's Uploads");
      } else if($mode === 'fav'){
        echo("$username's Favorites");
      } else {
        echo("View Posts");
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
      <ul id="navbar_menu">
        <li><a href="sub_list.php">Posts</a></li>
        <li><a href="submit.php">Submit</a></li>
        <li><a href="forum.php">Forum</a></li>
        <li><a href="news.php">News</a></li>
      </ul>
      <div id="navbar_search">
        <form id="search" method="GET" action="sub_list.php">
          <input type="text" id="navbar_search_box" name="tags" <?php echo('value="' . stripslashes($get_tag_string) . '"'); ?> placeholder="Search...">
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
      <div id="content">
        <div id="sidebar">
          <span class="sidebar_title">
            <?php
              if($mode === 'upl'){
                echo($username . "'s Uploads");
              } else if($mode === 'fav'){
                echo($username . "'s Favorites");
              } else {
                echo("All Posts");
              }
            ?>
          </span>
          <form id="search" method="GET" action="sub_list.php">
            <?php
              if($mode === 'upl'){
                echo('<input type="hidden" name="m" value="upl">');
              } else if($mode === 'fav'){
                echo('<input type="hidden" name="m" value="fav">');
              }
              if(isset($_GET['user'])){
                echo('<input type="hidden" name="user" value="' . $_GET['user'] . '">');
              }
            ?>
            <input type="text" id="search_box" name="tags" <?php echo('value="' . stripslashes($get_tag_string) . '"'); ?> placeholder="Search..." autofocus>
          </form>
          <div id="tag_container">
            <ul id="tag_list">
            <?php
                //spit out tag info
                if (mysqli_num_rows($tag_result) != 0){
                  while ($row = mysqli_fetch_array($tag_result)){
                    echo('<li><a class="tag_control" href="sub_list.php?');
                    if($mode === 'fav'){
                      echo("m=fav&user=$user_id&");
                    } else if($mode === 'upl'){
                      echo("m=upl&user=$user_id&");
                    }
                    echo('tags=' . stripslashes($get_tag_string) . '+' . $row[0] . '">+</a><a class="tag_control" href="sub_list.php?');
                    if($mode === 'fav'){
                      echo("m=fav&user=$user_id&");
                    } else if($mode === 'upl'){
                      echo("m=upl&user=$user_id&");
                    }
                    echo('tags=' . stripslashes($get_tag_string) . ' -' . $row[0] . '">-</a><a class="tag" href="sub_list.php?');
                    if($mode === 'fav'){
                      echo("m=fav&user=$user_id&");
                    } else if($mode === 'upl'){
                      echo("m=upl&user=$user_id&");
                    }
                    echo('tags=' . $row[0] . '">' . $row[0] . '</a><span class="tag_count">(' . $row[2] . ')</span></li>');
                  }
                }
              ?>
            </ul>
          </div>
        </div>
        <div id="main">
          <div id="news_bar">
            <span class="news_bar_title">Breaking News:</span>
            <span class="news_bar_body">This just in! There's a thing that everyone needs to know about, and it's so important that we had to put it at the top of this page! Click <a href="#">here</a> for more information!</span><i class="fa fa-close" id="news_bar_close"></i>
          </div>
          <div id="subs">
            <?php
              if (mysqli_num_rows($sub_result) == 0){
                echo('<div class="message_box">No Posts Found</div>');  
              } else {
                while ($row = mysqli_fetch_array($sub_result)){
                  //fav count
                  $query = "select count(*) from user_sub_fav where sub_id='" . $row[0] . "' group by sub_id;";
                  $result = mysqli_query($con, $query);
                  if(!$result){
                    die(mysqli_error());
                  }
                  $count = mysqli_fetch_array($result);
                  if($count[0] == ''){
                    $fav_count = 0;
                  } else {
                    $fav_count = $count[0]; 
                  }
                  //set ratings
                  if($row[4] == 0){
                    $rating = '<span class="sub_rating sub_positive" href="#">S</span>';
                  } else if($row[4] == 1) {
                    $rating = '<span class="sub_rating sub_neutral" href="#">M</span>';
                  } else {
                    $rating = '<span class="sub_rating sub_negative" href="#">E</span>';
                  }
                  //set score
                  if($row[3] < 0){
                    $score = '<i class="fa fa-thumbs-down"></i><span class="sub_score sub_negative">' . $row[3];
                  } else {
                    $score = '<i class="fa fa-thumbs-up"></i><span class="sub_score sub_positive">' . $row[3];
                  }
                  //if sub is a story
                  if ($row[1] == 0){
                    $sub_box = '<div class="sub">
                                  <a class="sub_title" href="view_sub.php?sub=' . $row[0] . '">
                                    <div class="sub_box sub_box_story">
                                      ' . $row[2] . '
                                    </div>
                                  </a>';
                    $artist = 'Author';
                  //else image
                  } else {
                    $sub_box = '<div class="sub">
                                  <a class="sub_image_link" href="view_sub.php?sub=' . $row[0] . '">
                                    <div class="sub_box">
                                      <img class="sub_thumb" src="uploads/thumbs/' . $row[5] . '.jpg" alt="Image Thumbnail">
                                    </div>
                                  </a>';
                    $artist = 'Artist';
                  }
                  echo($sub_box . '
                      <div class="sub_stats">'
                        . $score . '</span>
                        <i class="fa fa-exclamation-triangle"></i>' . $rating . '
                        <i class="fa fa-heart"></i><span class="sub_fav" href="#">' . $fav_count . '</span>
                        <i class="fa fa-comment"></i><span class="sub_com" href="#">28</span>
                      </div>
                    </div>
                  ');
                }
              }
              ?>
            <div id="sub_break"></div>
          </div>
          
          <div id="pag_cont">
            <ul class="pag">
              <?php
                function build_params($page, $offset){
                  $params = array_merge($_GET, array("page" => ($page + $offset)));
                  $new_query_string = http_build_query($params);
                  return $new_query_string;
                }
                $max_page = $sub_count/$load_subs;
                $max_page = ceil($max_page);
                //pagination. Will always show current.
                if ($page-3 >= 1){
                  echo('<li><a href="sub_list.php?' . build_params(1, 0) . '">First</a></li>');
                }
                if($page-2 >= 1){
                  echo('<li><a href="sub_list.php?' . build_params($page, -2) . '">'); echo($page-2); echo('</a></li>');
                }
                if($page-1 >= 1){
                  echo('<li><a href="sub_list.php?' . build_params($page, -1) . '">'); echo($page-1); echo('</a></li>');
                }
                echo('<li><a href="#" id="pag_cur">' . $page . '</a></li>');
                if($page+1 <= $max_page){
                  echo('<li><a href="sub_list.php?' . build_params($page, 1) . '">'); echo($page+1); echo('</a></li>');
                }
                if($page+2 <= $max_page){
                  echo('<li><a href="sub_list.php?' . build_params($page, 2) . '">'); echo($page+2); echo('</a></li>');
                }
                if($page+3 <= $max_page){
                  echo('<li><a href="sub_list.php?' . build_params($max_page, 0) . '">Last</a></li>');
                }
              ?>
            </ul>
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
  unset($_SESSION['message']);
?>