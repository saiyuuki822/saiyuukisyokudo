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
      $file = $_FILES['post_file']['tmp_name'];
      $cfile = new CURLFile($_FILES["post_file"]["tmp_name"],'image/jpeg','test_name');
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
      $curl->set_params(array('title' => $title, 'body' => $body, 'uid' => $login_user['uid'], 'navigation' => $navigation, 'image' => $file, 'nid' => $edit_nid));
      $response = $curl->execute()->response();
      $result = \Format::forge($response->body,'json')->to_array();
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
      $curl = Request::forge('http://myportal.jpn.com/api/node_data/'.$nid, 'curl');
      $curl->set_params(array('nid' => $nid));
      $response = $curl->execute()->response();
      $result = \Format::forge($response->body,'json')->to_array();
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
      $curl = Request::forge('http://myportal.jpn.com/api/node_delete', 'curl');
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
      $login_user = Session::get('user');
      $file = $_FILES['body_file']['tmp_name'];
      $cfile = new CURLFile($_FILES["body_file"]["tmp_name"],'image/jpeg','test_name');
      $curl = Request::forge('http://myportal.jpn.com/api/upload_file', 'curl');
      $curl->set_option(CURLOPT_RETURNTRANSFER,true);
      $curl->set_option(CURLOPT_BINARYTRANSFER,true);
      $curl->set_header('Content-Type','multipart/form-data');
      $curl->set_params(array('uid' => $login_user['uid'], 'image' => $file));
      $response = $curl->execute()->response();
      $result = \Format::forge($response->body,'json')->to_array();
      echo json_encode(array('uid' => $login_user['uid'], 'file' => $cfile, 'file_url' => $result['file_url']), true);
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