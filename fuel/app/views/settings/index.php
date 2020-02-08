<div class="profile-header text-center" style="background-image: url(../assets/img/);">
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
    <ul id="settings_tabs" class="nav nav-justified customNav" style="text-align:left;">
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
                  テーマ：<select name="theme">
                    <?php foreach($my_theme as $theme_data):?>
                    <?php if(isset($user["my_user_theme"]["theme_id"]) && $theme_data["theme_id"] == $user["my_user_theme"]["theme_id"]):?>
                    <option value="<?php echo $theme_data["theme_id"];?>" selected><?php echo $theme_data["name"];?></option>
                    <?php else:?>
                    <option value="<?php echo $theme_data["theme_id"];?>"><?php echo $theme_data["name"];?></option>
                    <?php endif;?>
                    <?php endforeach;?>
                  </select>
                </div>
              </li>
              <li class="list-group-item">
                <div class="media">
                  テーマカラー：<select name="theme_color">
                    <?php foreach($my_theme_color as $theme_color_data):?>
                    <?php if(isset($user["my_user_theme"]["theme_color"]) && $theme_color_data["color_id"] == $user["my_user_theme"]["theme_color"]):?>
                    <option value="<?php echo $theme_color_data["color_id"];?>" selected><?php echo $theme_color_data["name"];?></option>
                    <?php else:?>
                    <option value="<?php echo $theme_color_data["color_id"]; ?>"><?php echo $theme_color_data["name"];?></option>
                    <?php endif;?>
                    <?php endforeach;?>
                  </select>
                </div>
              </li>
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
                <!--
                <div class="media">
                  タグ：<br />
                  <?php foreach($default_value["my_tags"] as $tag_id => $value):?>
                  <input type="checkbox" name="nav_tags1[]" value="<?php echo $tag_id;?>">
                  <?php echo $value["name"];?>
                  <?php endforeach;?>
                </div>
                -->
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
                <!--
                <div class="media">
                  タグ：<br />
                  <?php foreach($default_value["my_tags"] as $tag_id => $value):?>
                  <input type="checkbox" name="nav_tags2[]" value="<?php echo $tag_id;?>">
                  <?php echo $value["name"];?>
                  <?php endforeach;?>
                </div>
                -->
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
                <!--
                <div class="media">
                  タグ：<br />
                  <?php foreach($default_value["my_tags"] as $tag_id => $value):?>
                  <input type="checkbox" name="nav_tags3[]" value="<?php echo $tag_id;?>">
                  <?php echo $value["name"];?>
                  <?php endforeach;?>
                </div>
                -->
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
                <!--
                <div class="media">
                  タグ：<br />
                  <?php foreach($default_value["my_tags"] as $tag_id => $value):?>
                  <input type="checkbox" name="nav_tags4[]" value="<?php echo $tag_id;?>">
                  <?php echo $value["name"];?>
                  <?php endforeach;?>
                </div>
                -->
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
                <!--
                <div class="media">
                  タグ：<br />
                  <?php foreach($default_value["my_tags"] as $tag_id => $value):?>
                  <input type="checkbox" name="nav_tags5[]" value="<?php echo $tag_id;?>">
                  <?php echo $value["name"];?>
                  <?php endforeach;?>
                </div>
                -->
              </li>
              <li class="list-group-item">
                <div class="media">
                  <div class="media-body">
                    <input type="submit" class="btn btn-default btn-sm pull-right" value="保存">
                  </div>
                </div>
              </li>
              <input type="hidden" id="edit_uid" name="edit_uid">
              <input type="hidden" name="uid" value="<?php echo $user["uid"];?>">
              <input type="hidden" name="form_type" value="basic">
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
  <?php foreach($user["my_navigation"] as $nav_idx => $nav):?>

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
  <?php if($nav_idx === 1):?>

  <?php endif;?>
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
              <input type="hidden" name="delete_sort[]" class="delete_sort" value="0" />
              <?php if(isset($user["page_content"][$nav["delta"]])):?>
              <?php foreach($user["page_content"][$nav["delta"]] as $id => $page_content):?>
              <div class="page_content" data-idx="<?php echo $id+1;?>">
                <li class="list-group-item">
                  <a href="#" class="delete_page_content"><div style="text-align:right;">×</div></a>
                  <?php $title = isset($page_content["title"]) ? $page_content["title"] : null;?>
                  タイトル：<input type="text" name="title<?php echo $id+1;?>" value="<?php echo $title;?>" style="width: 100%;" class="form-control title" data-action="grow" data-name="title">
                </li>
                <li class="content_page_type1 list-group-item">
                  タイプ：
                  <?php $type = isset($page_content["type"]) ? $page_content["type"] : null;?>
                  <select class="type" name="type<?php echo $id+1;?>" data-name="type">
                    <option value="">選択してください</option>
                    <option value="right_image_caption" <?php if($type == "right_image_caption") echo "selected";?>>文章+右画像</option>
                    <option value="left_image_caption" <?php if($type == "left_image_caption") echo "selected";?>>左画像+文章</option>
                    <option value="only_sentence"<?php if($type == "only_sentence") echo "selected";?>>文章のみ</option>
                    <option value="table"<?php if($type == "table") echo "selected";?>>テーブル</option>
                  </select>
                </li>
                <?php 
                  $display = ($type === "right_image_caption") ? "block" : "none";
                  $disabled =  ($type === "right_image_caption") ? 'disabled="false"' : 'disabled="true"';
                  if($type === "right_image_caption") {
                    $display = "block";
                    $disabled = '';
                    $image_src = $image[$page_content["caption_image"][$page_content["content_id"]]["image_id"]];
                    $sentence = $page_content["caption_image"][$page_content["content_id"]]["caption"];
                  } else {
                    $display = "none";
                    $disabled = 'disabled="true"';
                    $sentence = "";
                    $image_src = "";
                  }
                ?>
                <li class="right_image_caption list-group-item" data-idx="1" style="display:<?php echo $display;?>">
                  文章+右画像：
                  <div class="row">
                    <div class="col-lg-8">
                      <textarea name="sentence<?php echo $id+1;?>" class="form-control sentence" data-name="sentence" <?php echo $disabled;?>><?php echo $sentence;?></textarea>
                    </div>
                    <div class="col-lg-4">
                      <?php if(isset($page_content["caption_image"][$page_content["content_id"]]["image_id"]) && strlen($page_content["caption_image"][$page_content["content_id"]]["image_id"]) >= 1):?>
                      <img src="<?php echo $image_src;?>" width="200" height="200">
                      <input type="file" name="image<?php echo $id+1;?>" class="form-control image" style="border:none;" data-name="image" <?php echo $disabled; ?> />
                      <?php endif;?>
                    </div>
                  </div>
                </li>
                
                 <?php 
                  $display = ($type === "left_image_caption") ? "block" : "none";
                  $disabled =  ($type === "left_image_caption") ? 'disabled="false"' : 'disabled="true"';
                  if($type === "left_image_caption") {
                    $display = "block";
                    $disabled = '';
                    $image_src = $image[$page_content["caption_image"][$page_content["content_id"]]["image_id"]];
                    $sentence = $page_content["caption_image"][$page_content["content_id"]]["caption"];
                  } else {
                     $display = "none";
                     $disabled = 'disabled="true"';
                     $image_src = "";
                     $sentence = '';
                  }
                ?>
                <li class="left_image_caption list-group-item" data-idx="1" style="display:<?php echo $display;?>">
                  左画像+文章：
                  <div class="row">
                    <div class="col-lg-4">
                      <?php if(isset($page_content["caption_image"][$page_content["content_id"]]["image_id"]) && strlen($page_content["caption_image"][$page_content["content_id"]]["image_id"]) >= 1):?>
                      <img src="<?php echo $image_src;?>" width="200" height="200">
                      <input type="file" name="image<?php echo $id+1;?>" class="form-control image" style="border:none;" data-name="image" <?php echo $disabled;?> />
                      <?php endif;?>
                      
                    </div>
                    <div class="col-lg-8">
                      <textarea name="sentence<?php echo $id+1;?>" class="form-control sentence" data-name="sentence" <?php echo $disabled;?>><?php echo $sentence;?></textarea>
                    </div>
                  </div>
                </li>
                 <?php 
                  $display = ($type === "only_sentence") ? "block" : "none";
                  $disabled =  ($type === "only_sentence") ? 'disabled="false"' : 'disabled="true"';
                  if($type === "only_sentence") {
                    $display = "block";
                    $disabled = '';
                    $image_src = "";
                    $sentence = $page_content["caption_image"][$page_content["content_id"]]["caption"];;
                  } else {
                     $display = "none";
                     $disabled = 'disabled="true"';
                     $image_src = "";
                     $sentence = '';
                  }
                ?>
                <li class="only_sentence list-group-item" style="display:<?php echo $display;?>">
                  文章のみ：
                  <div class="row">
                    <div class="col-lg-12">
                      <textarea name="sentence<?php echo $id+1;?>" class="form-control" <?php echo $disabled;?>><?php echo $sentence;?></textarea>
                    </div>
                  </div>
                </li>
                <?php 
                  $display = ($type === "table") ? "block" : "none";
                  $disabled =  ($type === "table") ? 'disabled="false"' : 'disabled="true"';
                  if($type === "table") {
                    $display = "block";
                    $disabled = '';
                    $sentence = '';
                  } else {
                     $display = "none";
                     $disabled = 'disabled="true"';
                     $sentence = '';
                  }
                ?>
                
                <li class="table list-group-item" style="display:<?php echo $display;?>">
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
                      <?php if($page_content["table"][$page_content["content_id"]]):?>
                      <?php foreach($page_content["table"][$page_content["content_id"]] as $sort => $table_data):?>
                      <?php echo $sort;?>
                      <tr class="add">
                        <th class="num"><?php echo $table_data["sort"];?></th>
                        <td><input type="text" name="table_title<?php echo $id+1;?>_<?php echo $sort;?>" class="form-control table_title" data-name="table_title<?php echo $id+1;?>" value="<?php echo $table_data["title"];?>" /></td>
                        <td style="width:75%;"><textarea name="table_sentence<?php echo $id+1;?>_<?php echo $sort;?>" class="form-control table_sentence" data-name="table_sentence<?php echo $id+1;?>" rows="1" style="width:100%;"><?php echo $table_data["sentence"];?></textarea></td>
                        <td><a href="#" class="delete_table_row"><div style="text-align:right;">×</div></a></td>
                      </tr>
                      <?php endforeach;?>
                      <?php else:?>
                      <tr class="add">
                        <th class="num">1</th>
                        <td style="width:250px;padding-top:15px;"><input type="text" name="table_title" class="form-control table_title" data-name="table_title" /></td>
                        <td><textarea name="table_sentence" class="form-control table_sentence" data-name="table_sentence"></textarea></td>
                      </tr>
                      <?php endif;?>
                    </tbody>
                  </table>
                  <button class="add_table_row">行を追加</button>
                </li>
                    </div>
                <?php endforeach;?>
                <div style="margin:15px;" /><button class="add_content">コンテンツを追加</button></div>

        <?php endif;?>
            
            <?php if(!isset($user["page_content"][$nav["delta"]])):?>
              <div class="page_content" data-idx="1">
                <li class="list-group-item">
                  タイトル：<input type="text" name="title1" value="" style="width: 100%;" class="form-control title" data-action="grow" data-name="title">
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
                      <input type="file" name="image1" class="form-control image" style="border:none;" data-name="image" />
                    </div>
                  </div>
                </li>
                <li class="left_image_caption list-group-item" data-idx="1" style="display:none;">
                  左画像+文章：
                  <div class="row">
                    <div class="col-lg-4">
                      <textarea name="sentence1" class="form-control sentence" data-name="sentence"></textarea>
                    </div>
                    <div class="col-lg-8">
                      <input type="file" name="image1" class="form-control image" style="border:none;" data-name="image" />
                    </div>
                  </div>
                </li>
                <li class="only_sentence list-group-item" style="display:none;">
                  文章のみ：
                  <div class="row">
                    <div class="col-lg-12">
                      <textarea name="sentence1" class="form-control sentence"></textarea>
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
                        <td><a href="#" class="delete_table_row"><div style="text-align:right;">×</div></a></td>
                      </tr>
                    </tbody>
                  </table>
                  <button class="add_table_row">行を追加</button>
                </li>
                <div style="margin:15px;" /><button class="add_content">コンテンツを追加</button></div>
        </div>
            <?php endif;?>
            
            
            
            
            
            
        
        </ul>
          <input type="submit" class="btn btn-default btn-sm pull-right" value="保存">
          </form>
              <div id="page_content_template" class="page_content hide" data-idx="1" >
                <ul class="media-list media-list-users list-group">
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
                      <input type="file" name="image1" class="form-control image" style="border:none;" data-name="image" />
                    </div>
                  </div>
                </li>
                <li class="left_image_caption list-group-item" style="display:none;">
                  左画像+文章：
                  <div class="row">
                    <div class="col-lg-4">
                      <input type="file" name="image1" class="form-control image" style="border:none;" />
                    </div>
                    <div class="col-lg-8">
                      <textarea name="sentence1" class="form-control sentence"></textarea>
                    </div>
                  </div>
                </li>
                <li class="only_sentence list-group-item" style="display:none;">
                  文章のみ：
                  <div class="row">
                    <div class="col-lg-12">
                      <textarea name="sentence1" class="form-control sentence"></textarea>
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
                </ul>
                <div style="margin:15px;" /><button class="add_content">コンテンツを追加</button></div>
              </div>
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
          <form id="page_content_form" method="post" enctype="multipart/form-data">
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
      <form id="other_form" name="other_form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_type" value="other" />
        <?php if(isset($user["my_sub_menu"]) && count($user["my_sub_menu"]) >= 1):?>
        <?php foreach($user["my_sub_menu"] as $sub_menu_idx => $my_sub_menu):?>
        <div class="page_content" data-idx="<?php echo $sub_menu_idx;?>">
          <ul class="media-list media-list-users list-group">
            <li class="list-group-item">
              タイトル：<input type="text" name="title<?php echo ($sub_menu_idx+1);?>" value="<?php echo $my_sub_menu["title"];?>" style="width: 100%;" class="form-control title" data-action="grow" data-name="title" checked="checked">
            </li>
            <li class="table list-group-item">
              サブメニュー：
              <table style="background-color:#fff;">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">タイトル</th>
                    <th scope="col">URL</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($my_sub_menu["links"] as $links_id => $links):?>
                  <tr class="add">
                    <th class="num"><?php echo $links_id+1;?></th>
                    <td style="width:250px;"><input type="text" name="table_title<?php echo ($sub_menu_idx+1);?>_<?php echo ($links_id+1);?>" class="form-control table_title" data-name="table_title<?php echo $sub_menu_idx+1;?>" value="<?php echo $links["title"];?>" /></td>
                    <td style="width:600px;"><input type="text" name="table_sentence<?php echo ($sub_menu_idx+1);?>_<?php echo ($links_id+1);?>" class="form-control table_sentence" data-name="table_sentence<?php echo $sub_menu_idx+1;?>" value="<?php echo $links["url"];?>" /></textarea></td>
                    <td><a href="#" class="delete_table_row"><div style="text-align:right;">×</div></a></td>
                  </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
              <button class="add_table_row">行を追加</button>
            </li>
          </ul>
          <div style="margin:15px;"><button class="add_other">コンテンツを追加</button></div>
        </div>
        <?php endforeach;?>
        
        <?php else:?>
      <div class="page_content" data-idx="1">
        <ul class="media-list media-list-users list-group">
          <li class="list-group-item">
            タイトル：<input type="text" name="title1" value="" style="width: 100%;" class="form-control title" data-action="grow" data-name="title" checked="checked">
          </li>
          <li class="table list-group-item">
            サブメニュー：
            <table style="background-color:#fff;">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">タイトル</th>
                        <th scope="col">URL</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="add">
                        <th class="num">1</th>
                        <td style="width:250px;"><input type="text" name="table_title1_1" class="form-control table_title" data-name="table_title1" /></td>
                        <td style="width:600px;"><input type="text" name="table_sentence1_1" class="form-control table_sentence" data-name="table_sentence1" /></textarea></td>
                        <td><a href="#" class="delete_table_row"><div style="text-align:right;">×</div></a></td>
                      </tr>
                    </tbody>
                  </table>
                  <button class="add_table_row">行を追加</button>
                </li>
        </ul>
        <div style="margin:15px;"><button class="add_other">コンテンツを追加</button></div>  
      </div>
      <?php endif;?>
      <div style="margin:15px;"><input type="submit" class="btn btn-default btn-sm pull-right" value="保存"></div>
      </form>
      <div id="page_other_template" class="page_content" data-idx="1" style="display:none;">
        <ul class="media-list media-list-users list-group">
          <li class="list-group-item">
            タイトル：<input type="text" name="title1" value="" style="width: 100%;" class="form-control title" data-action="grow" data-name="title" checked="checked">
          </li>
          <li class="table list-group-item">
            サブメニュー：
            <table style="background-color:#fff;">
               <thead>
                 <tr>
                    <th scope="col">#</th>
                    <th scope="col">タイトル</th>
                    <th scope="col">URL</th>
                  </tr>
               </thead>
               <tbody>
                  <tr class="add">
                    <th class="num">1</th>
                    <td style="width:250px;"><input type="text" name="table_title1" class="form-control table_title" data-name="table_title1" /></td>
                    <td style="width:600px;"><input type="text" name="table_sentence1" class="form-control table_sentence" data-name="table_sentence1" /></textarea></td>
                  </tr>
                </tbody>
            </table>
            <button class="add_table_row">行を追加</button>
          </li>
        </ul>
        <div style="margin:15px;" /><button class="add_other">コンテンツを追加</button></div>
      </div>
    </div>
  </div>
</div>
</div>