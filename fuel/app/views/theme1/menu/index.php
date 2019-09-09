

<div id="main" style="margin-top:20px;">

<h2>MENU</h2>
<?php $tag = array('icon_osusume.png', 'icon_ninki.png');?> 
  
<?php foreach($list as $id => $data) { ?>
<section class="list">
<figure><img src="<?php echo $data["image"][0];?>" style="max-width:280px;max-height:210px;" width="280" height="210" alt="" /></figure>
<h4><a href='/<?php echo $site_user["name"];?>/detail/?nid=<?php echo $data["nid"];?>'><?php echo $data["title"];?></a><div style="padding-left: 70%;"></dic></h4>
<p><?php echo $data["body_value"];?>
</section>

<?php } ?>

<p class="pagetop"><a href="#">↑ PAGE TOP</a></p>

</div>
<!--/main-->