<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.8
 * @author     Fuel Development Team
 * @license    MIT License
tqka * @copyright  2010 - 2016 Fuel Development Team
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
class Controller_Api extends Controller_My
{
  public $template = 'login/template';
	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
    var_dump("api");
    exit(1);
	}
  
  public function action_login()
  {
      $name = Input::post('name');
      $password = Input::post('password');
      $curl = Request::forge('http://myportal.jpn.com/api/user_login', 'curl');
      $curl->set_params(array('name' => $name, 'pass' => $password));
      $response = $curl->execute()->response();
      $result = \Format::forge($response->body,'json')->to_array();
      $user = new Model_User();
      $login_user = $user->get_user($result['uid'])[0];
      // メインナビゲーションを取得
      $curl = Request::forge('http://myportal.jpn.com/api/navigation/'.$login_user['uid'], 'curl');
      $response = $curl->execute()->response();
      $list = \Format::forge($response->body,'json')->to_array();
      $login_user["navigation"] = $list;
      $login_user["picture"] = $this->image[$login_user["picture"]];
      $follow = $user->get_user_follow($login_user['uid']);
      foreach($follow as $id => $data) {
        $follow[$id]["picture"] = $this->image[$data["picture"]];
      }
      $login_user['follow'] = $follow;
      $user = Session::get('user');
      \Log::error("errorログ");
      \Log::error($user["uid"]);
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo (json_encode($login_user, true));
      exit(1);
  }
  
  public function action_node_list()
  {
    $curl = Request::forge('http://myportal.jpn.com/api/node_list/all', 'curl');
    $response = $curl->execute()->response();
    $list = \Format::forge($response->body,'json')->to_array();
    foreach($list as $id => $data) {
      $list[$id]["created"] = date('Y/m/d h:i', $data["created"]);  
    }
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
    echo (json_encode($list, true));
    exit(1);
  }
  
  public function action_post()
  {
    if(Input::post()) {
      $uid = Input::post('uid');
      $title = Input::post('post_title');
      $body  = Input::post('post_body');
      $navigation = Input::post('navigation');
      $file = $_FILES['post_file']['tmp_name'];
      $cfile = new CURLFile($_FILES["post_file"]["tmp_name"],'image/jpeg','test_name');
      $edit_nid = null;
      if(Input::post('edit_nid')) {
        $edit_nid = Input::post('edit_nid');
      }
      if(isset($edit_nid) && strlen($edit_nid)) {
        \Log::info("node_post_edit!!!");
        $curl = Request::forge('http://myportal.jpn.com/api/aaa', 'curl');
      } else {
        \Log::info("node_post!!!");
        $curl = Request::forge('http://myportal.jpn.com/api/node_post', 'curl');
      }
      $curl->set_option(CURLOPT_RETURNTRANSFER,true); 
      $curl->set_option(CURLOPT_BINARYTRANSFER,true);
      $curl->set_header('Content-Type','multipart/form-data');
      $curl->set_params(array('title' => $title, 'body' => $body, 'uid' => $uid, 'navigation' => $navigation, 'image' => $file, 'nid' => $edit_nid));
      $response = $curl->execute()->response();
      $result = \Format::forge($response->body,'json')->to_array();
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode(array('title' => $title, 'body' => $body, 'file' => $cfile), true);
      exit(1);
    }
  }
  
	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_comment()
	{
    $node = new Model_Node();
    $comment_list = $node->get_node_comment_list();
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
    echo (json_encode($comment_list, true));
    exit(1);
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
		return Response::forge(Presenter::forge('login/hello'));
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
