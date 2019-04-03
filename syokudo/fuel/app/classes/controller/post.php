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
    if(Input::post() || Input::get() ) {
      $title = Input::get('post-title');
      $body = Input::get('post-body');
      $login_user = Session::get('user');
      $curl = Request::forge('http://myportal.jpn.com/api/node_post', 'curl');
      $curl->set_params(array('title' => $title, 'body' => $body, 'uid' => $login_user['uid']));
      $response = $curl->execute()->response();
      $result = \Format::forge($response->body,'json')->to_array();
      echo (json_encode($result, true));
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