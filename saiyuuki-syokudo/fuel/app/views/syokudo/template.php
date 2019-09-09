<!doctype html>
<html lang="ja" prefix="og: http://ogp.me/ns#">
<head>
<meta charset="utf-8">
<title>サイユウキ食堂</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="copyright" content="Template Party">
<meta name="description" content="板橋本町駅から徒歩5分の仮想食堂です。">
<meta name="keywords" content=サイユウキ食堂,仮想食堂,板橋本町>
<meta name="google-site-verification" content="FfTmU3eoqWoXdUHpoJ6Qd_8OwMUvmYDpmShDIyny428" />
<?php if(isset($image)):?>
<meta property="og:image" content="<?php echo $image;?>" />
<?php else: ?>
<meta property="og:image" content="http://mypotal.jpn.com/images/logo.jpg" />
<?php endif;?>
<link rel="stylesheet" href="/css/style.css">
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript" src="js/openclose.js"></script>
</head>

<body>

<div id="container">

<header>
<h1><a href="index.html">h1テキスト入力場所です。サンプルテキスト。サンプルテキスト。</a></h1>
<p id="logo"><a href="/welcome"><img src="/images/syokudo.jpg" width="270" height="50" alt=""></a></p>
</header>
  
<nav id="menubar">
<ul>
<?php foreach($list as $data) { ?>
  <?php if(isset($data["menu_name"])): ?>
  <li id="<?php echo ($_SERVER['PHP_SELF'] == '/index.php/'.strtolower($data["menu_name"]) ? 'current' : '');?>"><a href="/<?php echo strtolower($data["menu_name"]);?>"><?php echo $data["menu_name"];?></a></li>
  <?php endif;?>
<?php } ?>
</ul>
</nav>

<div id="contents">
<?php echo $content;?>
<?php echo $sub_menu;?>
</div>
<!--/contents-->

<footer>
<small>Copyright&copy; 2014 <a href="index.html">SAMPLE CAFE</a>　All Rights Reserved.</small>
<span class="pr"><a href="http://template-party.com/" target="_blank">Web Design:Template-Party</a></span>
</footer>

</div>
<!--/container-->

<!--スライドショースクリプト-->
<script type="text/javascript" src="js/slide_simple_pack.js"></script>

<!--スマホ用更新情報-->
<script type="text/javascript">
if (OCwindowWidth() < 480) {
	open_close("newinfo_hdr", "newinfo");
}
</script>

</body>
</html>
