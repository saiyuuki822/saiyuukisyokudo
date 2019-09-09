<!doctype html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        
        <!-- viewport meta -->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="/css/style.css">
        <link rel="stylesheet" href="/css/theme1.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
      
<body>
  <div class="container">
    <div class="masthead">
      <div class="row">
        <div class="col-lg-4" style="margin-top:20px;">  
          <p id="logo"><img src="/images/syokudo.jpg" width="270" height="50" alt=""></p>
        </div>
        <div class="col-lg-8"></div>
      </div>
      <div id="main-navigation" class="row">
        <div id="main-navigation-item" class="col-sm">
          <a href="/<?php echo $site_user['name'];?>" id="main-link">Welcome</a>
        </div>
        <div id="main-navigation-item" class="col-sm">
          <a href="/<?php echo $site_user['name'];?>/profile" id="main-link">Profile</a>
        </div>
        <div id="main-navigation-item" class="col-sm">
          <a  href="/<?php echo $site_user['name'];?>/menu">Menu</a>
        </div>
        <div id="main-navigation-item" class="col-sm">
          <a  href="/<?php echo $site_user['name'];?>/map">Map</a>
        </div>
        <div id="main-navigation-item" class="col-sm">
          <a  href="/<?php echo $site_user['name'];?>/blog">BLOG</a>
        </div>
        <div id="main-navigation-item" class="col-sm">
          <a  href="/<?php echo $site_user['name'];?>/contact">Contact</a>
        </div>
      </div>
    </div>
    <?php if(isset($is_top)):?>
    <div class="row" style="margin-top:20px;">
      <div class="col-lg-12">
        <aside id="mainimg">
        <img class="slide_file" src="/images/1.jpg" title="index.html">
        <img class="slide_file" src="/images/2.jpg" title="<inde></inde>x.html">
        <img class="slide_file" src="/images/3.jpg" title="index.html">
        <input type="hidden" id="slide_loop" value="0">
        <a href="index.html" id="slide_link">
        <img id="slide_image" src="/images/1.jpg" alt="" width="977" height="260" />
        <img id="slide_image2" src="/images/1.jpg" alt="" width="977" height="260" /></a>
        </aside>
      </div>
    </div>
    <?php endif;?>  
    <div class="row">
      <div class="col-lg-8">
        <?php if(isset($is_top)):?>
        <aside class="mb1em"><img src="/images/banner1.jpg" width="700" height="99" alt="ランチタイムパン食べ放題" class="wa"></aside>
        <?php endif;?>
        <?php echo $content;?>
      </div>
      <div class="col-lg-4">
        <?php echo $sub_menu;?>
      </div>
    </div>
    <div class="row">
    <div class="col-lg-8">
    
    </div>
    <div class="col-lg-4">
          
    
      
    </div>
    </div>
      

    


      <!-- Site footer -->
      <footer class="footer">
        <p>© Company 2017</p>
      </footer>

    </div> <!-- /container -->


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
    </body>
</html>