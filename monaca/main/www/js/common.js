// This is a JavaScript file

//api_root = "https://saiyuuki-syokudo.sakura.ne.jp/drupal/";
api_root = "https://saiyuuki-syokudo.sakura.ne.jp/index.php/";
scroll_start_x = 0;
scroll_start_y = 0;
user = [];
user_list = [];
node = [];
offset = 0;

condition = [];
limit = 10;
next = true;
exec_flg = false;
exec_url = "";
/*
function show_image_caption(event) {
  var idx = parseInt($('#upload-form').find('.image_caption_wrapper:last').data('idx')) + 1;
  if(idx > 10) {
    alert("画像と文章の追加は10枚までです。");
    return false;
  }
  var template = $('#image_caption_template').prop('outerHTML');
  $(event.currentTarget).hide();
  $(event.currentTarget).next('.image_caption').show();
  $('#upload-form').find('.image_caption_wrapper:last').after(template);
  $('#upload-form').find('.image_caption_wrapper:last').attr('data-idx', idx);
  $('#upload-form').find('.image_caption_wrapper:last').find('.add_caption').attr('name', 'add_caption'+idx);
  alert($('#upload-form').find('.image_caption_wrapper:last').find('.add_caption').attr('name'));
  $('#upload-form').find('.image_caption_wrapper:last').find('.add_image').attr('name', 'add_image'+idx);
  alert($('#upload-form').find('.image_caption_wrapper:last').find('.add_image').attr('name'));
  if(idx !== 10) {
  $('#upload-form').find('.image_caption_wrapper:last').find('a').show();
  } else {
  $('#upload-form').find('.image_caption_wrapper:last').find('a').hide();
  }
  return false;
}
*/

function set_login_form() {
  $('#name').val("admin");
  $('#password').val("Saiyuuki31413");
}


function show_bookmark(event) {
  exec_ajax(api_root+"api/node_list/all/data","post",{"uid":user.uid, "type":"bookmark"}, node_callback,user,set_action);
  $('#node-area').html('');
  var menu = document.getElementById('menu');
  menu.load().then();
  menu.close();
  return false;

}

function user_post_callback(data) 
{
  if(data.result != false) {
    $('#userModal').modal('hide');
    if(data.edit_uid !== undefined) {
      alert("ユーザー登録が成功しました。ログインしてお楽しみ下さい。");
    } else {
      alert("ユーザー編集が成功しました。");
      tabbar = document.querySelector("ons-tabbar");
      tabbar.setActiveTab(0);
    }
  } else {
    alert("登録エラー：入力情報を見直してください。");
  }
}

function user_post(event) {
  exec_form_ajax('#user_regist-form', api_root + 'api/user_post', user_post_callback);
  return false;
}

function node_callback(data) {
  console.log(data);
  offset = offset + limit;
  next = data.next;
  $.each(data.list, function(index, value){
    if($('#node-area').find('.node-'+value.nid).length) {
      return true;
    }
    node[value.nid] = value;
    $('#node-template').addClass('node-'+value.nid);
    $('#node-template .post').attr('id','node-'+value.nid);
    
    if(value.is_good_user == true) {
      $('#node-template .good_user').attr('data-nid', value.nid);
      $('#node-template .good_user').show();
    } else {
      $('#node-template .good_user').hide();
    }
    if(value.is_ungood_user == true) {
      $('#node-template .but_user').attr('data-nid', value.nid);
      $('#node-template .but_user').show();
    } else {
      $('#node-template .but_user').hide();
    }
    $('#node-template .user_name a').attr 
    ('data-uid', value.uid);
    $('#node-template .user_name a').attr 
    ('data-user_body', value.user_body);
    $('#node-template .user_name a').html 
    (value.user_name);
    $('#node-template .node .picture').attr('src', value.picture);
    $('#node-template .post-image').attr('src', value.image);
    $('#node-template .title').html(value.title);
    if(value.tag_name !== undefined) {
      $('#node-template .tags-wrapper button').attr('data-tag_id', value.tag_id);
      $('#node-template .tags-wrapper span').html(value.tag_name);
    } else {
    $('#node-template .tags-wrapper').hide();
    }
    $('#node-template .body').html(value.body_value);
    $('#node-template .created').html(value.created);
     if(user.uid == value.uid) {
      $('#node-template .post_menu').show();
      $('#node-template .post_delete').show();
      $('#node-template .post_menu').attr('data-nid', value.nid);
      $('#node-template .post_delete').attr('data-nid', value.nid);
    } else {
      $('#node-template .post_menu').hide();
      $('#node-template .post_delete').hide();
    }

    $('#node-template .image_caption:first .field_image').attr('src', '');
    $('#node-template .image_caption:first .caption').html('');
    console.log("aaa");
    if(value.image_caption.length) {
      $('#node-template #carousel').removeAttr('disabled');
    } else {
      $('#node-template #carousel').attr('disabled', true);
    }
    console.log("bbb");
    $.each(value.image_caption, function(image_caption_idx, image_caption_value){
      $('#node-template .image_caption:last .field_image').attr('src', image_caption_value.field_image);
      $('#node-template .image_caption:last .caption').html(image_caption_value.caption);
      if(value.image_caption[image_caption_idx+1] !== undefined ) {
        $('#node-template .image_caption:last').after($('#node-template .image_caption:last').prop('outerHTML'));
      }
      //テンプレートをリセット
      //$('#node-template .image_caption:last').remove();
      //$('#node-template .image_caption:last .field_image').attr('src', '');
      //$('#node-template .image_caption:last .caption').html('');
      
    });
     console.log("ccc");
    var like_id = $('#node-template .good ons-icon').attr('id');
    $('#node-template .good ons-icon').attr('id', like_id + value.nid);
    var but_id = $('#node-template .but ons-icon').attr('id');
    $('#node-template .but ons-icon').attr('id', but_id + value.nid);
    var favorite_id = $('#node-template .favorite ons-icon').attr('id');
    $('#node-template .favorite ons-icon').attr('id', favorite_id + value.nid);


    
    var element = $('#node-template').prop('outerHTML');
    $('#node-area').append(element);
    $('#node-area #node-template').show();
    $('#node-template-wrapper #node-template').removeClass('node-'+value.nid);
    //$('#node-template-wrapper #node-template').attr('id','');
    $('#node-area #node-template:last').attr('id', value.nid);
    $('#node-template .tags-wrapper').show();
    $('#node-template .good ons-icon').attr('id', like_id);
    $('#node-template .but ons-icon').attr('id', but_id);
    $('#node-template .favorite ons-icon').attr('id', favorite_id);
    //$('#node-template .node-'+value.nid+'  comment_btn').attr('data-nid', value.nid);
    $('#node-template .comment-form').find('.user_name').attr('data-nid', value.nid);

    $.each(value.comment, function(index, comment_value){
      if(user.uid == value.uid || user.uid == comment_value.uid) {
        $('#node-template .comment-template .comment-delete').attr('data-cid', comment_value.cid);
      } else {
        $('#node-template .comment-template .comment-delete').hide();
        
      }
      $('#node-template .comment-template .picture').attr('src', comment_value.picture);
      $('#node-template .comment-template .user_name a').attr('data-user_body', comment_value.user_body);
      $('#node-template .comment-template .user_name a').html(comment_value.user_name);
      $('#node-template .comment-template .user_name a').attr('data-uid', comment_value.uid);
      $('#node-template .comment-template .body').html(comment_value.body);
      $('#node-template .comment-template .created').html(comment_value.created);
      $('#node-template .image_caption').each(function(index, element){
        if(index = 0) {
          $(this).find('.field_image').val();
          $(this).find('.caption').val();
        } else {
          //$(this).remove();
        }
      })


      var element = $('#node-template .comment-template').prop('outerHTML');
      $('#node-area .node-'+value.nid+' #comment-area').append(element);
      $('#node-area #comment-area .comment-template').removeClass('hide');
      $('#node-area #comment-area .comment-template:last').addClass('comment'+comment_value.cid);
      $('#node-area #comment-area .comment-template').removeClass('comment-template');
      
    });

    $('#node-template .image_caption:not(:first)').remove();
    //exec_ajax(api_root+"api/login","post", {"name":"saiyuuki","password":"saiyuuki3"}, login_callback);
  });
}

