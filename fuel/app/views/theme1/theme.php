  <div class="container">
    <div class="masthead">
      <div class="row">
        <div class="col-lg-4" style="margin-top:20px;">
          <?php if(isset($site_user["user_site_logo"])):?>
            <p id="logo"><img src="<?php echo $image[$site_user['user_site_logo']];?>" width="270" height="50" alt=""></p>
          <?php else:?>
            <div style="margin-top:-20px;"><h1 id="title" class="h1"><p class="border02"><?php echo $site_user['user_site_name'];?><p>
              
              </p></h1></div>
          <?php endif;?>  
        </div>
        <div class="col-lg-8">
        <?php if(isset($site_user["user_site_top_image"])):?>
        <aside class="mb1em"><img src="<?php echo $image[$site_user['user_site_top_image']];?>" style="width:100%;min-height:110px;max-height:110px;max-width:810px;min-width:810px;" height="99" alt="" class="wa"></aside>
        <?php else:?>
        <aside class="mb1em"><img src="/images/unknown_top_image.jpg" style="width:100%;min-height:110px;max-height:110px;max-width:810px;min-width:810px;" height="99" alt=""></aside>
        <?php endif;?>
        </div>
      </div>
      <ul id="main-navigation" class="nav nav-pills nav-justified">
      <?php foreach($site_user['my_navigation'] as $idx => $navigation):?>
        <?php $path = $navigation["type"] === "page" ? 'page' : 'list'; ?>
			  <li role="presentation" style="background-color:<?php echo $site_user["my_theme_color"]["value"];?>"><a href="/<?php echo $site_user['name'];?>/<?php echo $path;?>?nav=<?php echo $site_user['my_navigation'][$idx]['delta'];?>"><?php echo isset($site_user['my_navigation'][$idx]) ? $site_user['my_navigation'][$idx]['name'] : '';?></a></li>
		  <?php endforeach;?>
        <li role="presentation" style="background-color:<?php echo $site_user["my_theme_color"]["value"];?>"><a href="/<?php echo $site_user['name'];?>/contact">CONTACT</a></li>
      </ul>
    </div><!--/masthead-->
    <?php if(isset($is_top)):?>
    <div class="row" style="margin-top:5px;">
      <div class="col-lg-12">        
        <!-- 任意のID指定。クラスとデータ属性の指定。 -->
        <div id="carousel-example" class="carousel slide" data-ride="carousel">
          <!-- インジケーターの設置。下部の○●ボタン。 -->
          <ol class="carousel-indicators">
            <li data-target="#carousel-example" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example" data-slide-to="1"></li>
          </ol>
          <!-- スライドの内容 -->
          <div class="carousel-inner" style="margin-bottom:20px;">
            <?php if(isset($site_user["user_site_main_image1"])):?>
              <p id="logo"><img src="<?php echo $image[$site_user['user_site_main_image1']];?>" style="width:100%;" alt=""></p>
            <?php else:?>
              <p id="logo"><img src="/images/unknown_min_image.jpg" alt="" style="width:100%;max-height:250px;min-height:250px;"></p>
            <?php endif;?>
            <div class="item">
              <img src="/images/2.jpg" alt="" style="width:100%;max-height:250px;min-height:250px;">
            </div>
            <div class="item">
              <img src="/images/3.jpg" alt="" style="width:100%;max-height:250px;min-height:250px;">
            </div>
          </div>
          <!-- 左右の移動ボタン -->
          <a class="left carousel-control" href="#carousel-example" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
          </a>
          <a class="right carousel-control" href="#carousel-example" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
          </a>
        </div>
      </div>
    </div>
    <?php endif;?>  
    <div class="row">
      <div class="col-lg-8">
        <?php echo $content;?>
      </div>
      <div class="col-lg-4">
        <?php echo $sub_menu;?>
      </div>
      <!-- Site footer -->
      <footer class="footer" style="margin-top:300px;">
        <p>© フリーランス サイユウキ</p>
      </footer>
    </div> <!-- /row -->
  </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  

        
      
        
        <!-- jQuery、Popper.js、Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>