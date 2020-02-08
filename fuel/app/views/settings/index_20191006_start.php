<div class="profile-header text-center" style="background-image: url(../assets/img/iceland.jpg);">
  <div class="container">
    <div class="container-inner">
      <img class="img-circle media-object" src="<?php echo $image[$user['picture']];?>">
      <h3 class="profile-header-user">
        <?php echo $user["user_name"];?>
      </h3>
      <p class="profile-header-bio">
        <?php echo $user["user_body"];?>
      </p>
    </div>
  </div>

  <nav class="profile-header-nav">
    <ul id="settings_tabs" class="nav nav-tabs">
      <li class="active">
        <a class="menu" href="#" data-form="basic">Basic</a>
      </li>
      <?php foreach($user["my_navigation"] as $navigation):?>
      <li>
        <a class="menu" href="#" data-form="<?php echo $navigation["delta"];?>">
          <?php echo $navigation["name"];?>
        </a>
      </li>
      <?php endforeach;?>
      <li>
        <a class="menu" href="#" data-form="other">Other</a>
      </li>
    </ul>
  </nav>
</div>

<div class="container m-y-md">
  <div id="basic-form" style="width:100%;">
    <div class="panel panel-default">
      <div class="modal-header">
        <h4 class="modal-title">Basic</h4>
      </div>


      <div class="modal-body p-a-0">
        <div class="">
          <div style="margin:20px;">
            <?php if(strlen($message) >= 1):?>
            <div class="alert alert-success" role="alert">
              <?php echo $message;?>
            </div>
            <?php endif;?>
          </div>
          <ul class="media-list media-list-users list-group">
            <form id="user_regist-form" method="post" enctype="multipart/form-data">
              <li class="list-group-item">
                <div class="media">
                  サイト名：<input type="text" name="user_site_name" placeholder="サイト名" style="width: 100%;" class="form-control" data-action="grow" value="<?php echo isset($default_value['user_site_name']) ? $default_value['user_site_name'] : '';?>">
                </div>
              </li>
              </li>
              <li class="list-group-item">
                <div class="media">
                  <?php if(isset($result) && isset($result["upload_result"]) && isset($result["upload_result"]["field_user_site_logo"])  && isset($result["upload_result"]["field_user_site_logo"]["file_url"])):?>
                  <div>
                    <img src="<?php echo $result[" upload_result "]["field_user_site_logo "]["file_url "];?>" width="100" height="100" />
                  </div>
                  <?php endif;?>
                  <?php if(isset($user["user_site_logo"])):?>
                  <img src="<?php echo $image[$user['user_site_logo']];?>" width="200" height="200" />
                  <?php endif;?><br> サイトロゴ：
                  <input type="file" name="user_site_logo">

                </div>
              </li>
              <li class="list-group-item">
                <div class="media">
                  ナビゲーション1：<input type="text" name="nav_name1" placeholder="ナビゲーション1" style="width: 100%;" class="form-control" data-action="grow" value="<?php echo isset($default_value['my_navigation'][1]) ? $default_value['my_navigation'][1]['name'] : '';?>">
                </div>
                <div class="media">
                  タイプ：<br />
                  <select name="nav_type1">
                  <option value="page" <?php echo isset($default_value['my_navigation'][1]) && $default_value['my_navigation'][1]["type"] == 'page' ? 'selected' : '';?>>ページ</option>
                  <option value="content" <?php echo isset($default_value['my_navigation'][1]) && $default_value['my_navigation'][1]["type"] == 'content' ? 'selected' : '';?>>コンテンツ</option>
                </select>
                </div>
                <div class="media">
                  タグ：<br />
                  <?php foreach($default_value["my_tags"] as $tag_id => $value):?>
                  <input type="checkbox" name="nav_tags1[]" value="<?php echo $tag_id;?>">
                  <?php echo $value["name"];?>
                  <?php endforeach;?>
                </div>
              </li>
              <li class="list-group-item">
                <div class="media">
                  ナビゲーション2：<input type="text" name="nav_name2" placeholder="ナビゲーション2" style="width: 100%;" class="form-control" data-action="grow" value="<?php echo isset($default_value['my_navigation'][2]) ? $default_value['my_navigation'][2]['name'] : '';?>">
                </div>
                <div class="media">
                  タイプ：<br />
                  <select name="nav_type2">
                  <option value="page" <?php echo isset($default_value['my_navigation'][2]) && $default_value['my_navigation'][2]["type"] == 'page' ? 'selected' : '';?>>ページ</option>
                  <option value="content" <?php echo isset($default_value['my_navigation'][2]) && $default_value['my_navigation'][2]["type"] == 'content' ? 'selected' : '';?>>コンテンツ</option>
                </select>
                </div>
                <div class="media">
                  タグ：<br />
                  <?php foreach($default_value["my_tags"] as $tag_id => $value):?>
                  <input type="checkbox" name="nav_tags2[]" value="<?php echo $tag_id;?>">
                  <?php echo $value["name"];?>
                  <?php endforeach;?>
                </div>
              </li>
              <li class="list-group-item">
                <div class="media">
                  ナビゲーション3：<input type="text" name="nav_name3" placeholder="ナビゲーション3" style="width: 100%;" class="form-control" data-action="grow" value="<?php echo isset($default_value['my_navigation'][3]) ? $default_value['my_navigation'][3]['name'] : '';?>">
                </div>
                <div class="media">
                  タイプ：<br />
                  <select name="nav_type3">
                  <option value="page" <?php echo isset($default_value['my_navigation'][3]) && $default_value['my_navigation'][3]["type"] == 'page' ? 'selected' : '';?>>ページ</option>
                  <option value="content" <?php echo isset($default_value['my_navigation'][3]) && $default_value['my_navigation'][3]["type"] == 'content' ? 'selected' : '';?>>コンテンツ</option>
                </select>
                </div>
                <div class="media">
                  タグ：<br />
                  <?php foreach($default_value["my_tags"] as $tag_id => $value):?>
                  <input type="checkbox" name="nav_tags3[]" value="<?php echo $tag_id;?>">
                  <?php echo $value["name"];?>
                  <?php endforeach;?>
                </div>
              </li>
              <li class="list-group-item">
                <div class="media">
                  ナビゲーション4：<input type="text" name="nav_name4" placeholder="ナビゲーション4" style="width: 100%;" class="form-control" data-action="grow" value="<?php echo isset($default_value['my_navigation'][4]) ? $default_value['my_navigation'][4]['name'] : '';?>">
                </div>
                <div class="media">
                  タイプ：<br />
                  <select name="nav_type4">
                  <option value="page" <?php echo isset($default_value['my_navigation'][4]) && $default_value['my_navigation'][4]["type"] == 'page' ? 'selected' : '';?>>ページ</option>
                  <option value="content" <?php echo isset($default_value['my_navigation'][4]) && $default_value['my_navigation'][4]["type"] == 'content' ? 'selected' : '';?>>コンテンツ</option>
                </select>
                </div>
                <div class="media">
                  タグ：<br />
                  <?php foreach($default_value["my_tags"] as $tag_id => $value):?>
                  <input type="checkbox" name="nav_tags4[]" value="<?php echo $tag_id;?>">
                  <?php echo $value["name"];?>
                  <?php endforeach;?>
                </div>
              </li>
              <li class="list-group-item">
                <div class="media">
                  ナビゲーション5：<input type="text" name="nav_name5" placeholder="ナビゲーション5" style="width: 100%;" class="form-control" data-action="grow" value="<?php echo isset($default_value['my_navigation'][5]) ? $default_value['my_navigation'][5]['name'] : '';?>">
                </div>
                <div class="media">
                  タイプ：<br />
                  <select name="nav_type5">
                  <option value="page" <?php echo isset($default_value['my_navigation'][5]) && $default_value['my_navigation'][5]["type"] == 'page' ? 'selected' : '';?>>ページ</option>
                  <option value="content" <?php echo isset($default_value['my_navigation'][5]) && $default_value['my_navigation'][5]["type"] == 'content' ? 'selected' : '';?>>コンテンツ</option>
                </select>
                </div>
                <div class="media">
                  タグ：<br />
                  <?php foreach($default_value["my_tags"] as $tag_id => $value):?>
                  <input type="checkbox" name="nav_tags5[]" value="<?php echo $tag_id;?>">
                  <?php echo $value["name"];?>
                  <?php endforeach;?>
                </div>
              </li>
              <li class="list-group-item">
                <div class="media">
                  <div class="media-body">
                    <input type="submit" class="btn btn-default btn-sm pull-right" value="保存">
                  </div>
                </div>
              </li>
              <input type="hidden" id="edit_uid" name="edit_uid">
              <input type="hidden" name="form_type" value="basic">
            </form>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div id="home-form" style="width:100%;">
    <div class="panel panel-default" style="display:none;">
      <div class="modal-header">
        <h4 class="modal-title">Home</h4>
      </div>

      <div class="modal-body p-a-0">
        <div class="">
          <ul class="media-list media-list-users list-group">
            <form id="user_regist-form" method="post" enctype="multipart/form-data">
              <li class="list-group-item">
                <div class="media">
                  <?php if(isset($result) && isset($result["upload_result"]) && isset($result["upload_result"]["field_user_site_main_image1"])  && isset($result["upload_result"]["field_user_site_main_image1"]["file_url"])):?>
                  <div>
                    <img src="<?php echo $result[" upload_result "]["field_user_site_main_image1 "]["file_url "];?>" width="100" height="100" />
                  </div>
                  <?php endif;?>
                  <?php if(isset($user["user_site_main_image1"])):?>
                  <img src="<?php echo $image[$user['user_site_main_image1']];?>" width="200" height="200" />
                  <?php endif;?><br> メイン画像：
                  <input type="file" name="user_site_main_image1">
                </div>
              </li>
              <li class="list-group-item">
                <div class="media">
                  <?php if(isset($result) && isset($result["upload_result"]) && isset($result["upload_result"]["field_user_site_top_image"])  && isset($result["upload_result"]["field_user_site_top_image1"]["file_url"])):?>
                  <div>
                    <img src="<?php echo $result[" upload_result "]["field_user_site_top_image "]["file_url "];?>" width="100" height="100" />
                  </div>
                  <?php endif;?>
                  <?php if(isset($user["user_site_top_image"])):?>
                  <img src="<?php echo $image[$user['user_site_top_image']];?>" width="200" height="200" />
                  <?php endif;?><br> トップ画像：
                  <input type="file" name="user_site_top_image">
                </div>
              </li>
              </li>
              <input type="hidden" name="form_type" value="home">
              <li class="list-group-item">
                <div class="media">
                  <div class="media-body">
                    <input type="submit" class="btn btn-default btn-sm pull-right" value="保存">
                  </div>
                </div>
              </li>
            </form>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <?php foreach($user["my_navigation"] as $nav):?>

  <div id="<?php echo $nav["delta"];?>-form" style="width:100%;">
    <div class="panel panel-default" style="display:none;">
      <div class="modal-header">
        <h4 class="modal-title">
          <?php echo $nav["name"];?>
        </h4>
      </div>
      <?php if($nav["type"] == "content"):?>
      <div class="modal-body p-a-0">
        <div class="">
          <ul class="media-list media-list-users list-group">
            <form id="user_regist-form" method="post" enctype="multipart/form-data">
              <li class="list-group-item">
                <div class="media">
                  タグ：<br />
                  <?php foreach($default_value["my_tags"] as $tag_id => $value):?>
                  <?php if(isset($user["my_nav_tags"][$nav["delta"]]) && in_array($tag_id, $user["my_nav_tags"][$nav["delta"]])):?>
                  <input type="checkbox" name="tags_id[]" value="<?php echo $tag_id;?>" checked>
                  <?php echo $value["name"];?>
                  <?php else:?>
                  <input type="checkbox" name="tags_id[]" value="<?php echo $tag_id;?>">
                  <?php echo $value["name"];?>
                  <?php endif;?>
                  <?php endforeach;?>
                </div>
              </li>
              <li class="list-group-item">
                <div class="media">
                  <div class="media-body">
                    <input type="submit" class="btn btn-default btn-sm pull-right" value="保存">
                  </div>
                </div>
              </li>
              <input type="hidden" name="form_type" value="<?php echo $nav["type"];?>">
              <input type="hidden" id="uid" name="uid" value="<?php echo $user["uid"];?>">
              <input type="hidden" id="delta" name="delta" value="<?php echo $nav["delta"];?>">
            </form>
          </ul>
        </div>
      </div>
      <?php elseif($nav["type"] == "page"):?>
      <div class="modal-body p-a-0">
        <div class="">
          <form id="page_content_form" method="post" enctype="multipart/form-data">
          <ul class="media-list media-list-users list-group">
              <input type="hidden" name="uid" value="<?php echo $user["uid"];?>">
              <input type="hidden" name="delta" value="<?php echo $nav["delta"];?>">
              <input type="hidden" name="form_type" value="<?php echo $nav["type"];?>">
              <div class="page_content" data-idx="1">
                <li class="list-group-item">
                  タイトル：<input type="text" name="title1" value="" style="width: 100%;" class="form-control title" data-action="grow" data-name="title" checked="checked">
                </li>
                <li class="content_page_type1 list-group-item">
                  タイプ：
                  <select class="type" name="type1" data-name="type">
                    <option value="">選択してください</option>
                    <option value="right_image_caption">文章+右画像</option>
                    <option value="left_image_caption">左画像+文章</option>
                    <option value="only_sentence">文章のみ</option>
                    <option value="table">テーブル</option>
                  </select>
                </li>
                <li class="right_image_caption list-group-item" data-idx="1" style="display:none;">
                  文章+右画像：
                  <div class="row">
                    <div class="col-lg-4">
                      <textarea name="sentence1" class="form-control sentence" data-name="sentence"></textarea>
                    </div>
                    <div class="col-lg-8">
                      <input type="file" name="image1" class="form-control image" style="border:none;" data-name="image" required/>
                    </div>
                  </div>
                </li>
                <li class="left_image_caption list-group-item" style="display:none;">
                  左画像+文章：
                  <div class="row">
                    <div class="col-lg-4">
                      <input type="file" name="image1" class="form-control" style="border:none;" required />
                    </div>
                    <div class="col-lg-8">
                      <textarea name="sentence1" class="form-control"></textarea>
                    </div>
                  </div>
                </li>
                <li class="only_sentence list-group-item" style="display:none;">
                  文章のみ：
                  <div class="row">
                    <div class="col-lg-12">
                      <textarea name="sentence1" class="form-control"></textarea>
                    </div>
                  </div>
                </li>
                <li class="table list-group-item" style="display:none;">
                  テーブル：
                  <table style="background-color:#fff;">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">タイトル</th>
                        <th scope="col">内容</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="add">
                        <th class="num">1</th>
                        <td style="width:250px;padding-top:15px;"><input type="text" name="table_title" class="form-control table_title" data-name="table_title" /></td>
                        <td><textarea name="table_sentence" class="form-control table_sentence" data-name="table_sentence"></textarea></td>
                      </tr>
                    </tbody>
                  </table>
                  <button class="add_table_row">行を追加</button>
                </li>
                <div style="margin:15px;" /><button class="add_content">コンテンツを追加</button></div>
        </div>
        
        
        </ul>
          <input type="submit" class="btn btn-default btn-sm pull-right" value="保存">
          </form>
      </div>
    </div>
    <?php endif;?>
  </div>
