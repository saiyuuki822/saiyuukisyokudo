<div class="col-md-3">
      <div class="panel panel-default panel-profile m-b-md">
        <div class="panel-heading" style="background-image: url(/assets/img/iceland.jpg);"></div>
        <div class="panel-body text-center">
          <a href="profile/index.html">
            <img
              class="panel-profile-img"
              src="<?php echo $image[$user['picture']];?>">
          </a>

          <h5 class="panel-title">
            <a class="text-inherit" href="profile/index.html"><?php echo $user['user_name'];?></a>
          </h5>

          <?php if($user["uid"] != 0):?>
          <p class="m-b-md"><?php echo $user['user_body'];?></p>
          <?php else: ?>
          <a href="#"> <button class="btn btn-success-outline" data-dismiss="modal" onClick="return regist(this)">新規ユーザー登録</button><br></a>
          <div style="margin-top:5px;"><a href="/login"> <button class="btn btn-primary-outline" data-dismiss="modal">ログイン</button><br></a></div>
          <?php endif;?>

          <ul class="panel-menu">
            <li class="panel-menu-item">
              <a href="#userModal" class="text-inherit" data-toggle="modal">
                Friends
                <h5 class="m-y-0">12M</h5>
              </a>
            </li>

            <li class="panel-menu-item">
              <a href="#userModal" class="text-inherit" data-toggle="modal">
                Enemies
                <h5 class="m-y-0">1</h5>
              </a>
            </li>
          </ul>
        </div>
     </div>

     <div class="panel panel-default visible-md-block visible-lg-block">
       <div class="panel-body">
         <h5 class="m-t-0">Favorite Site <small>· <a href="#">Edit</a></small></h5> 
           <ul class="list-unstyled list-spaced">
             <?php foreach($user['favorite_url'] as $favorite_url):?>
             <li><span class="text-muted icon icon-calendar m-r"></span><a href="<?php echo $favorite_url['link_url'];?>" target="_blank"><?php echo $favorite_url['link_name'];?></a>
             <?php endforeach;?>
           </ul>
        </div>
     </div>

     <div class="panel panel-default visible-md-block visible-lg-block">
       <div class="panel-body">
          <h5 class="m-t-0">Follow <small>· <a href="#">Edit</a></small></h5>
          <div data-grid="images" data-target-height="150">
            <?php for($i = 0;$i<6;$i++):?>
              <?php if(isset($user['follow'][$i])):?>
              <div><img data-width="640" data-height="640" data-action="zoom" src="<?php echo $image[$user['follow'][$i]['picture']];?>" style="max-width:640px;max-height:640px;"></div>
              <?php else: ?>
              <div><img data-width="640" data-height="640" data-action="zoom" src="" style="max-width:640px;max-height:640px;display:none;"></div>
              <?php endif;?>
            <?php endfor;?>
          </div>
        </div>
       </div>
     </div>