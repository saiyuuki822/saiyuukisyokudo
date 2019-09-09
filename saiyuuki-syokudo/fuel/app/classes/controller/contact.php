<?php
/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.8.1
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2018 Fuel Development Team
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
class Controller_Contact extends Controller_My
{
	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
      // POSTの場合
      if(Input::method() == 'POST') {
        $name = Input::post('name');
        $contact = Input::post('contact');
        $type  = Input::post('type');
        $body  = Input::post('body');
        
        // Request_Curl オブジェクトを作成
        $curl = Request::forge('http://syokudo.jpn.org/api/mail', 'curl');
        $curl->set_method('get');
        $curl->set_params(array('uid' => 29, 'name' => $name, 'contact' => $contact, 'type' => $type, 'body' => $body));
        $response = $curl->execute()->response();
        
      }
      $data = array();
      $this->template->content = View::forge('syokudo/contact/index', $data);
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
		return Response::forge(Presenter::forge('syokudo/welcome/hello'));
	}

  public function action_about()
  {
  		return Response::forge(View::forge('syokudo/welcome/index'));
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
