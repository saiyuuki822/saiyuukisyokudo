<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.8
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2016 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Welcome extends Controller_My
{
  
    public function before()
    {
      parent::before();
      $this->template->login_user = Session::get('user');
      $this->template->is_settings = false;
    }
  
	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
    
    $user = new Model_User();
    $node = new Model_Node();
    
    $login_user = Session::get('user');
    
    if(!isset($login_user)) {
      $login_user['uid'] = 0;
    }
    if(Input::get("offset")) {
      $offset = Input::get("offset");
    } else {
      $offset = 0;
    }
    if(Input::get("limit")) {
      $limit = Input::get("limit");
    } else {
      $limit = 10;
    }
    $curl = Request::forge('http://syokudo.jpn.org/api/node_list/all/data?'."?offset=".$offset."&limit=".$limit, 'curl');
    $curl->set_method('post');
    $response = $curl->execute()->response();
    $node_list = \Format::forge($response->body,'json')->to_array();
    foreach($node_list as $key => $value) {
      $good_user = $node->get_good_user_data($value['nid']);
      
      $node_list[$key]["good_num"] = count($good_user);
      if(count($good_user) >= 1) {
        $node_list[$key]["is_good"] = true;
      } else {
        $node_list[$key]["is_good"] = false;
      }
      $ungood_user = $node->get_ungood_user_data($value['nid']);
      
      $node_list[$key]["ungood_num"] = count($ungood_user);
      if(count($good_user) >= 1) {
        $node_list[$key]["is_ungood"] = true;
      } else {
        $node_list[$key]["is_ungood"] = false;
      }
      $favorite_node = $node->get_good_favorite_node($login_user['uid'], $value['nid']);
      if(count($favorite_node) >= 1) {
        $node_list[$key]["is_favorite"] = true;
      } else {
        $node_list[$key]["is_favorite"] = false;
      }
    }

    $comment_list = $node->get_node_comment_list();
    if($login_user['uid'] != 0) {
      $follow = $user->get_user_follow($login_user['uid']);
      $favorite_url = $user->get_user_favorite_url($login_user['uid']);
      // メインナビゲーションを取得
      //$curl = Request::forge('http://syokudo.jpn.org/api/navigation/'.$login_user['uid'], 'curl');
      //$response = $curl->execute()->response();
      //$list = \Format::forge($response->body,'json')->to_array();
      //$navigation = $list;
      $navigation = [];
      Session::set('navigation', $navigation);
    } else {
      //Response::redirect('/index.php/login', 'refresh', 200);
      $follow = array();
      $favorite_url = array();
      $login_user['user_name'] = "AnonymousUser";
      $login_user['picture'] = 0;
      $login_user['user_body'] = '';
      $navigation = array();
      Session::set('navigation', $navigation);
    }

    $login_user['follow'] = $follow;
    $login_user['favorite_url'] = $favorite_url;
    $this->template->title = 'Example Page';
    $this->template->user = $login_user;
    //$this->template->user_navigation = $navigation;
    $this->template->image = $this->image;
    $this->template->is_top = true;
    $this->template->sidebar_left = View::forge('sidebar_left', ['image' => $this->image, 'user' => $login_user], false)->auto_filter(false);
    $this->template->sidebar_right = View::forge('sidebar_right', ['image' => $this->image, 'user' => $login_user], false)->auto_filter(false);
    $this->template->content = View::forge('welcome/index', ["list" => $node_list, 'image' => $this->image, "comment_list" => $comment_list, 'user' => $login_user], false)->auto_filter(false);
    return $this->template;
	}

	/**
	 * A typical "Hello, Bob!" type example.  This uses a Presenter to
	 * show how to use them.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_hello()
	{
		return Response::forge(Presenter::forge('welcome/hello'));
	}

	/**
	 * The 404 action for the application.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		return Response::forge(Presenter::forge('welcome/404'), 404);
	}
}
