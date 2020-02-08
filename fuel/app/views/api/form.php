<form id="upload-form" method="post" enctype="multipart/form-data" action="http://syokudo.jpn.org/api/post_edit">
              <input type="hidden" id="uid" name="uid" value="29">
              <input type="hidden" id="follow" name="" value="[{&quot;uid&quot;:&quot;29&quot;,&quot;follow_uid&quot;:&quot;1&quot;,&quot;picture&quot;:&quot;http://syokudo.jpn.org/sites/default/files/pictures/2018-11/cha-verde-emagrecedor-4.jpg&quot;},{&quot;uid&quot;:&quot;29&quot;,&quot;follow_uid&quot;:&quot;53&quot;,&quot;picture&quot;:&quot;http://syokudo.jpn.org/sites/default/files/pictures/2019-05/21192244_1387613751346580_3224091509494144283_n.jpg&quot;},{&quot;uid&quot;:&quot;29&quot;,&quot;follow_uid&quot;:&quot;54&quot;,&quot;picture&quot;:&quot;http://syokudo.jpn.org/sites/default/files/phppotHUF.jpg&quot;},{&quot;uid&quot;:&quot;29&quot;,&quot;follow_uid&quot;:&quot;149&quot;,&quot;picture&quot;:&quot;http://syokudo.jpn.org/sites/default/files/phpb9gJPi.jpg&quot;},{&quot;uid&quot;:&quot;29&quot;,&quot;follow_uid&quot;:&quot;150&quot;,&quot;picture&quot;:&quot;http://syokudo.jpn.org/sites/default/files/phpUiajkC.jpg&quot;}]">
              <input type="hidden" id="edit_nid" name="edit_nid" value="297">
            <li class="list-group-item">
              <div class="media">
                <div class="media-body" style="text-align:right;">
                </div>
              </div>
            </li>
              <ons-carousel fullscreen="" swipeable="" auto-scroll="" overscrollable="" id="carousel" class="ons-swiper" style="touch-action: pan-y; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"><div class="ons-swiper-target" style="transform: translate3d(0px, 0px, 0px); transition: all 0.1s cubic-bezier(0.4, 0.7, 0.5, 1) 0s;">
              <ons-carousel-item style="width: 100%;">
            <li class="list-group-item">
              <div class="media">
                <input type="text" name="post_title" placeholder="Title" style="width: 100%;" class="form-control" data-action="grow">
              </div>
            </li>
            <li class="list-group-item">
              <div class="media">
                <textarea id="post_body" name="post_body" rows="4" cols="40" placeholder="Body" style="width: 100%;" class="form-control"></textarea>
              </div>
            </li>
            <li class="list-group-item">
              <div>
              <img id="preview" src="" style="max-width:100px;max-height:100px;">
              </div>
              <div style="padding-left:60%;">
                <input type="button" id="move_add_caption_image_page1" value="文章と画像を追加">
              </div>
              <div class="media">
                 <input type="file" id="post_file" name="post_file" class="form-control" placeholder="アイキャッチ画像" accept="image/*;capture=camera" multiple="">
            </div></li>
            <li class="list-group-item">
              <div class="media">
                 <select id="tags" name="tags" class="form-control">
                   <option value="" selected="">投稿するタグを選択してください</option>
                 <option value="36">和食</option><option value="37">中華</option><option value="38">洋食</option><option value="39">麺類</option><option value="45">つまみ</option><option value="46">遊び</option><option value="47">ライフスタイル</option><option value="52">コンピュータ</option></select>
              </div>
                               <select id="public_scope" name="public_scope" class="form-control">
                   <option value="1" selected="">全公開</option>
                   <option value="2">フォロワーのみ</option>
                   <option value="3">自分のみ</option>
                 </select>
            </li>
            <!--
            <li class="list-group-item">
              <div class="media">
                 <select id="public_scope" name="public_scope" class="form-control">
                   <option value='1' selected>全公開</option>
                   <option value='2'>フォロワーのみ</option>
                   <option value='3'>自分のみ</option>
                 </select>
              </div>
            </li>
            -->
            <!--<li>  AAA
              <input type="button" id="camera" value="カメラ" />
            </li>-->

            <li class="list-group-item" style="text-align:center;">
                              <input id="addpost_btn" class="btn btn-primary-outline" style="width:180px;border: 1px solid blue;" type="submit" value="登録じゃ">
            </li>
              </ons-carousel-item>
              <ons-carousel-item class="image_caption_form" data-idx="1" style="width: 100%;">
              <div class="image_caption">
                <li id="image_caption_wrapper1" class="list-group-item image_caption_wrapper">
                <div class="media">
                  <div style="margin:0 0;">
                  <img id="preview1" src="" style="max-width:50px;max-height:50px;">
                  </div>
                  <input class="field_image_form" type="file" name="field_image1" placeholder="追加画像" data-idx="1">
                  <textarea class="caption form-control" name="caption1" rows="2" cols="40" placeholder="Body" style="width: 100%;"></textarea>
                </div>
                </li>
                <li id="image_caption_wrapper2" class="list-group-item image_caption_wrapper" style="">
                <div class="media">
                  <div style="margin:0 0;">
