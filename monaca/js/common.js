// This is a JavaScript file

api_root = "https://saiyuuki-syokudo.sakura.ne.jp/drupal/";
sns_api_root = "https://syokudo.myportal.jpn.com/index.php/";

function login_callback(data) {
  for (var i = 0;i < data.navigation.length;i++) {
    $('#navigation').append($('<option>').html(data.navigation[i].menu_name).val(data.navigation[i].menu_id));
  }
  $('#profile_user_name').html(data.user_name);
  $('#profile_user_picture').attr('src', data.picture);
  $('#profile_user_body').html(data.user_body);
}

function node_callback(data) {
  $.each(data, function(index, value){
    $('#node-template .user_name').html 
    (value.field_user_name_value);
    $('#node-template .picture').attr('src', value.picture);
    $('#node-template .image').attr('src', value.image);
    $('#node-template .body').html(value.body_value);
    $('#node-template .created').html(value.created);
    $('#node-template .post_menu').attr('data-nid', value.nid);
    var element = $('#node-template').prop('outerHTML');
    $('#node-area').append(element);
    $('#node-area #node-template').show();
    $('#node-area #node-template').attr('id', '');
    $.each(value.comment, function(index, value){
      $('#node-template .comment-template .user_name').html(value.user_name);
      $('#node-template .comment-template .body').html(value.body);
      $('#node-template .comment-template .created').html(value.created);
      var element = $('#node-template .comment-template').prop('outerHTML');
      $('#node-area #comment-area').append(element);
      $('#node-area #comment-area .comment-template').show();
    });
  });
}

function upload_file(form) {
  $('#body_file').click();
  return false;
}

function post_menu(event) {
    $('.post-view').click();
    edit_node(event);
    return false;
}

function scrollDirection(event) {
  scrollPoint = $(window).scrollTop();
  return false;
}


$(function() {
   user = exec_ajax(sns_api_root+"api/login","post", {"name":"saiyuuki","password":"saiyuuki3"}, login_callback);
  var node_list = exec_ajax(api_root+"api/node_list/all/","get",{}, node_callback);
  $(document).on('click', '#header-home-link', function(){
  window.open('http://mypotal.jpn.com', '_blank', 'location=yes'); 
      return false;
  });

var scroll_start_x = 0;
var scroll_start_y = 0;
$('body').on({
   'touchstart': function(e) {
      scroll_start_x = e.originalEvent.touches[0].pageX;
      scroll_start_y = e.originalEvent.touches[0].pageY;
   },
   'touchmove': function(e) {
      var scroll_end_x = e.originalEvent.touches[0].pageX;
      var scroll_end_y = e.originalEvent.touches[0].pageY;
      $(this).scrollTop($(this).scrollTop() - (scroll_end_y - scroll_start_y));

      if($(this).scrollTop() - (scroll_end_y - scroll_start_y) < 0) {
      $('.loding-area3 img').show();
      setTimeout(function(){
       $('.loding-area3 img').hide();
      },1000);
      }


      $(this).scrollLeft($(this).scrollLeft() - (scroll_end_x - scroll_start_x));
   }
});
});

$(document).on('click', '.post_menu', post_menu);


function exec_ajax(url, ajax_type, params, callback) {
      $.ajax(
        url,
        {
          type: ajax_type,
          dataType: "json",
          data: params,
          success: function(data) {
            callback(data);
            return data;
          },
          error: function(data) {
            return false;
          }
        }
      );
}

function change_body_file(form) {
  $form = $('#upload-form');
  fd = new FormData($form[0]);
  $.ajax(
      sns_api_root + 'post/upload_file',
      {
      type: 'post',
      processData: false,
      contentType: false,
      data: fd,
      dataType: "json",
      success: function(data) {
        var text = $('#post_body').val();
        $('#post_body').val(text+"<img src='"+data.file_url+"' />");
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
          $('.loding-area').hide();
        console.log("ajax通信に失敗しました");
        console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        console.log("textStatus     : " + textStatus);
        console.log("errorThrown    : " + errorThrown.message);
      }
  });
  return false;
}

function upload(form) {
  $form = $('#upload-form');
  fd = new FormData($form[0]);
  $('.loding-area').show();
  $.ajax(
      sns_api_root+'api/post',
      {
      type: 'post',
      processData: false,
      contentType: false,
      data: fd,
      dataType: "json",
      success: function(data) {
          $('.loding-area').hide();
          tabbar = document.querySelector("ons-tabbar");
          tabbar.setActiveTab(0);
          location.href="/";
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert("error");
          $('.loding-area').hide();
          location.href="/";
      }
  });
  return false;
}

function edit_node(event) {
    nid = $(event.currentTarget).data('nid');
    var ajax_event = event;
    $.ajax(
        sns_api_root + 'post/edit',
        {
        type: 'post',
        data: {"post_nid":nid},
        dataType: "json",
        success: function(data) {
          //daraが[0]がついて返ってくる　原因不明
          $('#postModal').modal('show');
          $('[name=post_title]').val(data[0].title);
          $('[name=post_body]').val(data[0].body_value);
          $('[name=navigation]').val(data[0].page_menu);
          $('[name=edit_nid]').val(data[0].nid);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert('error');
          $('.loding-area').hide();
          location.href="/";
        }
    });
  return false;
}