function user_callback(data) {
  user_list = data;
  //var arr = JSON.parse(data);
  //alert(arr.length);
  var i = 0;
  $('#user-area').html('');
  $.each(data, function(key, value) {
    if(i % 3 == 0) {
      $('#user-area').append('<ons-row class="user-row"></ons-row>');
    }
    $('#user_template img').attr('src', data[key].picture);
    $('#user-area .user-row:last').append($('#user_template').prop('outerHTML'));
    $('#user-area #user_template:last').attr('data-uid', value.uid).addClass('user');
    $('#user-area #user_template').attr('id', '');
    i++;
  });
  
  for (var j = i;j % 3 != 0;j++) {
    $('#user-area .user-row:last').append($('#user_template').prop('outerHTML'));
    $('#user-area .user-row:last #user_template').attr('id', '');
    $('#user-area .user-row:last ons-col:last img').hide();
  }
  
  /*
  var i =0;
  user_list = data;
  $.each(data, function(key, value) {
    alert(data[key].picture);
    $('#user_template img').attr('src', data[key].picture);
    $('#user-area ons-row:last').append($('#user_template').prop('outerHTML'));
    $('#user-area #user_template').attr('id', '');
    if(key%3 == 2 || key == data.length-1) {
      //$('#follow-area > .follow').wrap('<ons-row></ons-row>');
      //$('#follow-area .follow').unwrap();
      if(key%3 == 2) {
        $('#user-area').append('<ons-row class="user"></ons-row>');
      }
      if(i == data.length-1) {
        for (var j = data.length % 3; j < 3;j++) {
          $('#user-area ons-row:last').append($('#user_template').prop('outerHTML'));
        }
        $('#user-area .user:last').find('#user_template img').hide();
        $('#user-area .user:last').find('#').attr('id', '');
      }
    }
  });
  */

  /*
  $.each(data, function(key, value) {
    $('#user_template .user .picture').attr('src', value.picture);
    $('#user_template .user').attr('data-uid', value.uid);
    $('#user-area ons-row:last').append($('#user_template .user').prop('outerHTML'));
    if(key%3 == 2 || key == data.length-1) {
      if(key%3 == 2) {
        $('#user-area').append('<ons-row class="user"></ons-row>');
      }
      if(key == data.length-1) {
        for (var i = data.length % 3; i < 3;i++) {
          $('#user-area ons-row').append($('#user_template .user').prop('outerHTML'));
          //$('#user-area .user:last').find('.picture').hide();
          $('#user-area .user').find('#user_template').attr('id', '');
        }
        $('#user-area .user:last').find('.picture').hide();
        $('#user-area .user:last').find('#user_template').attr('id', '');
      }
    }
  });
  */
}