</div>
<?php endforeach;?>
<div id="contact-form" style="width:100%;">
  <div class="panel panel-default" style="display:none;">
    <div class="modal-header">
      <h4 class="modal-title">Contact</h4>
    </div>

    <div class="modal-body p-a-0">
      <div class="">
        <ul class="media-list media-list-users list-group">
          <form id="user_regist-form" method="post" enctype="multipart/form-data">
            <li class="list-group-item">
              <div class="media">
                <input type="text" name="name" placeholder="ユーザーID" style="width: 100%;" class="form-control" data-action="grow">
              </div>
            </li>
            <li class="list-group-item">
              <div class="media">
                <input type="text" name="mail" placeholder="メールアドレス" style="width: 100%;" class="form-control" data-action="grow">
              </div>
            </li>
            <li class="list-group-item">
              <div class="media">
                <input type="password" name="password" placeholder="パスワード" style="width: 180px;" class="form-control" data-action="grow">
              </div>
            </li>
            <li class="list-group-item">
              <div class="media">
                <input type="password" name="repassword" placeholder="パスワードの確認" style="width: 100%;" class="form-control" data-action="grow">
              </div>
            </li>
            <li class="list-group-item">
              <div class="media">
                <input type="text" name="user_name" placeholder="ユーザー名" style="width: 100%;" class="form-control" data-action="grow">
              </div>
            </li>
            <li class="list-group-item">
              <div class="media">
                <input type="file" name="picture" class="form-control" placeholder="アイキャッチ画像">
              </div>
            </li>
            <li class="list-group-item">
              <div class="media">
                <textarea id="user_body" name="user_body" rows="4" cols="40" placeholder="自己紹介" style="width: 100%;" class="form-control"></textarea>
              </div>
            </li>
            <li class="list-group-item">
              <div class="media">
                <div class="media-body">
                  <button class="btn btn-default btn-sm pull-right close" data-dismiss="modal" onclick="return user_post(this);">
                    <span class="glyphicon glyphicon-user"></span> 登録
                  </button>
                </div>
              </div>
            </li>
            <input type="hidden" id="edit_nid" name="edit_nid">
          </form>
        </ul>
      </div>
    </div>
  </div>
