<?php
header('Content-type: text/html');
?>

<div id="contents">

<div id="main">

<section style="margin-left: 15px;">
<h2><?php echo $data["title"];?></h2>
  <div style="text-align: center;"><img src="<?php echo $data['image'][0];?>" width="200" height="200" /></div>
  <div style="text-align:right;">
    <p>
      <button class="sharer button" data-sharer="twitter" data-title='<?php echo "サイユウキ食堂"." | ". $title;?>' data-url="<?php echo (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];?>" style="background-color: #75c7f0;color:white;">Twitter</button>
      <button class="sharer button" data-sharer="facebook" data-url="<?php echo (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];?>" style="background-color: #759ef0;color:white;">Facebook</button>
    </p>
  </div>
<?php echo $data["body_value"] ;?><br>
  
<img src="<?php echo $data['image'][0];?>" width="400" height="400" />

<?php foreach($data["image_caption"] as $image_caption):?>
  <?php echo "<br>";?>
  <?php echo "<br>";?>
  <div><p>
    <?php echo $image_caption["caption"];?></p></div>
  <img src="<?php echo $this->image[$image_caption["field_image"]];?>" width="400" height="400" />
  <?php echo "<br>";?>
<?php endforeach;?>
</section>

</div>
<!--/main-->



<p id="pagetop"><a href="#">↑ PAGE TOP</a></p>

</div>
<!--/contents-->