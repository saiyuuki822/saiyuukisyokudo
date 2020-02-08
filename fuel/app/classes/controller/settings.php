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
class Controller_Settings extends Controller_My
{
	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
    $common = new Component_Common();
    $user = $common->getUser($this->template->login_user["uid"]);
    $message = "";
    $result = [];
    if(Input::post()) {
      if(Input::post('form_type') === "basic") {
        $result = $common->saveSettingsBasic($this->template->login_user["uid"], Input::post(), $_FILES);
        $message = "保存が完了しました。";
      }
      if(Input::post('form_type') === "home") {
        $result = $common->saveSettingsHome($this->template->login_user["uid"], Input::post(), $_FILES);
        $message = "保存が完了しました。";
      }
      if(Input::post('form_type') === "content") {
        $result = $common->saveSettingsContent($this->template->login_user["uid"], Input::post(), $_FILES);
        $message = "保存が完了しました。";
      }
      if(Input::post('form_type') === "page") {
        $result = $common->saveSettingsPageContent($this->template->login_user["uid"], Input::post(), $_FILES);
        $message = "保存が完了しました。";
      }
      if(Input::post('form_type') === "other") {
        $result = $common->saveSettingsOtherContent($this->template->login_user["uid"], Input::post(), $_FILES);
        $message = "保存が完了しました。";
      }
      $user = $common->getUser($this->template->login_user["uid"]);
    }
    
    
    $this->update_image();
    
    
    
    $default_value = [];
    $default_value["user_site_name"] = $user["user_site_name"];
    $default_value["my_navigation"] = $user["my_navigation"];
    $default_value["my_tags"] = $user["my_tags"];
    $this->template->is_settings = true;
    $this->template->content = View::forge('settings/index', ["result" => $result, "user" => $user, "image" => $this->image, "message" => $message, "default_value" => $default_value,  "post" => Input::post(), "image" => $this->image], false)->auto_filter(false);
    return $this->template;
	}
  
  public function action_login()
  {
    $commonModel = new Model_Common();
    $token = Input::get('token');
    $pass_token = $commonModel->select("my_pass_token", ["token" => $token]);
    if($pass_token['uid']) {
      $user = new Model_User();
      $login_user = $user->get_user($pass_token['uid']);
      Session::set('user', $login_user);
      Response::redirect('/index.php/settings/', 'refresh', 200);
    } else {
      Response::redirect('/index.php/login/', 'refresh', 200);
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
		return Response::forge(Presenter::forge('syokudo/welcome/hello'));
	}

  public function action_about()
  {
  		return Response::forge(View::forge('syokudo/welcome/index'));
  }
  
  
  public function update_image() {
        $curl = Request::forge('http://syokudo.jpn.org/api/file', 'curl');
        $response = $curl->execute()->response();
        $this->image = \Format::forge($response->body,'json')->to_array();
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
