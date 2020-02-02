
<div id="sub" style="margin-top:20px;">
<?php foreach($site_user["my_sub_menu"] as $sub_menu):?>
<div class="panel panel-primary">
	<div class="panel-heading" style="border-color:<?php echo $site_user["my_theme_color"]["value"];?>;background-color:<?php echo $site_user["my_theme_color"]["value"];?>"><?php echo $sub_menu['title'];?></div>
    <?php foreach($sub_menu["links"] as $link):?>
			<div class="panel-body">
        <a href="<?php echo $link['url'];?>" target="_blank"><?php echo $link['title'];?></a>
			</div>
    <?php endforeach;?>
  </div>
<?php endforeach;?>
</div>
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