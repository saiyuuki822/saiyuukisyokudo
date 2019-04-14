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
