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
          } else {
            swal("Cancelled", "いいねを取り消しました。", "error");
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
          } else {
            swal("Cancelled", "だめねを取り消しました。", "error");
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
          } else {
            swal("Cancelled", "お気に入りを取り消しました。", "error");
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          $('.loding-area').hide();
        }
    });
} 