function activity_callback(data) {
  $.each(data.notification.activity, function(key, value) {
    $('#activity-template .activity').find('img').attr('src', value.picture);
    $('#activity-template .activity').find('.content').html(value.content);
    $('#activity-template .activity').find('.updated').html(value.updated);
    $('#your-notification #activity-area').append($('#activity-template .activity').prop('outerHTML'));
  });
  $.each(data.my_activity.activity, function(key, value) {
    $('#activity-template .activity').find('img').attr('src', value.picture);
    $('#activity-template .activity').find('.content').html(value.content);
    $('#activity-template .activity').find('.updated').html(value.updated);
    $('#my_activity #activity-area').append($('#activity-template .activity').prop('outerHTML'));
  });
  if(data.notification.new_activity != null && data.notification.new_activity.new_flg == 1) {
    $("onstab[page='notification.html'] .ion-ios-heart").addClass('like');
    $(".ion-ios-heart").addClass('like');
  }
  
  return false;
}

function set_login_info(data) {
  $('#follow').val(JSON.stringify(data.follow));
  $.each(data.good, function(key, value){
    $('#button-post-good-'+value.nid).addClass('like');
  });
  $.each(data.but, function(key, value){
    $('#button-post-but-'+value.nid).addClass('like');
  });
  $.each(data.favorite, function(key, value){
    $('#button-post-favorite-'+value.nid).addClass('like');
  });
  for (var i = 0;i < data.navigation.length;i++) {
    $('#navigation').append($('<option>').html(data.navigation[i].menu_name).val(data.navigation[i].menu_id));
  }

  for (var j = 0;j < data.tags.length;j++) {
    $('#tags').append($('<option>').html(data.tags[j].name).val(data.tags[j].tag_id));
  }
  $('#uid').val(data.uid);
  $('.comment-form').find('.picture').attr('src', data.picture);
  $('.comment-form').find('.user_name').html(data.user_name);
  
  //プロフィール画面
  $('#profile_user_name').html(data.user_name);
  $('#profile_user_picture').attr('src', data.picture);
  $('#profile_user_body').html(data.user_body);
  $('.profile_info_wrapper').find('.follow').hide();
  put_follow_area(data);

  //タグ管理
  $.each(data.tags, function(key, value){
    $('input[name="post_tag'+value.sort+'"]').val(value.name);
  });

  var menu = document.getElementById('menu');
  menu.load('menu.html').then();
  put_tags_menu(data);
} 

function sleep(waitMsec) {
  var startMsec = new Date();
 
  // 指定ミリ秒間だけループさせる（CPUは常にビジー状態）
  while (new Date() - startMsec < waitMsec);
}

function wait(fn_condition) {
  var startMsec = new Date();
 
  // 指定ミリ秒間だけループさせる（CPUは常にビジー状態）
  while (fn_condition() == false);
}

function set_view_user_data(data) {
    for (var i = 0;i < data.navigation.length;i++) {
      $('#navigation').append($('<option>').html(data.navigation[i].menu_name).val(data.navigation[i].menu_id));
    }
    $('#uid').val(data.uid);
    $('#profile_user_name').html(data.user_name);
    $('#profile_user_picture').attr('src', data.picture);
    $('#profile_user_body').html(data.user_body);  
    put_follow_area(data);
}

function show_node_list(event, params, callback) {
  //exec_ajax(api_root+"api/node_list/all/data?offset="+offset+"&limit="+limit,"post",{"uid":user.uid, "offset": offset, "limit": limit}, node_callback);
  exec_ajax(api_root+"api/node_list/all/data","post",{"uid":user.uid, "offset": offset, "limit": limit}, node_callback);
  $('#node-area').html('');
  var menu = document.getElementById('menu');
  menu.load().then();
  menu.close();
  return false;

}

function login_callback_after(data) {
    user = data;
    
    //exec_ajax(api_root+"api/node_list/all/data?offset="+offset+"&limit="+limit,"post",{"uid":user.uid}, node_callback, data, set_login_info);
    //alert('login_callback_after');
    
    exec_url = api_root+"api/node_list/all/data";
    condition = {"uid":user.uid, "offset": offset, "limit": limit};
    exec_ajax(exec_url,"post", condition, node_callback, data, set_login_info);
    //alert(exec_url);
    exec_ajax(api_root+"api/user_list/all","get",{}, user_callback);
    //alert(api_root+"api/user_list/all");
    set_view_user_data(data);
    //alert("set_view_user_data");
    //alert(user.uid);
    exec_ajax(api_root+"api/activity","get",{"uid":user.uid}, activity_callback);
}

function login_callback(data) {
  if(data.uid) {
    var content = document.getElementById('content');
    var menu = document.getElementById('menu');
    content.load('tab.html', login_callback_after(data));
    var wait_condition = function() {
      if($('#home-page'.length)) {
        return true;
      } else {
      return false;
      }
    }
    wait(wait_condition);
  } else {
    alert("ユーザーIDまたはパスワードが間違ってます。");
  }
}

function put_follow_area(data) {
  $('#follow-area').html(''); 
  for (var i = 0;i < data.follow.length;i++) {
    if(i % 3 == 0) {
      $('#follow-area').append('<ons-row class="follow-row"></ons-row>');
    }
    //$('#follow-area .user:last').data('uid', data.follow[i].follow_uid);
    $('#follow-template img').attr('src', data.follow[i].picture);
    
    $('#follow-area ons-row:last').append($('#follow-template').prop('outerHTML'));
    $('#follow-area ons-row:last #follow-template').attr('data-uid', data.follow[i].follow_uid);
    $('#follow-area ons-row:last #follow-template').attr('id', '');
    if(i == data.follow.length-1) {
      if(i == data.follow.length-1) {
        for (var j = (data.follow.length -1) % 3; j <2;j++) {
          $('#follow-area ons-row:last').append($('#follow-template').prop('outerHTML'));
        }
        $('#follow-area ons-row:last').find('#follow-template img').hide();
        $('#follow-area ons-row:last').find('#follow-template').attr('data-uid', data.follow[i].follow_uid);
        $('#follow-area ons-row:last').find('#follow-template').attr('id', '');
      }
    }
  }
}

