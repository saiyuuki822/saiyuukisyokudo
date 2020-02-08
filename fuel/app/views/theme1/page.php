<div id="content">
  <?php foreach($page_content as $content):?>
  <?php if($content["type"] == "only_sentence"):?>
  <div class="well well-sm topbar" style="border-color:<?php echo $site_user["my_theme_color"]["value"];?>">
    <?php echo $content["title"];?>
  </div>
  <div style="margin-left:10px;">
    <p>
      <?php echo $content["caption_image"]["caption"];?>
    </p>
  </div>
  <?php elseif($content["type"] === "right_image_caption"):?>
    <div class="well well-sm topbar" style="border-color:<?php echo $site_user["my_theme_color"]["value"];?>">
    <?php echo $content["title"];?>
    </div>
    <div class="row">
      <div class="col-lg-4">   
        <img src="<?php echo $image[$content["caption_image"]["image_id"]];?>" width="200" height="200" alt="" class="wa" style="max-height: 200px;">
      </div>
      <div class="col-lg-8">   
        <p>
          <?php echo $content["caption_image"]["caption"];?>
        </p>
      </div>
    </div>
  <?php elseif($content["type"] === "left_image_caption"):?>
    <div class="well well-sm topbar" style="border-color:<?php echo $site_user["my_theme_color"]["value"];?>">
      <?php echo $content["title"];?>
    </div>
    <div class="row">
      <div class="col-lg-8">   
        <?php echo $content["caption_image"]["caption"];?>
      </div>
      <div class="col-lg-4">   
        <p>
          <img src="/images/saiyuuki.jpg" width="200" height="200" alt="" class="wa" style="max-height: 200px;">
        </p>
      </div>
    </div>
    <?php elseif($content["type"] === "table"):?>
    <div id="main">
      <div class="well well-sm topbar" style="border-color:<?php echo $site_user["my_theme_color"]["value"];?>">
        <?php echo $content["title"];?>
      </div>
      <section>
        <div class="row">
          <table class="ta1 mb1em">
            <tbody>
            <?php foreach($content["table"] as $table_data):?>
            <tr>
              <th><?php echo $table_data["title"];?></th>
              <td><?php echo str_replace("\r\n", "<br>", $table_data["sentence"]);?></td>
            </tr>
            <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
      </section>
  
  <?php endif;?>
  <?php endforeach;?>



<!--
  <div class="well well-sm topbar">
    更新情報・お知らせ W(`0`)W
  </div>
  <section id="new">
  <dl id="newinfo">
<dt><time datetime="2018-03-03">2018/03/03</time></dt>
<dd>ホームページリニューアル</dd>
</dl>


  </section>
-->
</div>


