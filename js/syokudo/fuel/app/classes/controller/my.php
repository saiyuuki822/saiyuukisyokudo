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
class Controller_My extends Controller_Template
{
  public $templete="template";
  public $content_data;
  public $image;
  
    /**
     * 初期処理
     */
    public function before()
    {
        $curl = Request::forge('http://syokudo.jpn.org/api/file', 'curl');
        $response = $curl->execute()->response();
        $this->image = \Format::forge($response->body,'json')->to_array();
        $this->image[0] = '/assets/img/anonymous_user.jpeg';
        
      
        parent::before();
    }
    
    /**
     * 初期処理
     */
    public function after($response)
    {
        $response = parent::after($response); // あなた自身のレスポンスオブジェクトを作成する場合は必要ありません。
        return $response; // after() は確実に Response オブジェクトを返すように
    }
  
  	public function action_index()
    {
        $data = array();
        $this->template->title = 'Example Page';
        $this->template->content = View::forge('welcome/index', $data);
	  }
}