function put_tags_menu(data) {
  if(!$('#menu_wrapper .tags_menu').length) {
    for (var i=0; i<data.tags.length;i++) {
      $('#menu_template_wrapper #menu_template').html(data.tags[i].name);
      $('#menu_wrapper #logout_menu').before($('#menu_template_wrapper #menu_template').prop('outerHTML'));
      $('#menu_wrapper .tags_menu:last').attr('data-tag_id', data.tags[i].tag_id);
      $('#menu_wrapper .tags_menu:last').attr('id', '');
    }
  }
  
}

function show_tags_menu(event) {
  var obj = document.getElementById("node-area");
  obj.scrollTop = obj.scrollHeight;
  $("html,body").css('height','auto');  ''
  $("html,body").animate({scrollTop:0});
  var tag_id = $(event.currentTarget).data('tag_id');
  if(tag_id === undefined) {
    tag_id = $(event.currentTarget).find('button').data('tag_id');
  }
  $('#node-area').html('');
  offset = 0;
  limit = 10;
  condition = {"uid":user.uid,"tag_id":tag_id, "offset":offset, "limit":limit};
  exec_url = api_root+"api/node_list/user/"+user.uid;
  exec_ajax(exec_url, "post", condition, node_callback,user,set_action);
  var menu = document.getElementById('menu');
  menu.load().then();
  menu.close();
  return false;
}

function login() {
 var name = $('#name').val();
 var password = $('#password').val();
   exec_ajax(api_root+"api/login","post", {"name": name,"password": password}, login_callback);
}

function change_body_file_callback(data) {
  var text = $('#post_body').val();
  $('#post_body').val(text+"<img src='"+data.file_url+"' />");
  return false;
}

function edit_node_callback(data) {
  //daraが[0]がついて返ってくる　原因不明
  $('#postModal').modal('show');
  $('[name=post_title]').val(data[0].title);
  $('[name=post_body]').val(data[0].body_value);
  $('[name=public_scope]').val(data[0].public_scope);
  $('[name=navigation]').val(data[0].page_menu);
  $('[name=tags]').val(data[0].tag_id);
  $('[name=edit_nid]').val(data[0].nid);
}

function upload_callback(data) {
  $('.loding-area').hide();
  tabbar = document.querySelector("ons-tabbar");
  tabbar.setActiveTab(0);
  if(data.post_type !== "regist") { 
    alert("編集が完了しました。");
  } else {
    alert("登録が完了しました。");
  }
  $('#node-area').html('');
  exec_ajax(api_root+"api/node_list/all/data","post",{"uid":user.uid}, node_callback);
}

function upload_file(form) {
  $('#body_file').click();
  return false;
}

function post_menu(event) {
    //$('.post-view').click();
    var tabbar = document.querySelector("ons-tabbar");
    tabbar.setActiveTab(2);
    edit_node(event);
    return false;
}

function post_delete(event) {
  var nid = $(event.currentTarget).data('nid');
  swal({
		title: '本当に削除してもいいですか？',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "削除する",
		cancelButtonText: "キャンセル",
		closeOnConfirm: true,
		html: true
  }, function(isConfirm) {
    if(isConfirm) {
      exec_ajax(api_root+"api/node_delete","post", {"nid": nid}, post_delete_callback);
      $(document).find('.node-'+nid).ready(function() {
        $('.node-'+nid).remove();
      });
      return false;
    }
  });

  return false;
    return false;
}

function scrollDirection(event) {
  scrollPoint = $(window).scrollTop();
  return false;
}


function show_my_site(event) {
  window.open('http://myportal.tokyo/saiyuuki', '_blank', 'location=yes'); 
  return false;
}


function touchstart(event) {
  scroll_start_x = event.originalEvent.touches[0].pageX;
  scroll_start_y = event.originalEvent.touches[0].pageY;
}

var newScrollPoint = 0; // 現在のスクロール位置
var scrollPoint = 0; // 現在のスクロール位置との比較用

function touchmove(event) {
  var scroll_end_x = event.originalEvent.touches[0].pageX;
  var scroll_end_y = event.originalEvent.touches[0].pageY;
  $(this).scrollTop($(this).scrollTop() - (scroll_end_y - scroll_start_y));
  if($(this).scrollTop() - (scroll_end_y - scroll_start_y) < 0) {
    $('.loding-area3 img').show();
    if(next) {
      if(!exec_flg) {
        condition.limit = limit;
        condition.offset = offset;
        exec_ajax(exec_url,"post", condition, node_callback);
      }
    }
    setTimeout(function(){
      $('.loding-area3 img').hide();
    },1000);
  }
  var t = $('#area-end').offset().top;
  var p = t - $(window).height();
  if(p < 0 && next) {
    if(!exec_flg) {
    $('.loding-end img').show();   
    condition.limit = limit;
    condition.offset = offset;
    console.log(exec_url);
    console.log(condition);

    exec_ajax(exec_url,"post", condition, node_callback);
    setTimeout(function(){
      $('.loding-end img').hide();
    },1000);
    }
  }


    
}