<img id="preview2" src="" style="max-width:50px;max-height:50px;">
                  </div>
                  <input class="field_image_form" type="file" name="field_image2" placeholder="追加画像" data-idx="2">
                  <textarea class="caption form-control" name="caption2" rows="2" cols="40" placeholder="Body" style="width: 100%;"></textarea>
                </div>
                </li>
                <li id="image_caption_wrapper3" class="list-group-item image_caption_wrapper" style="">
                <div class="media">
                  <div style="margin:0 0;">
<img id="preview3" src="" style="max-width:50px;max-height:50px;">
                  </div>
                  <input class="field_image_form" type="file" name="field_image3" placeholder="追加画像" data-idx="3">
                  <textarea class="caption form-control" name="caption3" rows="2" cols="40" placeholder="Body" style="width: 100%;"></textarea>
                </div>
                </li>
                <li id="image_caption_wrapper4" class="list-group-item image_caption_wrapper" style="">
                <div class="media">
                  <div style="margin:0 0;">
<img id="preview4" src="" style="max-width:50px;max-height:50px;">
                  </div>
                <input class="field_image_form" type="file" name="field_image4" placeholder="追加画像" data-idx="4">
                  <textarea class="caption form-control" name="caption4" rows="2" cols="40" placeholder="Body" style=""></textarea> 
                </div>
                </li>
              </div>
              </ons-carousel-item>
              <ons-carousel-item class="image_caption_form" data-idx="２" disabled="true" style="width: 100%;">
              <div class="image_caption">
                <li id="image_caption_wrapper5" class="list-group-item image_caption_wrapper" style="">
                <div class="media">
                  <div style="margin:0 0;">
<img id="preview5" src="" style="max-width:50px;max-height:50px;">
                  </div>
                  <input class="field_image_form" type="file" name="field_image5" placeholder="追加画像" data-idx="5">
                  <textarea class="caption form-control" name="caption5" rows="2" cols="40" placeholder="Body" style="width: 100%;"></textarea>
                </div>
                </li>
                <li id="image_caption_wrapper6" class="list-group-item image_caption_wrapper">
                <div class="media">
                  <div style="margin:0 0;">
<img id="preview6" src="" style="max-width:50px;max-height:50px;">
                  </div>
                  <input class="field_image_form" type="file" name="field_image6" placeholder="追加画像" data-idx="6">
                  <textarea class="caption form-control" name="caption6" rows="2" cols="40" placeholder="Body" style="width: 100%;"></textarea>
                </div>
                </li>
                <li id="image_caption_wrapper7" class="list-group-item image_caption_wrapper" style="">
                <div class="media">
                  <div style="margin:0 0;">
<img id="preview7" src="" style="max-width:50px;max-height:50px;">
                  </div>
                  <input class="field_image_form" type="file" name="field_image7" placeholder="追加画像" data-idx="7">
                  <textarea class="caption form-control" name="caption7" rows="2" cols="40" placeholder="Body" style="width: 100%;"></textarea>
                </div>
                </li>
                <li id="image_caption_wrapper8" class="list-group-item image_caption_wrapper" style="">
                <div class="media">
                  <div style="margin:0 0;">
<img id="preview8" src="" style="max-width:50px;max-height:50px;">
                  </div>
                <input class="field_image_form" type="file" name="field_image8" placeholder="追加画像" data-idx="8">
                  <textarea class="caption form-control" name="caption8" rows="2" cols="40" placeholder="Body" style="width: 100%;"></textarea> 
                </div>
                </li>
              </div>
              </ons-carousel-item>
              </div><div class="ons-swiper-blocker" style="pointer-events: none;"></div></ons-carousel>



</form>