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

  public $component;
  
  public function __construct() {
    $this->component = new Component_Common();
    $this->util = new Util_Common();
    $this->commonModel = new Model_Common();
  }
    /**
     * 初期処理
     */
    public function before()
    {
        parent::before();
      
        Config::load('action_data',true);
        
        
      
      
        \Log::error($_SERVER["REQUEST_URI"]);
        if($_SERVER["REQUEST_METHOD"] != "POST") {
          \Log::error("GET". print_r($_GET, true));
          
        } else {
          
          \Log::error("POST". print_r($_POST, true));
        }
        // メインナビゲーションを取得
        //$curl = Request::forge('http://syokudo.jpn.org/api/navigation/29', 'curl');
        //$response = $curl->execute()->response();
        //$list = \Format::forge($response->body,'json')->to_array();
        
        // サブナビゲーションを取得
        $curl = Request::forge('http://syokudo.jpn.org/api/user_sub_menu/29', 'curl');
        $response = $curl->execute()->response();
        $sub_menu_list = \Format::forge($response->body,'json')->to_array();
      
        $curl = Request::forge('http://syokudo.jpn.org/api/file', 'curl');
        $response = $curl->execute()->response();
        $this->image = \Format::forge($response->body,'json')->to_array();
        $this->image[0] = '/assets/img/anonymous_user.jpeg';

        /*
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
        */
        $login_user = Session::get('user');
        $userModel = new Model_User();
        $site_user = [];
        if(isset($login_user)) {
          $favorite_url = $userModel->get_user_favorite_url($login_user['uid']);
          $login_user["favorite_url"] = $favorite_url;
          $site_user = $this->component->getUser($login_user['uid']);
        }

        $this->template->is_theme = false;
        //$this->template->list = $list;
        $this->template->sub_menu = View::forge('theme1/sub_menu', ["list" => $sub_menu_list]);
        $this->template->login_user = $login_user;
        $this->template->user = $login_user;
        $this->template->image = $this->image;
        $this->template->sidebar_left = View::forge('sidebar_left', ['image' => $this->image, 'user' => $this->template->login_user, 'favorite_url' => ''], false)->auto_filter(false);
        $this->template->sidebar_right = View::forge('sidebar_right', ['image' => $this->image, 'user' => $this->template->login_user, 'favorite_url' => ''], false)->auto_filter(false);
        //$this->template->content = View::forge($site_user["my_theme"]["system_name"]. '/welcome/index', [], false)->auto_filter(false);
    }
  
    /**
     * 初期処理
     */
    public function after($response)
    {
      
        $params = $this->template->content->get();
      
 

        $controller = Request::main()->uri->segment(1);
        $action_name = Request::main()->action;
        $action_data = Config::get('action_data.'.$controller.".".$action_name);
        if(isset($action_data["select"])) {
          foreach($action_data["select"] as $table_name => $data) {
            $result = $this->commonModel->select($table_name, $data["condition"], $data["is_list"], $data["key_name"], $data["order"]);
            $params = array_merge($params, [$table_name => $result]); 
          }
          
          
          $this->template->content = View::forge($controller. '/'. $action_name, $params);

          
        }
        if(Input::post()) {
          if(isset($action_data["save"])) {
            foreach($action_data["save"] as $table_name => $data) {
              $condition = [];
              foreach($data["condition"] as $column_name => $value) {
                $condition[$column_name] = Input::post($value);
              }
              $result = $this->commonModel->select($table_name, $condition, true, null);
              if(count($result) >= 1) {
                $upd_data = [];
                foreach($data["data"] as $column_name => $value) {
                  if( Input::post($value)) {
                    $upd_data[$column_name] = Input::post($value);
                  }
                }
                if(!empty($upd_data)) {
                  $result = $this->commonModel->update($table_name, $upd_data, $condition);
                }
              } else {
                $ins_data = [];
                if(Input::post($value)) {
                  foreach($data["data"] as $column_name => $value) {
                    $ins_data[$column_name] = Input::post($value);
                  }
                }
                if(!empty($ins_data)) {
                  $result = $this->commonModel->insert($table_name, $ins_data);
                }
              }
            }
          }
          $login_user = Session::get('user');
          $user = $this->component->getUser($login_user["uid"]);
          $params = array_merge($params, ["user" => $user]);
          $this->template->content = View::forge($controller. '/'. $action_name, $params);
        }
        $response = parent::after($response); // あなた自身のレスポンスオブジェクトを作成する場合は必要ありません。
        return $response; // after() は確実に Response オブジェクトを返すように
    }
  
  	public function action_index()
    {
        $data = array();
        $this->template->title = 'Example Page';
        $this->template->content = View::forge('welcome/index', $data);
	  }
  
    public function returnJson($data) {
      \Log::error("Response");
      \Log::error(rtrim(print_r($data,true)) . "\n");
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode($data, true);
      exit(1);
    }
  
    public function debug($data) {
      echo "<pre>";
      print_r($data,true);
      echo "</pre>";
      exit(1);
    }
  
    public function add_image_field($list, $field_name, $new_field_name) {
      foreach($list as $id => $data) {
        $list[$id][$new_field_name] = $this->image[$list[$id][$field_name]];
      }
      return $list;
    }
  
}