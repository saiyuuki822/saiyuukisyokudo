
<div id="sub">

<?php foreach($list as $data): ?>
<div class="box1 mb1em"><!--<aside class="mb1em">-->
<nav>
<h2><?php echo $data['name'];?></h2>
<ul>
<?php foreach($data['link'] as $link): ?>
<li><a href="<?php echo $link['link_url'];?>" target="_blank"><?php echo $link['link_name'];?></a></li>
<?php endforeach; ?>
</ul>
</nav>

</div>
<!--/box1-->
<?php endforeach;?>
<br>
<?php 
  $url = $_SERVER['REQUEST_URI'];
  if(strstr($url,'profile')==true):
?>
<div>
<img src="/images/saiyuuki.jpg" width="200" height="200" alt="サイユウキ" class="wa" style="max-height: 200px;">
<p>
サイユウキ
</p>
</div>

<?php endif;?>

<!--
<aside class="mb1em">
<h2>関連情報</h2>
<ul>

<li><a href="#">関連情報リンク</a></li>
<li><a href="#">関連情報リンク</a></li>
<li><a href="#">関連情報リンク</a></li>
<li><a href="#">関連情報リンク</a></li>
</ul>
</aside>

-->

<!--  
  
<div class="box1 mb1em">

<section>
<h2>当ブロック内に画像を置く場合</h2>
<p>幅240pxまで。</p>
</section>

</div>

<section>
<h2>box1の外は</h2>
<p>こんな感じです。ここに画像を置く場合、幅260pxまで。</p>
</section>
-->
  
</div>
<!--/sub-->