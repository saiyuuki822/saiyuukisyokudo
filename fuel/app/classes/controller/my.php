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
  public $content_data;
  public $sub_menu;
  public $image;
  
    /**
     * 初期処理
     */
    public function before()
    {
        parent::before();
        // メインナビゲーションを取得
        $curl = Request::forge('http://syokudo.jpn.org/api/navigation/29', 'curl');
        $response = $curl->execute()->response();
        $list = \Format::forge($response->body,'json')->to_array();
        
        // サブナビゲーションを取得
        $curl = Request::forge('http://syokudo.jpn.org/api/user_sub_menu/29', 'curl');
        $response = $curl->execute()->response();
        $sub_menu_list = \Format::forge($response->body,'json')->to_array();
      
        $curl = Request::forge('http://syokudo.jpn.org/api/file', 'curl');
        $response = $curl->execute()->response();
        $this->image = \Format::forge($response->body,'json')->to_array();
        $this->image[0] = '/assets/img/anonymous_user.jpeg';

        
        $page = [];
        $current_link;
        foreach($list as $id => $data) {
          $list[$id]["menu_name"] = str_replace('HOME', 'Welcome', $list[$id]["menu_name"]);
          $link_name = strtolower($list[$id]["menu_name"]);
          $page[$link_name] = $data;
          $path1 = explode('/', $_SERVER['PHP_SELF']);
          if(isset($path1[2])) { 
            $path1 = $path1[2];
          } else {
            Response::redirect('/welcome');
          }
          if(str_replace('/index.php/', '', $path1) == $link_name) {
            $current_link = $link_name;
          }
        }
        if(!empty($current_link)) {
          if($page[$current_link]['type'] == 2) {
              $tid = $page[$current_link]['menu_id'];
              $curl = Request::forge('http://syokudo.jpn.org/api/page/'. $tid, 'curl');
              $response = $curl->execute()->response();
              $data = \Format::forge($response->body,'json')->to_array();
              $this->content_data = $data[0]["body_value"];
          }
        }
        
        $this->template->list = $list;
        $this->template->sub_menu = View::forge('theme1/sub_menu', ["list" => $sub_menu_list]);
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