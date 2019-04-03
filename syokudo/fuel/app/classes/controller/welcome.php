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
class Controller_Welcome extends Controller_My
{
	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
    

    //$query = DB::query('SELECT * FROM `users`');
    // 返り値: Database_MySQLi_Result オブジェクト
    //$result = $query->execute()->as_array();
    //array('select' => array('id', 'name'))
    //$user = Model_User::find(29, array('select'=> array('uid', 'uuid','t1.field_user_name_value'), 'related' => array('user_name')));
    //$user = Model_User::query()->related('user_name')->get();
    
    $user = new Model_User();
    $node = new Model_Node();
    
    $node_list = $node->get_node();
    
    $comment_list = $node->get_node_comment_list();

    $login_user = Session::get('user');
    $follow = $user->get_user_follow($login_user['uid']);
    $favorite_url = $user->get_user_favorite_url($login_user['uid']);
    $login_user['follow'] = $follow;
    $login_user['favorite_url'] = $favorite_url;
    $this->template->title = 'Example Page';
    $this->template->user = $login_user;
    $this->template->image = $this->image;
    $this->template->content = View::forge('welcome/index', ["list" => $node_list, 'image' => $this->image, "comment_list" => $comment_list], false)->auto_filter(false);
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
