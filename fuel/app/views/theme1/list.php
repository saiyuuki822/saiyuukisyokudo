
<div id="content">
<div id="main" style="margin-top:20px;">

  <div class="well well-sm topbar" style="border-color:<?php echo $site_user["my_theme_color"]["value"];?>">
    <?php echo $nav_info["name"];?>
  </div>
  
<?php $tag = array('icon_osusume.png', 'icon_ninki.png');?> 
<?php $idx = 0;?>
<?php foreach($list as $id => $data) { ?>
<!--<section class="list">
<figure><img src="<?php echo $data["image"][0];?>" style="max-width:280px;max-height:210px;" width="280" height="210" alt="" /></figure>
<h4><a href='/<?php echo $site_user["name"];?>/detail/?nid=<?php echo $data["nid"];?>'><?php echo $data["title"];?></a><div style="padding-left: 70%;"></dic></h4>
<p><?php echo $data["body_value"];?>
</section>-->

<div class="col-sm-6 col-md-6">
    <div class="thumbnail" style="height:400px;?>">
      <img data-src="holder.js/100%x200" alt="100%x200" src="<?php echo $data["image"][0];?>" data-holder-rendered="true" style="height: 200px; width: 100%; display: block;">
      <div class="caption">
        <h3 id="thumbnail-label"><a href='/<?php echo $site_user["name"];?>/detail/?nid=<?php echo $data["nid"];?>'><?php echo $data["title"];?><span class="anchorjs-icon"></span></a></h3>
        <p><?php echo $data["body_value"];?></p>
      </div>
    </div>
  </div>

<?php } ?>

<p class="pagetop"><a href="#">↑ PAGE TOP</a></p>

</div>
<!--/main-->
</div>