</div>
<div id="other-form" style="width:100%;">
  <div class="panel panel-default" style="display:none;">
    <div class="modal-header">
      <h4 class="modal-title">Other</h4>
    </div>

    <div class="modal-body p-a-0">
      <div class="">
        <ul class="media-list media-list-users list-group">
          <form id="user_regist-form" method="post" enctype="multipart/form-data">
            <li class="list-group-item">
              <div class="media">
                <input type="text" name="name" placeholder="ユーザーID" style="width: 100%;" class="form-control" data-action="grow">
              </div>
            </li>
            <li class="list-group-item">
              <div class="media">
                <input type="text" name="mail" placeholder="メールアドレス" style="width: 100%;" class="form-control" data-action="grow">
              </div>
            </li>
            <li class="list-group-item">
              <div class="media">
                <input type="password" name="password" placeholder="パスワード" style="width: 180px;" class="form-control" data-action="grow">
              </div>
            </li>
            <li class="list-group-item">
              <div class="media">
                <input type="password" name="repassword" placeholder="パスワードの確認" style="width: 100%;" class="form-control" data-action="grow">
              </div>
            </li>
            <li class="list-group-item">
              <div class="media">
                <input type="text" name="user_name" placeholder="ユーザー名" style="width: 100%;" class="form-control" data-action="grow">
              </div>
            </li>
            <li class="list-group-item">
              <div class="media">
                <input type="file" name="picture" class="form-control" placeholder="アイキャッチ画像">
              </div>
            </li>
            <li class="list-group-item">
              <div class="media">
                <textarea id="user_body" name="user_body" rows="4" cols="40" placeholder="自己紹介" style="width: 100%;" class="form-control"></textarea>
              </div>
            </li>
            <li class="list-group-item">
              <div class="media">
                <div class="media-body">
                  <button class="btn btn-default btn-sm pull-right close" data-dismiss="modal" onclick="return user_post(this);">
                    <span class="glyphicon glyphicon-user"></span> 登録
                  </button>
                </div>
              </div>
            </li>
            <input type="hidden" id="edit_nid" name="edit_nid">
          </form>
        </ul>
      </div>
    </div>
  </div>
</div>
</div>