function node_regist(event) {
  $('[name=edit_nid]').val('');
  $('#postModal').modal('show');
  return false;
}

function edit_node(event) {
    nid = $(event).data('nid');
    var ajax_event = event;
    $.ajax(
        '/index.php/post/edit',
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

function post_delete(event) {
  swal({
    title: "本当に削除しますか?",
    text: "削除した投稿は復元することは出来ません",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "キャンセル",
    confirmButtonText: "OK",
    closeOnConfirm: true
  }, function() {
    nid = $(event).data('nid');
    $('.loding-area').show();
    var ajax_event = event;
    $.ajax(
        '/index.php/post/delete',
        {
        type: 'post',
        data: {"post_nid":nid},
        dataType: "json",
        success: function(data) {
          $('.loding-area').hide();
          $(ajax_event).parents('.list-group-item').remove();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert('error');
          $('.loding-area').hide();
          location.href="/";
        }
    });
  });
  return false;
}

function comment_delete(event) {
  swal({
    title: "本当に削除しますか?",
    text: "削除したコメントは復元することは出来ません",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "キャンセル",
    confirmButtonText: "OK",
    closeOnConfirm: true
  }, function() {
    cid = $(event).data('cid');
    $('.loding-area').show();
    var ajax_event = event;
    $.ajax(
        '/index.php/post/comment_delete',
        {
        type: 'post',
        data: {"cid":cid},
        dataType: "json",
        success: function(data) {
          $('.loding-area').hide();
          $(ajax_event).parents('.comment').remove();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert('error');
        }
    });
  });
  return false;
}

function upload(form) {
  $form = $('#upload-form');
  fd = new FormData($form[0]);
  $('.loding-area').show();
  $.ajax(
      '/index.php/post',
      {
      type: 'post',
      processData: false,
      contentType: false,
      data: fd,
      dataType: "json",
      success: function(data) {
          $('.loding-area').hide();
          //location.href="/";
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
          $('.loding-area').hide();
          location.href="/";
      }
  });
  return false;
}

function upload_file(form) {
  $('#body_file').click();
  return false;
}

function change_body_file(form) {
  $form = $('#upload-form');
  fd = new FormData($form[0]);
  $.ajax(
      '/index.php/post/upload_file',
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
          location.href="/";
      }
  });
  return false;
}

function good(event) {
  $('.loding-area').show();
  nid = $(event).data('nid');
    $.ajax(
        '/index.php/post/good',
        {
        type: 'post',
        data: {"nid":nid},
        dataType: "json",
        success: function(data) {
          $('.loding-area').hide();
          if(data.type == 'good') {
            swal({
              title: "Good!",
              text: "いいねしました。",
              imageUrl: '/assets/img/thumbs-up.jpg'
            });
            $(event).html('取り消し');
          } else {
            swal("Cancelled", "いいねを取り消しました。", "error");
            $(event).html('いいね');
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          $('.loding-area').hide();
        }
    });
}
  
function ungood(event) {
  $('.loding-area').show();
  nid = $(event).data('nid');
    $.ajax(
        '/index.php/post/ungood',
        {
        type: 'post',
        data: {"nid":nid},
        dataType: "json",
        success: function(data) {
          $('.loding-area').hide();
          if(data.type == 'ungood') {
            swal({
              title: "But!",
              text: "だめねしました。",
              imageUrl: '/assets/img/thumbs-but.jpg'
            });
            $(event).html('取り消し');
          } else {
            swal("Cancelled", "だめねを取り消しました。", "error");
            $(event).html('だめね');
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          $('.loding-area').hide();
        }
    });
} 

function favorite_node(event) {
  $('.loding-area').show();
  nid = $(event).data('nid');
    $.ajax(
        '/index.php/post/favorite_node',
        {
        type: 'post',
        data: {"nid":nid},
        dataType: "json",
        success: function(data) {
          $('.loding-area').hide();
          if(data.type == 'favorite') {
            swal({
              title: "Favorite!",
              text: "お気に入りに入れました。",
              imageUrl: '/assets/img/favorite.jpg'
            });
            $(event).html('取り消し');
          } else {
            swal("Cancelled", "お気に入りを取り消しました。", "error");
            $(event).html('お気に入り');
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          $('.loding-area').hide();
        }
    });
} 

function good_user(event) {
   var nid = $(event).data('nid');
   $.ajax(
     '/index.php/post/get_good_user',
     {
       type: 'post',
       data: {"nid":nid},
       dataType: "json",
       success: function(data) {
         $('.js-user-list').html('');
         console.log(data);
         $.each(data.good_user,
           function(key, value) {
             $('#tmp-user-list .user_name').html(value.user_name);
             $('#tmp-user-list .user_body').html(value.user_body);
             $('#tmp-user-list a img').attr('src', value.picture);
             $('#tmp-user-list .media-body .follow').attr('data-uid', value.uid);
             $('.js-user-list').append($('#tmp-user-list').prop('outerHTML'));
             $('.js-user-list').children('li:last-child').show();
             $('.js-user-list').children('li:last-child').attr('id', '');
           });
         $('#userModal').modal('show');
         
       }  
     });
  return false;
}

function ungood_user(event) {
   var nid = $(event).data('nid');
   $.ajax(
     '/index.php/post/get_ungood_user',
     {
       type: 'post',
       data: {"nid":nid},
       dataType: "json",
       success: function(data) {
         $('.js-user-list').html('');
         console.log(data);
         $.each(data.ungood_user,
           function(key, value) {
             $('#tmp-user-list .user_name').html(value.user_name);
             $('#tmp-user-list .user_body').html(value.user_body);
             $('#tmp-user-list a img').attr('src', value.picture);
             $('.js-user-list').append($('#tmp-user-list').prop('outerHTML'));
             $('.js-user-list').children('li:last-child').show();
           });
         $('#userModal').modal('show');
       }  
     });
  return false;
}

function follow(event) {
  var uid = $(event).data('uid');
  $('.loding-area').show();
    $.ajax(
        '/index.php/post/follow',
        {
        type: 'post',
        data: {"uid":uid},
        dataType: "json",
        success: function(data) {
          $('.loding-area').hide();
          if(data.type == 'follow') {
            swal({
              title: "Follow!",
              text: "フォローしました。",
              imageUrl: '/assets/img/follow.jpg'
            });
            $(event).html('取り消し');
          } else {
            swal("Cancelled", "フォローを取り消しました。", "error");
            $(event).html('Follow');
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          $('.loding-area').hide();
        }
    });

  return false;
}

function regist(event) {
  $('#registModal').modal('show');
}

function user_post(event) {
  if($("[name=password]").val() != $("[name=repassword]").val()) {
    swal("But Password", "パスワードとパスワードの確認が違います。", "error");
  }
  $('.loding-area').show();
  $form = $('#user_regist-form');
  fd = new FormData($form[0]);
  $('.loding-area').show();
  $.ajax(
      '/index.php/post/user_post',
      {
      type: 'post',
      processData: false,
      contentType: false,
      data: fd,
      dataType: "json",
      success: function(data) {
          alert('success');
          $('.loding-area').hide();
          location.href="/index.php/login";
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
          $('.loding-area').hide();
          location.href="/";
      }
  });
  return false;
}

function change_body_file(form) {
  $form = $('#upload-form');
  fd = new FormData($form[0]);
  $.ajax(
      '/index.php/post/upload_file',
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
          location.href="/";
      }
  });
  return false;
}
