<?php
/*
$my_page = 	array(
  ':name/profile' => 'mypage/profile',
	':name/menu' => 'mypage/menu',
	':name/blog' => 'mypage/blog',
	':name/detail' => 'mypage/detail',
	':name/map' => 'mypage/map',
	':name/contact' => 'mypage/contact',
	':name' => 'mypage/index',
);

$user = new Model_User();
$user_data = $user->get_user(29);

foreach($my_page as $id => $data) {
  unset($my_page[$id]);
  $id = str_replace(":name", $user_data["name"], $id);
  $my_page[$id] = $data;
}

$page = array_merge($page, $my_page);
*/
$my_portal = 	array(
  ':name/profile' => 'myportal/profile',
	':name/menu' => 'myportal/menu',
	':name/blog' => 'myportal/blog',
	':name/detail' => 'myportal/detail',
	':name/map' => 'myportal/map',
	':name/contact' => 'myportal/contact',
	':name' => 'myportal/index',
  ':name/list' => 'myportal/list',
  ':name/page' => 'myportal/page',
);

$myportal_custom = array();

$user = new Model_User();
$user_data = $user->get_user(29);

$user_list = $user->get_user();
$idx = 0;
foreach($user_list as $user_data) {
  foreach($my_portal as $id => $data) {
    $id = str_replace(":name", $user_data["name"], $id);
    $myportal_custom[$id] = $data;
    $idx++;
  }
}

$page = array(
	'_root_'  => 'welcome/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route
  'api/login' => 'api/login',
  'api/index' => 'api/index',
  'api/node_list' => 'api/node_list',
  'api/activity' => 'api/activity',
  'api/user_list' => 'api/user_list',
	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
);

$page = array_merge($page, $myportal_custom);

return $page;