function good_callback(data) {
  //var data = JSON.parse(data);
  $('.loding-area').hide();
  if(data.type == 'good') {
    document.getElementById("button-post-good-"+data.nid).classList.remove("ion-thumbsup");
    document.getElementById("button-post-good-"+data.nid).classList.add("ion-thumbsup","like");
    swal({
      title: "Good!",
      text: "いいねしました。",
      imageUrl: 'img/thumbs-up.jpg'
    });
    user.good.push({"nid":data.nid,"uid":user.uid});
  } else {
    document.getElementById("button-post-good-"+data.nid).classList.remove("ion-thumbsup","like");
    document.getElementById("button-post-good-"+data.nid).classList.add("ion-thumbsup");
    swal("Cancelled", "いいねを取り消しました。", "error");
    user.good.some(function(value,index) {
      if(value.nid = data.nid) {
        user.good.splice(index,1);
      }
    });
  }
}
function good(event) {
  var nid = $(event.currentTarget).parents('.post').find('.post_menu').data('nid');
  var nid = $(event.currentTarget).parents('.node-wrapper').attr('id');
  var uid = user.uid;

  exec_ajax(api_root+"api/good","post",{"nid":nid,"uid":uid, "title":node[nid].title, "author_uid":node[nid].uid,"user_name":user.user_name}, good_callback);
  return false;
}

function but_callback(data) {
  $('.loding-area').hide();
  if(data.type == 'ungood') {
    document.getElementById("button-post-but-"+data.nid).classList.remove("ion-thumbsdown");
    document.getElementById("button-post-but-"+data.nid).classList.add("ion-thumbsdown","like");
    swal({
      title: "But!",
      text: "だめねしました。",
      imageUrl: 'img/thumbs-but.jpg'
    });
    user.but.push({"nid":data.nid,"uid":user.uid});
  } else {
    document.getElementById("button-post-but-"+data.nid).classList.remove("ion-thumbsdown","like");
    document.getElementById("button-post-but-"+data.nid).classList.add("ion-thumbsdown");
    swal("Cancelled", "だめねを取り消しました。", "error");
    user.but.some(function(value,index) {
      if(value.nid = data.nid) {
        user.but.splice(index,1);
      }
    });
  }
}

function but(event) {
  var nid = $(event.currentTarget).parents('.node-wrapper').attr('id');
  var uid = user.uid;
  exec_ajax(api_root+"api/ungood","post",{"nid":nid,"uid":uid, "title":node[nid].title, "author_uid":node[nid].uid,"user_name":user.user_name}, but_callback);
  return false;
}

function favorite_callback(data) {
  $('.loding-area').hide();
  if(data.type == 'favorite') {
    document.getElementById("button-post-favorite-"+data.nid).classList.remove("ion-bookmark");
    document.getElementById("button-post-favorite-"+data.nid).classList.add("ion-bookmark","like");
    swal({
      title: "Favorite!",
      text: "お気に入りに入れました。",
      imageUrl: 'img/favorite.jpg'
    });
    user.favorite.push({"nid":data.nid,"uid":user.uid});
  } else {
    document.getElementById("button-post-favorite-"+data.nid).classList.remove("ion-bookmark","like");
    document.getElementById("button-post-favorite-"+data.nid).classList.add("ion-bookmark");
    swal("Cancelled", "お気に入りから外しました。", "error");
    user.favorite.some(function(value,index) {
      if(value.nid = data.nid) {
        user.favorite.splice(index,1);
      }
    });
  }
}

function favorite(event) {
  var nid = $(event.currentTarget).parents('.node-wrapper').attr('id');
  var uid = user.uid;
  exec_ajax(api_root+"api/favorite_node","post",{"nid":nid,"uid":uid, "title":node[nid].title, "author_uid":node[nid].uid,"user_name":user.user_name}, favorite_callback);
  return false;
}

function follow_callback(data) {
  $('.loding-area').hide();
  if(data.type == 'follow') {
    swal({
      title: "Follow",
      text: "フォローしました。",
      imageUrl: 'img/follow.jpg'
    });
    $('.follow').addClass('active');
  } else {
    swal("Cancelled", "フォローを外しました。", "error");
    $('.follow').removeClass('active');
  }
}

function follow(event) {
  var uid = user.uid;
  var follow_uid = $(event.currentTarget).data('follow_uid');
  exec_ajax(api_root+"api/follow",
    "post",
    {"uid":uid,"follow_uid":follow_uid},
    follow_callback
  );
  return false;
}

function profile_callback(data) {
  $('#profile_user_name').html(data.user_name);
  $('#profile_user_body').html(data.user_body);
  $('#profile_user_picture').attr('src', data.picture);
  if(user.uid != data.uid) {
    $('.profile_info_wrapper').find('.follow').show();
    $('.profile_info_wrapper').find('.follow').attr('data-follow_uid', data.uid);
  } else {
    $('.profile_info_wrapper').find('.follow').hide();
  }
  if(data.is_follow) {
    $('#follow_btn').addClass('active');
  } else {
    $('#follow_btn').removeClass('active');
  }
  
  
  put_follow_area(data);
}

function show_ac_user_profile(event) {
  $('#goodModal').modal('hide');
  var tabbar = document.querySelector("ons-tabbar");
  tabbar.setActiveTab(4);
  var uid = $(event.currentTarget).data('uid');
  var picture = $(event.currentTarget).parents('.activity').find('img').attr('src');
  $('#profile_user_picture').attr('src', picture);
  $('#follow_btn').attr('data-uid', uid);
  exec_ajax(api_root + "api/user/"+uid, "post", {"uid":user.uid}, profile_callback);
  return false;
}

