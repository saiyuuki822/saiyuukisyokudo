<div id="main">

<h2>MENU</h2>

<ul class="navmenu">
<li><a href="#lunch">ランチメニュー</a></li>
<li><a href="#course">コースメニュー</a></li>
<li><a href="#single">単品メニュー</a></li>
<li><a href="#drink">ドリンクメニュー</a></li>
<li><a href="#desert">デザートメニュー</a></li>
</ul>

<?php $tag = array('icon_osusume.png', 'icon_ninki.png');?> 
  
<?php foreach($list as $id => $data) { ?>
  
<section class="list">
<figure><img src="<?php echo $data["image"][0];?>" width="280" height="210" alt="" / style="max-width:280px;max-height:210px;"></figure>
<h4><a href='/blog/detail/<?php echo $data["nid"];?>'><?php echo $data["title"];?></a><div style="padding-left: 70%;"><?php echo date('Y/m/d', $data["created"]);?></dic></h4>
<img src="images/<?php echo $tag[intval($id) % 2];?>" width="90" height="60" alt="おすすめ" class="icon"></p>

<p><?php echo $data["body_value"];?>
  
  
</section>

<?php } ?>

<p class="pagetop"><a href="#">↑ PAGE TOP</a></p>

</div>
<!--/main-->