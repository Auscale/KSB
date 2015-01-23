$("#menu_link").click(
  function() {
    if($("#navbar_menu").is(":visible")){
      $("#navbar_menu").slideUp();
      $("#menu_icon").removeClass("navbar_active");
    } else {
      $("#navbar_menu").slideDown();
      $("#menu_icon").addClass("navbar_active");
      $("#navbar_search").slideUp();
      $("#search_icon").removeClass("navbar_active");
      $("#navbar_user").slideUp();
      $("#user_icon").removeClass("navbar_active");
      $("#sign_in_tab").removeClass("navbar_active");
    }
  }
);

$("#search_link").click(
  function() {
    if($("#navbar_search").is(":visible")){
      $("#navbar_search").slideUp();
      $("#search_icon").removeClass("navbar_active");
    } else {
      $("#navbar_search").slideDown();
      $("#search_icon").addClass("navbar_active");
      $("#navbar_menu").slideUp();
      $("#menu_icon").removeClass("navbar_active");
      $("#navbar_user").slideUp();
      $("#user_icon").removeClass("navbar_active");
      $("#sign_in_tab").removeClass("navbar_active");
    }
  }
);

$("#user_link").click(user_bar_slide);

$("#sign_in_tab").click(user_bar_slide);
$("#user_tab").click(user_bar_slide);

function user_bar_slide() {
  if($("#navbar_user").is(":visible")){
    $("#navbar_user").slideUp();
    $("#user_icon").removeClass("navbar_active");
    $("#sign_in_tab").removeClass("navbar_active");
  } else {
    $("#navbar_user").slideDown();
    $("#user_icon").addClass("navbar_active");
    $("#sign_in_tab").addClass("navbar_active");
    $("#navbar_search").slideUp();
    $("#search_icon").removeClass("navbar_active");
    $("#navbar_menu").slideUp();
    $("#menu_icon").removeClass("navbar_active");
    $("#username_text").focus();
  }
}

$("#cozy_mode").click(
  function(){
    if($("#main").hasClass("cozy_mode")){
      $("#sidebar").removeClass("cozy_mode");
      $("#main").removeClass("cozy_mode");
      $("a").removeClass("cozy_mode");
    } else {
      $("#sidebar").addClass("cozy_mode");
      $("#main").addClass("cozy_mode");
      $("a").addClass("cozy_mode");
    }
  }
);

$("#sub_type_story").click(
  function(){
    $("#sub_form_image_upload_label").hide();
    $("#sub_form_image_upload_file").hide();
    $("#sub_form_image_upload_url").hide();
    $("#ckeditor_container").show();
  }
);

$("#sub_type_image").click(
  function(){
    $("#ckeditor_container").hide();
    $("#sub_form_image_upload_label").show();
    $("#sub_form_image_upload_file").show();
    $("#sub_form_image_upload_url").show();
  }
);

$(".forum_category_collapse").each(function(){
  $(this).click(function(){
    if ($(this).closest("tbody").next("tbody").is(":visible")){
      $(this).closest("tbody").next("tbody").hide();
      $(this).find("i").removeClass("fa-minus-square");
      $(this).find("i").addClass("fa-plus-square");
    } else {
      $(this).closest("tbody").next("tbody").show();
      $(this).find("i").addClass("fa-minus-square");
      $(this).find("i").removeClass("fa-plus-square");
    }
  });
});

$(".reply_link").each(function(){
  $(this).click(function(){
    $("#submit_comment").clone().append($(this).closest(".comment_footer"));
  });
});

$(document).ready(function() {
  $("body").removeClass("preload");
  if($("#ckeditor_container").length){
    $("#ckeditor_container").hide();
  }
  if($("#search_box").length){
    var input = $("#search_box");
    var len = input.val().length;
    input[0].focus();
    input[0].setSelectionRange(len,len);
  }
});

$(window).resize(function(){
  if (window.innerWidth >= 520){
    $("#navbar_menu").hide();
    $("#navbar_search").hide();
    $("#menu_icon").removeClass("navbar_active");
    $("#search_icon").removeClass("navbar_active");
  }
});

function deleteSub(sub_id){
  if(confirm("Are you sure you wish to delete this post?") === true){
    window.location.href = "delete_sub.php?sub=" + sub_id;
  }
}