function show_user_profile(event) {
  //var menu = document.getElementById('menu');
  //menu.load('profile.html').then();
  var tabbar = document.querySelector("ons-tabbar");
  tabbar.setActiveTab(4);
  var picture = $(this).closest('.node-wrapper,.comment').find('.picture').attr('src');
  var user_name = $(this).find('a').html();
  var uid = $(this).find('a').data('uid');
  var user_body = $(this).find('a').data('user_body');
  var data = {"picture":picture,"user_name":user_name,"uid":uid,"user_body":user_body};
  $(document).on('postopen', '#menu', data, post_load);

  var wait_condition = function() {
    if($('#menu .profile').length) {
      return true;
    } else {
      return false;
    }
  }
  
  $('#profile_user_picture').attr('src', picture);
  $('#profile_user_name').html($(this).find('a').html());
  $('#profile_user_body').html($(this).find('a').data('user_body'));
  $('.profile_info_wrapper').find('.follow').show();
  $('.profile_info_wrapper').find('.follow').attr('data-follow_uid', uid);
  if(user.uid != uid) {
    $('#edit_profile_button').hide();
  } else {
    $('#edit_profile_button').show();
  }
   exec_ajax(api_root + "api/user/"+uid, "post", {"uid":user.uid}, profile_callback);

  //menu.open();

  return false;
}

function show_home_menu(event) {
  var menu = document.getElementById('menu');
  put_tags_menu(user);
  menu.load().then();
  menu.open();
  
  return false;
}

function post_load(event) {
  var follow =JSON.parse($('#follow').val());
  var follow_uids = [];
  $.each(follow, function(key, value) {
    follow_uids.push(value.follow_uid);
  });
  $('#profile_user_name').html(event.data.user_name);
  $('#profile_user_picture').attr('src', event.data.picture);
  $('#profile_user_body').html(event.data.user_body);
  if(follow_uids.indexOf(String(event.data.uid)) >= 0) {
    $('.follow').addClass("active");
  }
  $('.follow').attr('data-follow_uid', event.data.uid);
  return false;
}

function comment_callback(data) {
  var picture = $('.node-'+data.nid+' .comment-form .picture').attr('src');
  var user_name = $('.node-'+data.nid+' .comment-form .user_name').html();
  var body = data.comment;
  //var now = new Date();
  //var Year = now.getFullYear();
  //var Month = now.getMonth()+1;
  //var Date = now.getDate();
  //var Hour = now.getHours();
  //var Min = now.getMinutes();
  //var Sec = now.getSeconds();
  $('.comment-template .picture').attr('src', picture);
  $('.comment-template .body').html(data.comment);
  $('.comment-template .user_name a').html(user_name);
  $('.comment-template .user_name').data(data.uid);
  //$('.comment-template .created').html(Year + "/" + Month + "/" + Date + "/" + Hour + ":" + Min);
  var element = $('.comment-template').prop('outerHTML');
  $('.node-'+data.nid+' #comment-area').append(element);
  $('#comment-area .comment-template:last').addClass('comment'+data.cid);
  $('#comment-area .comment-template:last').find('.comment-delete').attr('data-cid',data.cid);
  $('#comment-area .comment-template').removeClass('hide');
  $('.node-'+data.nid+' .comment-form .comment_body').val('');

  
  return false;
}

function comment_delete_callback(data) {
  return false;
} 

function post_delete_callback(data) {
  return false;
} 

function comment_delete(event) {
  var cid = $(event.currentTarget).data('cid');
  swal({
		title: '本当に削除してもいいですか？',
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: "削除する",
		cancelButtonText: "キャンセル",
		closeOnConfirm: true,
		html: true
  }, function(isConfirm) {
    if(isConfirm) {
      exec_ajax(api_root+"api/comment_delete","post", {"cid": cid}, comment_delete_callback);
      $(document).find('.comment'+cid).ready(function() {
        $('.comment'+cid).remove();
      });
      return false;
    }
  });

  return false;
}

function comment(event) {
  var nid = $(event.currentTarget).parents('.node-wrapper').attr('id');
  var comment_body = $(event.currentTarget).parents('.comment-form').find('.comment_body').val();
  var user_name = $(event.currentTarget).parents('.comment-form').find('.user_name').html();
  var picture = $(event.currentTarget).parents('.comment-form').find('.picture').attr('src');
  var uid = $('#uid').val();
  
  exec_ajax(api_root+"api/post_comment","post", {"author_uid":node[nid].uid,"comment": comment_body,"uid": uid,"nid":nid,"title":node[nid].title, "user_name":user_list[uid].user_name}, comment_callback);

  return false;
}

function search_callback(data) {
  user_list = data;
  return false;
}

function search(event) {
  if ( event.keyCode === 13 || ( event.keyCode === 13 && (event.shiftKey === true || event.ctrlKey === true || event.altKey === true) )) { 
    var word = $('.search_box').val();
    offset = 0;
    limit = 10;
    if(word.length) {
      condition = {"uid":user.uid, "word":word, "offset":offset, "limit":limit};
      $('#node-area').html('');
      exec_url = api_root+"api/node_search/all/data";
      exec_ajax(exec_url, "post", condition, node_callback);
    } else {
      condition = {"uid":user.uid, "offset":offset, "limit":limit};
      $('#node-area').html('');
      exec_url = api_root+"api/node_list/all/data";
      exec_ajax(exec_url, "post", condition, node_callback);
    }
    return false;
  }
}

