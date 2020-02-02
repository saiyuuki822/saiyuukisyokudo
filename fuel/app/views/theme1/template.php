<!doctype html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        
        <!-- viewport meta -->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>サイユウキ食堂<?php if(isset($title)): echo " | ". $title; endif;?></title>
        <meta name="description" content="板橋本町駅から徒歩5分の仮想食堂です。">
        <meta name="keywords" content=サイユウキ食堂,仮想食堂,板橋本町>
        <?php if(isset($image)):?>
        <meta property="og:image" content="<?php echo $image; ?>" />
        <?php else: ?>
        <meta property="og:image" content="http://mypotal.jpn.com/images/logo.jpg" />
        <?php endif;?>
        <!-- Bootstrap CSS -->
        <link href="/assets/css/toolkit.css" rel="stylesheet">
        <link href="/assets/css/application.css" rel="stylesheet">
        <link rel="stylesheet" href="/css/style.css">
        <link rel="stylesheet" href="/css/theme1.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/chart.js"></script>
        <script src="/assets/js/toolkit.js"></script>
        <script src="/assets/js/application.js"></script>
    </head>
      
<body>

  
  <!--
  <nav class="navbar navbar-inverse navbar-fixed-top app-navbar">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-main">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.html">
        <img src="/assets/img/brand-white.png" alt="brand">
      </a>
    </div>
    <div class="navbar-collapse collapse" id="navbar-collapse-main">
      <ul class="nav navbar-nav hidden-xs">
        <li class="active">
          <a href="/">Home!!</a>
        </li>
        <li>
          <a href="/saiyuuki">MyPage</a>
        </li>
        <li>
          <a data-toggle="modal" href="#msgModal">Messages</a>
        </li>
        <li>
          <a href="docs/index.html">Docs</a>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right m-r-0 hidden-xs">
        <li>
          <a class="app-notifications" href="notifications/index.html">
            <span class="icon icon-bell"></span>
          </a>
        </li>
        <li>
          <button class="btn btn-default navbar-btn navbar-btn-avitar" data-toggle="popover" data-original-title="" title="">
            <img class="img-circle" src="http://syokudo.jpn.org/sites/default/files/pictures/2019-05/saiyuuki.jpg">
          </button>
        </li>
      </ul>
      <form class="navbar-form navbar-right app-search" role="search">
        <div class="form-group">
          <input type="text" class="form-control" data-action="grow" placeholder="Search">
        </div>
      </form>
      <ul class="nav navbar-nav hidden-sm hidden-md hidden-lg">
        <li><a href="index.html">Home</a></li>
        <li><a href="profile/index.html">Profile</a></li>
        <li><a href="notifications/index.html">Notifications</a></li>
        <li><a data-toggle="modal" href="#msgModal">Messages</a></li>
        <li><a href="docs/index.html">Docs</a></li>
        <li><a href="#" data-action="growl">Growl</a></li>
        <li><a href="login/index.html">Logout</a></li>
      </ul>
      <ul class="nav navbar-nav hidden">
        <li><a href="#" data-action="growl">Growl</a></li>
        <li><a href="/index.php/login">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>
  -->
  
  
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
<div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
        <img src="/images/1.jpg" class="d-block w-100" alt="..." style="max-height:250px;min-height:250px;">
    </div>
    <div class="carousel-item">
      <img src="/images/2.jpg" class="d-block w-100" alt="..." style="max-height:250px;min-height:250px;">
    </div>
    <div class="carousel-item">
      <img src="/images/3.jpg" class="d-block w-100" alt="..." style="max-height:250px;min-height:250px;">
    </div>
  </div>
</div>
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
        <p>© フリーランス サイユウキ</p>
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