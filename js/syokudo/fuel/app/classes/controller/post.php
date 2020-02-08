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
class Controller_Post extends Controller_My
{
  public $template = 'template';
	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
    if(Input::post()) {
      $title = Input::post('post_title');
      $body  = Input::post('post_body');
      $navigation = Input::post('navigation');
      $login_user = Session::get('user');
      \Log::error("errorログ");
      \Log::error($login_user["uid"]);
      $file = $_FILES['post_file']['tmp_name'];
      $cfile = new CURLFile($_FILES["post_file"]["tmp_name"],'image/jpeg','test_name');
      $edit_nid = null;
      if(Input::post('edit_nid')) {
        $edit_nid = Input::post('edit_nid');
      }
      $params = array('title' => $title, 'body' => $body, 'uid' => $login_user['uid'], 'navigation' => $navigation, 'image' => $file, 'nid' => $edit_nid);
      $i = 1;
      while(isset($_FILES['field_image'.$i]['tmp_name'])) {
        $params['field_image'.$i] = $_FILES['field_image'.$i]['tmp_name'];
      }
      $i = 1;
      while(Input::post('caption'.$i)) {
        $params['caption'.$i] = Input::post('caption'.$i);
      } 
      if(isset($edit_nid) && strlen($edit_nid)) {
        \Log::info("node_post_edit!!!");
        $curl = Request::forge('http://syokudo.jpn.org/api/aaa', 'curl');
      } else {
        \Log::info("node_post!!!");
        $curl = Request::forge('http://syokudo.jpn.org/api/node_post', 'curl');
      }
      $curl->set_option(CURLOPT_RETURNTRANSFER,true); 
      $curl->set_option(CURLOPT_BINARYTRANSFER,true);
      $curl->set_header('Content-Type','multipart/form-data');
      $curl->set_params($params);
      $response = $curl->execute()->response();
      $result = \Format::forge($response->body,'json')->to_array();
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode(array('title' => $title, 'body' => $body, 'file' => $cfile), true);
      exit(1);
    }
    Session::delete('user');
    $this->template->title = 'Example Page';
    $this->template->content = View::forge('login/index', [], false)->auto_filter(false);
    return $this->template;
	}

	/**
	 * A typical "Hello, Bob!" type example.  This uses a Presenter to
	 * show how to use them.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_edit()
	{
    if(Input::post() || Input::get()) {
      if(Input::get()) {
        $nid = Input::get('post_nid');
      } else {
        $nid = Input::post('post_nid');
      }
      $curl = Request::forge('http://syokudo.jpn.org/api/node_data/'.$nid, 'curl');
      $curl->set_params(array('nid' => $nid));
      $response = $curl->execute()->response();
      $result = \Format::forge($response->body,'json')->to_array();
      $node = new Model_Node();
      $tags = $node->get_node_tags($nid);
      if(isset($tags["tag_id"])) {
        $result[0]["tag_id"] = $tags["tag_id"];
        $result[0]["tag_name"] = $tags["name"];
       }
      
      
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode($result, true);
      exit(1);
    }
    exit(1);
	}
  
	/**
	 * A typical "Hello, Bob!" type example.  This uses a Presenter to
	 * show how to use them.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_delete()
	{
    if(Input::post()) {
      $nid = Input::post('post_nid');
      $curl = Request::forge('http://syokudo.jpn.org/api/node_delete', 'curl');
      $curl->set_params(array('nid' => $nid));
      $response = $curl->execute()->response();
      echo json_encode(array('nid' => $nid), true);
      exit(1);
    }
    exit(1);
	}

  public function action_upload_file()
  {
    if(Input::post()) {
      $file = $_FILES['body_file']['tmp_name'];
      $cfile = new CURLFile($_FILES["body_file"]["tmp_name"],'image/jpeg','test_name');
      $curl = Request::forge('http://syokudo.jpn.org/api/upload_file', 'curl');
      $curl->set_option(CURLOPT_RETURNTRANSFER,true);
      $curl->set_option(CURLOPT_BINARYTRANSFER,true);
      $curl->set_header('Content-Type','multipart/form-data');
      $curl->set_params(array('image' => $file));
      $response = $curl->execute()->response();
      $result = \Format::forge($response->body,'json')->to_array();
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode(array('file' => $cfile, 'file_url' => $result['file_url']), true);
      exit(1);
    }
    Session::delete('user');
    $this->template->title = 'Example Page';
    $this->template->content = View::forge('login/index', [], false)->auto_filter(false);
    return $this->template;
  }
  
  public function action_good()
  {
    if(Input::post() || Input::method() == 'GET' ) {
      $login_user = Session::get('user');
      $node = new Model_Node();
      try {
        $node->add_good_user($login_user['uid'], Input::post('nid'));
        $type = 'good';
      } catch (Exception $e) {
        \Log::error($e->getMessage());
        $node->delete_good_user($login_user['uid'], Input::post('nid'));
        $type = 'cancel';
      }
      echo json_encode(array('nid' => Input::post('nid'), 'type' => $type), true);
      exit(1);
    }
  }
  
  public function action_ungood()
  {
    if(Input::post() || Input::method() == 'GET' ) {
      $login_user = Session::get('user');
      $node = new Model_Node();
      try {
        $node->add_ungood_user($login_user['uid'], Input::post('nid'));
        $type = 'ungood';
      } catch (Exception $e) {
        $node->delete_ungood_user($login_user['uid'], Input::post('nid'));
        $type = 'cancel';
      }
      echo json_encode(array('nid' => Input::post('nid'), 'type' => $type), true);
      exit(1);
    }
  }
  
  public function action_favorite_node()
  {
    if(Input::post() || Input::method() == 'GET' ) {
      $login_user = Session::get('user');
      $node = new Model_Node();
      try {
        $node->add_favorite_node($login_user['uid'], Input::post('nid'));
        $type = 'favorite';
      } catch (Exception $e) {
        $node->delete_favorite_node($login_user['uid'], Input::post('nid'));
        $type = 'cancel';
      }
      echo json_encode(array('nid' => Input::post('nid'), 'type' => $type), true);
      exit(1);
    }
  }
  
  public function action_comment()
  {
    if(Input::post()) {
      $login_user = Session::get('user');
      $comment = Input::post('comment');
      $nid = Input::post('nid');
      $curl = Request::forge('http://syokudo.jpn.org/api/post_comment', 'curl');
      $curl->set_params(array('nid' => $nid, 'uid' => $login_user['uid'], 'comment' => $comment));
      $response = $curl->execute()->response();
      Response::redirect('/', 'refresh', 200);
      
    }
  }
  
  public function action_comment_delete() {
    if(Input::post()) {
       $cid = Input::post('cid');
       $node = new Model_Node();
       $node->delete_comment($cid);
      echo json_encode(array('cid' => $cid), true);
      exit(1);
    }
  }
  
  public function action_get_good_user() {
    if(Input::post()) {
      $nid = Input::post('nid');
      $node = new Model_Node();
      $good_user = $node->get_good_user($nid, $this->image);
      echo json_encode(array('good_user' => $good_user), true);
      exit(1);
    }
  }
  
  public function action_get_ungood_user() {
    if(Input::post()) {
      $nid = Input::post('nid');
      $node = new Model_Node();
      $ungood_user = $node->get_ungood_user($nid, $this->image);
      echo json_encode(array('ungood_user' => $ungood_user), true);
      exit(1);
    }
  }

  public function action_follow()
  {
     if(Input::post()) {
       $login_user = Session::get('user');
       $user = new Model_User();
       try {
         $user->add_follow_user($login_user['uid'], Input::post('uid'));
         $type = 'follow';
       } catch (Exception $e) {
         \Log::error($e->getMessage());
         $user->delete_follow_user($login_user['uid'], Input::post('uid'));
         $type = 'cancel';
      }
      echo json_encode(array('uid' => Input::post('uid'), 'type' => $type), true);
      exit(1);
    }
  }
  
  public function action_user_post()
  {
     if(Input::post()) {
      $name = Input::post('name');
      $password  = Input::post('password');
      $mail = Input::post('mail');
      $user_name = Input::post('user_name');
      $user_body = Input::post('user_body');
      $file = $_FILES['picture']['tmp_name'];
      $cfile = new CURLFile($_FILES["picture"]["tmp_name"],'image/jpeg','picture');
      $curl = Request::forge('http://syokudo.jpn.org/api/user_post', 'curl');
      $curl->set_option(CURLOPT_RETURNTRANSFER,true); 
      $curl->set_option(CURLOPT_BINARYTRANSFER,true);
      $curl->set_header('Content-Type','multipart/form-data');
      $curl->set_params(array('name' => $name, 'user_name' => $user_name, 'user_body' => $user_body, 'mail' => $mail, 'password' => $password, 'picture' => $file));
      $response = $curl->execute()->response();
      $result = \Format::forge($response->body,'json')->to_array();
      echo json_encode(array('name' => $name, 'user_body' => $user_body, 'mail' => $mail, 'password' => $password, 'picture' => $file), true);
      exit(1);
     }
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