function set_action(data) {
  $.each(data.good, function(key, value){
    $('#button-post-good-'+value.nid).addClass('like');
  });
  $.each(data.but, function(key, value){
    $('#button-post-but-'+value.nid).addClass('like');
  });
  $.each(data.favorite, function(key, value){
    $('#button-post-favorite-'+value.nid).addClass('like');
  });
}

function user_node(event) {
  var uid = $(event.currentTarget).data('uid');
  var tabbar = document.querySelector("ons-tabbar");
  tabbar.setActiveTab(0);
  $('#node-area').html('');
  exec_url = api_root+"api/node_list/user/"+uid;
  condition = {"uid":user.uid};
  offset = 0;
  condition.offset = offset;
  condition.limit = limit;
  exec_ajax(exec_url, "post", condition, node_callback,user,set_action);

  return false;
}

function show_activity(event) {
    var href = $(this).attr('href');
    //2回なぜかイベントが発生するため条件を入れる
    if(href !== undefined) {
      var nid = href.replace(/[^0-9]/g, '');
      tabbar.setActiveTab(0);
      $('#node-area').html('');
      var reset_offset = function() {
        offset = 0;
        exec_url = api_root+"api/node_list/all/data/";
        condition = {"uid":user.uid};
      };
      exec_ajax(api_root+"api/node_list/node/"+nid,"post",{"uid":user.uid,"offset":0,"limit":1}, node_callback, null, reset_offset);
    }
}

function read_activity_callback(data) {
  if(data != false) {
    $(".ion-ios-heart").removeClass('like');
  }
}




function open_activity_view() {
  var params = {"uid":user.uid};
  exec_ajax(api_root + 'api/read_activity', 'post', params, read_activity_callback);
  return false;
}

function add_tags_callback(data) {
alert("タグの保存が完了しました");
var tags_select = $('#tags').children().remove();
$('#tags').append($('<option disabled selected>').html("投稿するタグを選択してください"));
$.each(data.tags, function(key, value){
    $('#tags').append($('<option>').html(data.tags[key].name).val(data.tags[key].tag_id));
});
$.each(data.tag_names, function(key, value){
    $('#post_tag'+key+1).val(value);
});
tabbar.setActiveTab(0);
return false;
}

function add_tags() {
  $('#add_tag_uid').val(user.uid);
  exec_form_ajax('#tag-form', api_root + 'api/add_tags', add_tags_callback);
  return false;
}

function show_edit_profile() {
  $('#userModal').modal('show');
  $('#userModal .modal-title').html('ユーザー情報編集');
  $('#userModal .uid').val(user.uid);
  $('#userModal .user_body').val(user.user_body);
  $('#userModal .user_name').val(user.user_name);
  $('#userModal .name').val(user.name);
  $('#userModal .name').attr('readonly',true);
  $('#userModal .mail').val(user.mail);
  $('#userModal .edit_uid').val(user.uid);
  return false;
}

function show_regist_profile() {
  $('#userModal').modal('show');
  return false;
}

function show_good_user_callback(data) {
  var $modal = $('#goodModal');
  if(data.type == 'good') {
    $modal.find('.modal-title').html('いいねと言ってる人');
  } else {
    $modal.find('.modal-title').html('だめねと言ってる人');
  }
  $modal.find('#insert-area').html('');
  $('#goodModal').modal('show');
  $.each(data.users, function(key, value){
    $modal.find('#list-template .ac_user_name').html(value.user_name);
    $modal.find('#list-template .ac_user_name').attr('data-uid', value.uid);
    $modal.find('#list-template .picture').attr('src', value.picture);
    $modal.find('#insert-area').append($modal.find('#list-template').prop('outerHTML'));
    $modal.find('#insert-area #list-template').attr('id', '');
    $modal.find('#insert-area .list-wrapper').show();
  });
  
  return false;
}

function show_good_user(event) {
  var nid = $(event.currentTarget).data('nid');
  exec_ajax(api_root+"api/good_user","post", {"nid": nid}, show_good_user_callback);
  
  return false;
}

function show_but_user_callback(data) {

  return false;
}

function show_but_user(event) {
  var nid = $(event.currentTarget).data('nid');
  exec_ajax(api_root+"api/ungood_user","post", {"nid": nid}, show_good_user_callback);
  return false;
}

function touchHandler(e){
  e.preventDefault();
  if(e.type == "touchstart"){
    alert("そのまま横にフリックしてください。");
    }
  if(e.type == "touchmove"){
    alert("あ、いい感じです。"); 
    }
  if(e.type == "touchend"){
    alert("フリック終了ぅ〜！");  
    }
}

function add_image_caption_form(event) {
  
  var idx = $(event.currentTarget).data('idx') + 1;
  if(idx <= 10) {
    $('#image_caption_wrapper'+idx).show();
    //$('#camera-page .image_caption_form:last').after($('#camera-page .image_caption_form:last').prop('outerHTML'));
    //$('#camera-page .image_caption_form:last .field_image_form').attr('name', 'field_image'+idx);
    //$('#camera-page .image_caption_form:last .caption').attr('name', 'caption'+idx);
    //$('#camera-page .image_caption_form:last .field_image_form').val('');
    //$('#camera-page .image_caption_form:last .caption').val('');
  }
  
  return false;
}

function show_post_view(event) {
  $('[name=post_title]').val('');
  $('[name=post_body]').val('');
  $('[name=public_scope]').val('');
  $('[name=navigation]').val('');
  $('[name=tags]').val('');
  $('[name=edit_nid]').val('');
  for(var i=1;i<=8;i++) {
    $('[name="caption'+i+'"]').val('');
    $('[name="field_image'+i+'"]').val('');
    if(i == 1) {
      $('#image_caption_wrapper'+i).show();
    } else {
      $('#image_caption_wrapper'+i).hide();
    }
  }

  return false;
}
function show_my_profile(event) {
  $('#edit_profile_button').show();
  $('#profile_user_name').html(user.user_name);
  $('#profile_user_picture').attr('src', user.picture);
  $('#profile_user_body').html(user.user_body);  
  put_follow_area(user);
}

