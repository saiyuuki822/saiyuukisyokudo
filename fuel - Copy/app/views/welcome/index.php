

<?php foreach($list as $data): ?>
<li class="media list-group-item p-a">
  <div style="text-align: right;">
    <a href="#" class="text-inherit" data-toggle="modal" onClick="return edit_node(this);" class="text-inherit" data-nid="<?php echo $data["nid"];?>"><span class="icon icon-edit" ></span></a>
  </div>
          <a class="media-left" href="#">
            <img
              class="media-object img-circle"
              src="<?php echo $image[$data["picture"]];?>">
          </a>
          <div class="media-body">
            <div style="text-align: right;">
              <form id="delete-form" method="post" name"delete-form">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onClick="return post_delete(this);" data-nid="<?php echo $data["nid"];?>">×</button></div>
                <input type="hidden" name="post_nid" value="<?php echo $data["nid"];?>" />
              </form>
              <div class="media-heading">
                <small class="pull-right text-muted">4 min</small>
                <a href="<?php echo $data['user_site_url'];?>" target="_blank"><h5><?php echo $data["user_name"];?></h5></a>
              </div>
            <h3>
              <a href=""><?php echo $data["title"];?></a>
            </h3>
            <p>
              <?php echo strip_tags($data["body"]);?>
            </p>

            <div class="media-body-inline-grid" data-grid="images">
              <div style="display: none">
                <img data-action="zoom" data-width="1050" data-height="700" src="<?php echo $image[$data["image"][0]];?>">
              </div>

              <?php if(isset($data["image"][1])): ?>
              <div style="display: none">
                <img data-action="zoom" data-width="640" data-height="640" src="<?php echo $image[$data["image"][1]];?>">
              </div>
              <?php endif;?>

              <?php if(isset($data["image"][2])): ?>
              <div style="display: none">
                <img data-action="zoom" data-width="640" data-height="640" src="<?php echo $image[$data["image"][2]];?>">
              </div>
              <?php endif;?>
              <?php if(isset($data["image"][3])): ?>
              <div style="display: none">
                <img data-action="zoom" data-width="1048" data-height="700" src="<?php echo $image[$data["image"][3]];?>">
              </div>
              <?php endif;?>
            </div>
            
            <?php if($user['uid'] != 0):?>
            <hr>
            <div>
              <div style="float:left;">
                <a href="#" id="good" data-nid="<?php echo $data["nid"];?>" onClick="good(this);">
                  <?php $label = ($data["is_good"]) ? '取り消し' : 'いいね';?><?php echo $label;?>
                </a>
              </div>
              <div style="float:left;padding-left:20px;">
                  <a class="good_num" href="#" onClick="good_user(this);" data-nid="<?php echo $data["nid"];?>" data-toggle="modal"><?php echo $data["good_num"];?>人</a>
              </div>
              <div style="float:left;padding-left:20px;">
                <a href="#" id="ungood" data-nid="<?php echo $data["nid"];?>" onClick="ungood(this);">
                  <?php $label = ($data["is_good"]) ? '取り消し' : 'だめね';?><?php echo $label;?>
                </a>
              </div>
              <div style="float:left;padding-left:20px;">
                  <a class="ungood_num" href="#" onClick="ungood_user(this);" data-nid="<?php echo $data["nid"];?>" data-toggle="modal"><?php echo $data["ungood_num"];?>人</a>
              </div>
              <!--<div style="float:left;padding-left:20px;"><a href="#" id="comment" data-nid="<?php echo $data["nid"];?>">コメント</a></div>-->
              <div style="float:left;padding-left:20px;">
                <a href="#" id="favorite" data-nid="<?php echo $data["nid"];?>" onClick="favorite_node(this);">
                  <?php $label = ($data["is_favorite"]) ? '取り消し' : 'お気に入り';?><?php echo $label;?>
                </a>
              </div>
              <div style="clear:both;">  </div>
            </div>
            <?php endif;?>
            <hr>

            <?php if(isset($comment_list[$data["nid"]])):?>
            <ul class="media-list m-b">
              <?php foreach($comment_list[$data["nid"]] as $comment_data):?>
              <li class="media comment">
                <div style="text-align:right;">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onClick="return comment_delete(this);" data-cid="<?php echo $comment_data["cid"];?>">×</button>
                </div>
                <a class="media-left" href="#">
                  <img
                    class="media-obj$ect img-circle"
                    src="<?php echo $image[$comment_data['picture']];?>"
                    style="max-width:80px;max-height:80px;">
                </a>
                <div class="media-body">
                  <strong><?php echo $comment_data['user_name'];?></strong><br />
                  <?php echo $comment_data['body'];?>
                </div>
              </li>
              <hr>
              <?php endforeach;?>
              <?php if($user['uid'] != 0):?>
              <li class="media">
                <a class="media-left" href="#">
                  <img
                    class="media-obj$ect img-circle"
                    src="<?php echo $image[$user['picture']];?>"
                    style="max-width:80px;max-height:80px;">
                </a>
                <div class="media-body" style="padding-top:10px;">
                  <form method="post" action="/index.php/post/comment">
                  <textarea name="comment" style="width:100%;" required></textarea>
                  <div style="text-align:right;">
                  <input type="submit" class="btn btn-default btn-sm pull-right" value="コメントする">
                  <input type="hidden" name="nid" value="<?php echo $data["nid"];?>">
                  </div>
                  </form>
                </div>
              </li>
              <?php endif;?>
              </ul>
              <?php endif;?>

            
          </div>
        </li>
<? endforeach;?>