$(function() {
   $(document).on('click', '#login', login);
   $(document).on('click', '#header-home-link', show_my_site);
   $(document).on('click', '.user_name', show_user_profile);
   $(document).on('click', '.ac_user_name', show_ac_user_profile);
   $(document).on('click', '.good', good);
   $(document).on('click', '.but', but);
   $(document).on('click', '.favorite', favorite);
   $(document).on('click', '.follow', follow);
   $(document).on('click','.comment-delete', comment_delete)
   $(document).on('click', '.comment_btn', comment);
   $(document).on('keypress', '.search_box', search);
   $(document).on('click', '.user', user_node);
   $(document).on('click', '.activity', show_activity);
   $(document).on('click', '.activity-view', open_activity_view);
   $(document).on('click', '#addtag_btn', add_tags);
   $(document).on('click', '#edit_profile_button', show_edit_profile);
   $(document).on('click', '#regist_profile_button', show_regist_profile);
   $(document).on('click', '.tags_menu', show_tags_menu);
   $('body').on({'touchstart': touchstart,'touchmove': touchmove,});
   $(document).on('click', '.post_menu', post_menu);
   $(document).on('click', '.post_delete', post_delete);
   $(document).on('click', '.good_user', show_good_user);
   $(document).on('click', '.but_user', show_but_user);
   $(document).on('click', '.post-view', show_post_view);
   $(document).on('change', '.field_image_form', add_image_caption_form);
   $(document).on('click', '.profile-view', show_my_profile);
   //$(document).on('touchstart', '.touchBox', touchHandler);
   //$(document).on('touchmove', '.touchBox', touchHandler);
   //$(document).on('touchend', '.touchBox', touchHandler);
   //var box = $(document).find(".touchBox")[0];
   //box.addEventListener("touchstart", touchHandler, false);
  //$(document).on('click', '.add_image_caption', show_image_caption);


window.addEventListener("load", function(event) {
    var touchStartX;
    var touchStartY;
    var touchMoveX;
    var touchMoveY;
 
    // 開始時
    window.addEventListener("touchstart", function(event) {
    event.preventDefault();
    // 座標の取得
    touchStartX = event.touches[0].pageX;
    touchStartY = event.touches[0].pageY;
    }, false);
 
    // 移動時
    window.addEventListener("touchmove", function(event) {
    event.preventDefault();
    // 座標の取得
    touchMoveX = event.changedTouches[0].pageX;
    touchMoveY = event.changedTouches[0].pageY;
    }, false);
 
    // 終了時
    window.addEventListener("touchend", function(event) {
    // 移動量の判定
    if (touchStartX > touchMoveX) {
        if (touchStartX > (touchMoveX + 50)) {
        //alert("右から左に指が移動した場合");
        }
    } else if (touchStartX < touchMoveX) {
        if ((touchStartX + 50) < touchMoveX) {
        //alert("左から右に指が移動した場合");
        }
    }
    }, false);
}, false);

var prev = function() {
  var carousel = document.getElementById('carousel');
  carousel.prev();
};

var next = function() {
  var carousel = document.getElementById('carousel');
  carousel.next();
};

ons.ready(function() {
  var carousel = document.addEventListener('postchange', function(event) {

  });
});

});

function edit_node(event) {
  var nid = $(event.currentTarget).data('nid');
  var params = {"post_nid":nid};
  exec_ajax(api_root + 'post/edit', 'post', params, edit_node_callback);
  return false;
}

function exec_ajax(url, ajax_type, data, callback, param =null, param_callback = null) {
  $('.loding-area3 img').show();
//  if(!exec_flg) {
  $.ajax(
    url,
    {
      type: ajax_type,
      dataType: "json",
      data: data,
      success: function(data) {
        exec_flg = false;
        $('.loding-area3 img').hide();
        callback(data);
        if(param_callback != null) {
          param_callback(param);
        }
        return data;
      },
      error: function(data) {
        alert("error");
        alert(url);
        return false;
      }
    }
  );
  exec_flg = true;
}

function change_body_file() {
  exec_form_ajax('#upload-form', api_root + 'post/upload_file',change_body_file_callback);
  return false;
}

function upload(form) {
  $('#upload-form').find('#uid').val(user.uid);
  exec_form_ajax('#upload-form', api_root+'api/post',upload_callback);
  return false;
}

function exec_form_ajax(selector, url, callback) {
  $form = $(selector);
  fd = new FormData($form[0]);
  $.ajax(
      url,
      {
      type: 'post',
      processData: false,
      contentType: false,
      data: fd,
      dataType: "json",
      success: function(data) {
        callback(data);
        return data;
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        $('.loding-area').hide();
        if(XMLHttpRequest.status != 200) {
          alert('登録内容を見直してください。');
          console.log("ajax通信に失敗しました");
          console.log("XMLHttpRequest : " + XMLHttpRequest.status);
          console.log("textStatus     : " + textStatus);
          console.log("errorThrown    : " + errorThrown.message);
          return false;
        } else {
          callback(fd);
          return false;
        }
      }
  });
}

/*

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

        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          alert('error');
          $('.loding-area').hide();
          location.href="/";
        }
    });
  return false;
}

*/

/*

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
*/



/*
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

*/