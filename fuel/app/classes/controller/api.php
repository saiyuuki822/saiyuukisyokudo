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
  
  public function action_user_post()
  {
    if(Input::post()) {
      $name = Input::post('name');
      $password  = Input::post('password');
      $mail = Input::post('mail');
      $user_name = Input::post('user_name');
      $user_body = Input::post('user_body');
      $edit_uid = Input::post('edit_uid');
      $file = $_FILES['picture']['tmp_name'];
      $cfile = new CURLFile($_FILES["picture"]["tmp_name"],'image/jpeg','picture');
      $curl = Request::forge('http://myportal.monster/api/user_post', 'curl');
      $curl->set_option(CURLOPT_RETURNTRANSFER,true);
      $curl->set_option(CURLOPT_BINARYTRANSFER,true);
      $curl->set_header('Content-Type','multipart/form-data');
      $params = array('name' => $name, 'user_name' => $user_name, 'user_body' => $user_body, 'mail' => $mail, 'password' => $password, 'picture' => $file, 'uid' => $edit_uid);
      $curl->set_params($params);
      \Log::error("user_postuser_postuser_post");
      $response = $curl->execute()->response();
      \Log::error("user_postuser_postuser_post2");
      
      $result = \Format::forge($response->body,'json')->to_array();
      \Log::error("uid:".$result["result"]["uid"]);
      $_POST["uid"] = $result["result"]["uid"];
      $_POST["token"] = "abcdefg";
      \Log::error(print_r($result));
      $uid = $result["result"]["uid"];
      $token = md5($name. ":". $password);
      $commonModel = new Model_Common();
      $commonModel->insert('my_pass_token', ["uid" => $uid, "token" => $token]);
      if($result !== false && strlen($mail) >= 1 && filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        if(!(isset($edit_uid) && strlen($edit_uid) >= 1)) {
          $email = Email::forge();
          $email->from('info@myportal.monster', 'MyPORTAL');
          if(isset($user_name)) {
            $email->to($mail, $user_name. 'さん');
          } 
          $email->subject('MyPORTALへご登録ありがとうございます');
          try
          {
            $user = new Model_User();

            $pass_token = $commonModel->select("my_pass_token", ["uid" => $uid]);
            $pass = $pass_token["token"];
            //$pass = $user->get_user_activate_password($name);

            $params["pass"] = $pass;
            $email->body(\View::forge('email/activate', $params));
            $email->send();
          }
          catch(\EmailValidationFailedException $e)
          {
            echo "バリデーションが失敗したとき";
            return false;
          }
          catch(\EmailSendingFailedException $e)
          {
            echo "メールアドレスが間違ってる可能性があります";
            return false;
          }
        }
      }
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode(array("result" => $result, "name" => $name, "user_body" => $user_body, "mail" => $mail, "password" => $password, "picture" => $file), true);
      exit(1);
    }
  }
  
  public function action_activate($pass)
  {
    
    $commonModel = new Model_Common();
    
    $user = new Model_User();
    $uid = $user->get_user_uid_by_activate_password($pass);
    
    if($uid !== false) {
      $user->update_user_activate_status($uid, 1);
      $commonModel->insert('my_user_theme', ["uid" => $uid, "theme_id" => 1, "theme_color" => 1]);
      $commonModel->insert('my_navigation', ["uid" => $uid, "name" => "Home", "type" => "page", "delta" => 1]);
      $content_id = $commonModel->insert('my_page_content', ["title" => "ようこそ、私のポータルサイトへ", "type" => "only_sentence", "uid" => $uid, "delta" => 1, "sort" => 1]);
      $commonModel->insert('my_page_content_caption_image', ["content_id" => $content_id, "caption" => "よろしくお願いします。"]);
      $commonModel->insert('my_navigation', ["uid" => $uid, "name" => "Aboutas", "type" => "page", "delta" => 2]);
      $commonModel->insert('my_navigation', ["uid" => $uid, "name" => "LifeStyle", "type" => "content", "delta" => 3]);
      $commonModel->insert('my_navigation', ["uid" => $uid, "name" => "Hobby", "type" => "content", "delta" => 4]);
      $commonModel->insert('my_navigation', ["uid" => $uid, "name" => "Blog", "type" => "content", "delta" => 5]);
      $tag_id = $commonModel->insert('my_tags', ["uid" => $uid, "name" => "ライフスタイル", "sort" => 1]);
      $commonModel->insert('my_navigation_tag', ["uid" => $uid, "delta" => 3, "tag_id" => $tag_id]);                                         
      $tag_id = $commonModel->insert('my_tags', ["uid" => $uid, "name" => "趣味", "sort" => 2]);
      $commonModel->insert('my_navigation_tag', ["uid" => $uid, "delta" => 4, "tag_id" => $tag_id]);
      $tag_id = $commonModel->insert('my_tags', ["uid" => $uid, "name" => "BLOG", "sort" => 3]);
      $commonModel->insert('my_navigation_tag', ["uid" => $uid, "delta" => 5, "tag_id" => $tag_id]);
    }
    
    Response::redirect('/index.php/login?activate=1', 'refresh', 200);
    
    return $this->template;
  }

  public function action_login()
  {
    \Log::error("action_login");
    $commonModel = new Model_Common();
    $user = new Model_User();
    if(Input::post('uid')) {
      $login_user = $user->get_user(Input::post('uid'));
      $login_user["navigation"] = [];
      $login_user["picture"] = $this->image[$login_user["picture"]];
      $follow = $user->get_user_follow($login_user['uid']);
      foreach($follow as $id => $data) {
        $follow[$id]["picture"] = $this->image[$data["picture"]];
      }
      $login_user['follow'] = $follow;
      $good_node = $user->get_user_good_node($login_user['uid']);
      $login_user['good'] = $good_node;
      $ungood_node = $user->get_user_ungood_node($login_user['uid']);
      $login_user['but'] = $ungood_node;
      $favorite_node = $user->get_user_favorite_node($login_user['uid']);
      $login_user['favorite'] = $favorite_node;
      $tags = $user->search_tags($login_user["uid"]);
      $login_user['tags'] = $tags;
      $pass_token = $commonModel->select("my_pass_token", ["uid" => $login_user["uid"]]);
      $token = $pass_token["token"];
      $login_user["token"] = $token;
    } else {
      $name = Input::post('name');
      $password = Input::post('password');
      $curl = Request::forge('http://myportal.monster/api/user_login', 'curl');
      $curl->set_params(array('name' => $name, 'pass' => $password));
      $response = $curl->execute()->response();
      $result = \Format::forge($response->body,'json')->to_array();
      if(isset($result['uid'])) {
        $login_user = $user->get_user($result['uid']);
        $login_user["navigation"] = [];
        $login_user["picture"] = $this->image[$login_user["picture"]];
        $follow = $user->get_user_follow($login_user['uid']);
        foreach($follow as $id => $data) {
          $follow[$id]["picture"] = $this->image[$data["picture"]];
        }
        $login_user['follow'] = $follow;
        $good_node = $user->get_user_good_node($login_user['uid']);
        $login_user['good'] = $good_node;
        $ungood_node = $user->get_user_ungood_node($login_user['uid']);
        $login_user['but'] = $ungood_node;
        $favorite_node = $user->get_user_favorite_node($login_user['uid']);
        $login_user['favorite'] = $favorite_node;
        $tags = $user->search_tags($login_user["uid"]);
        $login_user['tags'] = $tags;
        $pass_token = $commonModel->select("my_pass_token", ["uid" => $login_user["uid"]]);
        $token = $pass_token["token"];
        $login_user["token"] = $token;
      } else {
        $login_user = ["uid" => 0];
      }
    }
    //$pass_token = $commonModel->select("my_pass_token", ["uid" => $login_user["uid"]);
    //$token = $pass_token["token"];
    //$login_user["token"] = $token;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
    echo (json_encode($login_user, true));
    exit(1);
  }
  
  public function action_user($uid)
  {
    $user = new Model_User();
    $login_user_uid = Input::post('uid');
    $target_user = $user->get_user($uid);
    
    $follow = $user->get_user_follow($uid);
    foreach($follow as $id => $data) {
      $follow[$id]["picture"] = $this->image[$data["picture"]];
    }
    $target_user['picture'] = $this->image[$target_user["picture"]];
    
    $target_user['follow'] = $follow;
    
    $target_user['is_follow'] = $user->is_follow($login_user_uid, $target_user["uid"]);
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
    echo (json_encode($target_user, true));
    exit(1);
  } 
  
  public function action_node_list($type, $entity_id)
  {
    if(Input::get("offset")) {
      $offset = Input::get("offset");
    } else {
      $offset = Input::post("offset");
    }
    if(Input::get("limit")) {
      $limit = Input::get("limit");
    } else {
      $limit = Input::post("limit");
    }
    
    $node = new Model_Node();
    $user = new Model_User();
    $query_string = "?offset=".$offset."&limit=".$limit;
    if(Input::post("tag_id") || Input::get("tag_id")) {
      if(Input::post("tag_id")) {
        $post_tag_id = Input::post("tag_id");
      } else {
        $post_tag_id = Input::get("tag_id");
      }
      if(is_numeric($post_tag_id)) {
        $query_string = $query_string. "&tag_id=".$post_tag_id;
      }
    }
    $curl = Request::forge('http://myportal.monster/api/node_list/'.$type. "/".$entity_id. $query_string, 'curl');
    $curl->set_method('post');
    
    $response = $curl->execute()->response();
    $list = \Format::forge($response->body,'json')->to_array();
    
    foreach($list as $id => $data) {
      $nid = $data["nid"];
      $list[$id]["created"] = date('Y/m/d h:i', $data["created"]);
      $list[$id]["is_good_user"] = $node->is_good_user($nid);
      $list[$id]["is_ungood_user"] = $node->is_ungood_user($nid);
      //文章と画像
      foreach($data["image_caption"] as $iamge_caption_idx => $image_caption) {
        $list[$id]["image_caption"][$iamge_caption_idx]["field_image"] = $this->image[$image_caption["field_image"]];
      }
      //タイプによる絞り込み
      if(Input::post("type")) {
        $type = Input::post("type");
        //お気に入りの場合
        if($type == 'bookmark') {
          $uid = Input::post("uid");
          $result = $node->get_good_favorite_node($uid);
          $nids = array_keys($result);
          if(!in_array($nid, $nids)) {
            unset($list[$id]);
            continue;
          }
        }
      }
      //公開範囲の絞り込み
      if(Input::post("uid")) {
        $public_scope = $data["public_scope"];
        $node_uid = $data["uid"];
        $current_user_uid = Input::post("uid");
        //全公開の場合
        if($public_scope == 1) {
          //何もしない
        //フォロワーのみの場合
        } else if($public_scope == 2) {
          $follow = $user->get_follower($current_user_uid);
          $followers = array_keys($follow);
          if(!in_array($node_uid, $followers) && $node_uid != $current_user_uid) {
            unset($list[$id]);
            continue;
          }
        //自分のみの場合
        } else if($public_scope == 3) {
          if($node_uid != $current_user_uid) {
            unset($list[$id]);
            continue;
          }
        //それ以外の場合
        } else {
          //何もしない
        }
      }
      if(isset($list[$id]["comment"]) && count($list[$id]["comment"]) >= 1) {
        //コメントに画像をつける
        foreach($list[$id]["comment"] as $comment_id => $comment_data) {
          $list[$id]["comment"][$comment_id]["picture"] = $this->image[$comment_data["picture"]];
        }
      }
      //タグが付いている場合
      $tags = $node->get_node_tags($nid);
      if(isset($tags["tag_id"])) {
        $list[$id]["tag_id"] = $tags["tag_id"];
        $list[$id]["tag_name"] = $tags["name"];
        //タグによる絞り込みの場合
        if(Input::post("tag_id")) {
          $post_tag_id = Input::post("tag_id");
          if($post_tag_id != $tags["tag_id"]) {
            unset($list[$id]);
          }
        }
      }
    }
    $next = false;
    if(count($list) >= $limit) {
      $next = true;
    }
    
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
    echo (json_encode(["list" => $list, "next" => $next], true));
    exit(1);
  }
  
  public function action_node_search($type, $entity_id)
  {
    if(Input::get("offset")) {
      $offset = Input::get("offset");
    } else {
      $offset = Input::post("offset");
    }
    if(Input::get("limit")) {
      $limit = Input::get("limit");
    } else {
      $limit = Input::post("limit");
    }
    $node = new Model_Node();
    $user = new Model_User();
    $word = Input::post("word");
    $url = 'http://myportal.monster/api/node_search/'.$type. "/".$entity_id. "/".$word."?offset=".$offset."&limit=".$limit;
    \Log::error($url);
    $curl = Request::forge('http://myportal.monster/api/node_search/'.$type. "/".$entity_id. "/".$word."?offset=".$offset."&limit=".$limit, 'curl');
    $response = $curl->execute()->response();
    $list = \Format::forge($response->body,'json')->to_array();
    foreach($list as $id => $data) {
      $nid = $data["nid"];
      $list[$id]["created"] = date('Y/m/d h:i', $data["created"]);
      
      $list[$id]["is_good_user"] = $node->is_good_user($nid);
      $list[$id]["is_ungood_user"] = $node->is_ungood_user($nid);
      //タイプによる絞り込み
      if(Input::post("type")) {
        $type = Input::post("type");
        //お気に入りの場合
        if($type == 'bookmark') {
          $uid = Input::post("uid");
          $result = $node->get_good_favorite_node($uid);
          $nids = array_keys($result);
          if(!in_array($nid, $nids)) {
            unset($list[$id]);
            continue;
          }
        }
      }
      //公開範囲の絞り込み
      if(Input::post("uid")) {
        $public_scope = $data["public_scope"];
        $node_uid = $data["uid"];
        $current_user_uid = Input::post("uid");
        //全公開の場合
        if($public_scope == 1) {
          //何もしない
        //フォロワーのみの場合
        } else if($public_scope == 2) {
          $follow = $user->get_follower($current_user_uid);
          $followers = array_keys($follow);
          if(!in_array($node_uid, $followers) && $node_uid != $current_user_uid) {
            unset($list[$id]);
            continue;
          }
        //自分のみの場合
        } else if($public_scope == 3) {
          if($node_uid != $current_user_uid) {
            unset($list[$id]);
            continue;
          }
        //それ以外の場合
        } else {
          //何もしない
        }
      }
      if(isset($list[$id]["comment"]) && count($list[$id]["comment"]) >= 1) {
        //コメントに画像をつける
        foreach($list[$id]["comment"] as $comment_id => $comment_data) {
          $list[$id]["comment"][$comment_id]["picture"] = $this->image[$comment_data["picture"]];
        }
        
      }
      
      //タグが付いている場合
      $tags = $node->get_node_tags($nid);
      
      if(isset($tags["tag_id"])) {
        $list[$id]["tag_id"] = $tags["tag_id"];
        $list[$id]["tag_name"] = $tags["name"];
        //タグによる絞り込みの場合
        if(Input::post("tag_id")) {
          $post_tag_id = Input::post("tag_id");
          if($post_tag_id != $tags["tag_id"]) {
            unset($list[$id]);
          }
        }
      }
    }
    $next = false;
    if(count($list) >= $limit) {
      $next = true;
    }
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
    echo (json_encode(["list" => $list, "next" => $next], true));
    exit(1);
  }
  
  public function action_theme() {
    $result = $this->component->getTheme();
    $list = $this->add_image_field($result, "image_id", "image");
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
    echo (json_encode(["list" => $list], true));
    exit(1);
  }
  
  public function action_user_list()
  {
    $curl = Request::forge('http://myportal.monster/api/user_list/all', 'curl');
    $response = $curl->execute()->response();
    $list = \Format::forge($response->body,'json')->to_array();
    foreach($list as $id => $data) {
      $list[$id]["picture"] = $this->image[$data["picture"]];
    }
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
    echo (json_encode($list, true));
    exit(1);
  }
  
  public function action_post()
  { 
    $node = new Model_Node();
    if(Input::post()) {
      $uid = Input::post('uid');
      \Log::error($uid);
      $title = Input::post('post_title');
      \Log::error($title);
      $body  = Input::post('post_body');
      $navigation = Input::post('navigation');
      $public_scope = Input::post('public_scope');
      if(isset($_FILES['post_file'])) {
        $file = $_FILES['post_file']['tmp_name'];
        $cfile = new CURLFile($_FILES["post_file"]["tmp_name"],'image/jpeg','test_name');
      }
      $edit_nid = null;
      if(Input::post('edit_nid')) {
        $edit_nid = Input::post('edit_nid');
      }
      if(isset($edit_nid) && strlen($edit_nid)) {
        $post_type = "edit";
        $curl = Request::forge('http://myportal.monster/api/node_edit', 'curl');
        $is_edit = true;
      } else {
        $post_type = "regist";
        \Log::info("node_post!!!");
        $curl = Request::forge('http://myportal.monster/api/node_post', 'curl');
      }
      $params = array('title' => $title, 'body' => $body, 'uid' => $uid, 'navigation' => $navigation,'nid' => $edit_nid, 'public_scope' => $public_scope);
      if(isset($_FILES['post_file'])) {
        $params["image"] = $file;
      }
      
      /*
      fileUpload
      $result = $this->commonModel->select("my_page_content", ['uid' => $uid, 'delta' => $delta, 'sort' => $idx], false);
      $this->commonModel->update("my_page_content", ['title' => $params["title".$idx], 'type' => $params["type".$idx]], 
      $this->commonModel->insert("my_page_content_table_data", [
        'content_id' => $result["content_id"], 
        'sort' => $sort_idx, 
        'title' => $params["table_title".$table_idx."_".$sort_idx], 
        'sentence' => $params["table_sentence".$table_idx."_".$sort_idx], 
      ]);
      */
      $commonModel = new Model_Common();
      $i = 1;
      while(isset($_FILES['field_image'.$i]['tmp_name'])) {
        $params['field_image'.$i] = $_FILES['field_image'.$i]['tmp_name'];
        $i++;
      }
      $i = 1;
      $caption = Input::post('caption'.$i);
      while(isset($caption)) {
        $params['caption'.$i] = Input::post('caption'.$i);
        $i++;
        $caption = Input::post('caption'.$i);
      }
      \Log::error(print_r($params, true));
      $curl->set_option(CURLOPT_RETURNTRANSFER,true); 
      $curl->set_option(CURLOPT_BINARYTRANSFER,true);
      $curl->set_header('Content-Type','multipart/form-data');
      $curl->set_params($params);
      $response = $curl->execute()->response();
      $result = \Format::forge($response->body,'json')->to_array();
      
      
      \Log::error("tags if before");
      if(Input::post('tags')) {
        \Log::error("tags if in");
        $tag_id = Input::post('tags');
        \Log::error($tag_id);
        
        $nid  = $result["nid"];
        \Log::error($nid);
        $node->regist_node_tags($nid, $tag_id);
      }
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");

      $data = array('post_type' => $post_type,'title' => $title, 'body' => $body, 'nid' => $result["nid"]);
      if(isset($cfile)) {
        $data['file'] = $cfile;
      }
      echo json_encode($data, true);
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
    
    foreach($comment_list as $nid => $comment_data) {
      foreach($comment_data as $id => $data) {
        if(isset($data["picture"])) {
          $comment_list[$nid][$id]['picture'] = $this->image[$data["picture"]];
        }
      }
    }
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
    echo (json_encode($comment_list, true));
    exit(1);
	}
  
  public function action_activity_count()
  {
    $user = new Model_User();
    if(Input::post('uid')) {
      $uid = Input::post('uid');
    } else {
      $uid = Input::get('uid');
    }
    $count = $user->get_user_activity_no_read_count($uid);
    echo (json_encode(["count" => $count], true));
    exit(1);
  }

  public function action_good()
  {
    if(Input::post()) {
      $node = new Model_Node();
      $user = new Model_User();
      $uid = Input::post('uid');
      $author_uid = Input::post('author_uid');
      $author = $user->get_user($author_uid);
      $return = ["nid" => Input::post('nid'), "uid" => Input::post('uid'), "author_uid" => $author_uid];
      try {
        \Log::error("aaaaaaaaaaaaaaaaaa");
        $node->add_good_user(Input::post('uid'), Input::post('nid'));
        $type = 'good';
        $result = $user->get_user_activity($author_uid, Input::post('uid'), $type, Input::post('nid'));
        \Log::error("bbbbbbbbbbbbbbbbbbbbbbbbbbbbbb");
        \Log::error(print_r($result, true));
        if($result !== false && count($result) >= 1) {
          $user->update_user_activity($author_uid, Input::post('uid'), $type, Input::post('nid'), 0);
          \Log::error("cccccccccccccccccccccccccc");
        } else {
          if($uid == $author_uid) {
            $message = '自分の<a href="#'. Input::post("nid"). '" class="activity">'.Input::post("title").'</a>にいいねを付けました。';
          } else {
            $message = '<a href="#" class="ac_user_name" data-uid="'.$uid.'">'. Input::post("user_name"). '</a>さんが<a href="#" class="ac_user_name" data-uid="'.$author_uid.'">'.$author["user_name"].'</a>さんの<a href="#'. Input::post("nid"). '" class="activity">'.Input::post("title").'</a>にいいねを付けました';
          }
          $return["message"] = strip_tags($message);
          \Log::error(print_r($author, true));
          $user->insert_user_activity(Input::post('author_uid'), Input::post('uid'), $message, $type, Input::post('nid'));
          $user->update_user_new_activity(Input::post('author_uid'), 1);
          
          \Log::error("ddddddddddddddddddddddddddddddddddddd");
        }
        
      } catch (Exception $e) {
        \Log::error("The exception was created on line: " . $e->getLine());
        \Log::error($e->getMessage());
        $node->delete_good_user(Input::post('uid'), Input::post('nid'));
        $type = 'cancel';
        $user->update_user_activity(Input::post('author_uid'), Input::post('uid'), "good", Input::post('nid'), 1);
        $user->update_user_new_activity_no_read(Input::post('uid'));
      }
      $return["type"] = $type;
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode($return, true);
      exit(1);
    }
  }
  
  public function action_ungood()
  {
    if(Input::post()) {
      $node = new Model_Node();
      $user = new Model_User();
      $uid = Input::post('uid');
      $author_uid = Input::post('author_uid');
      $author = $user->get_user($author_uid);
      $return = ["nid" => Input::post('nid'), "uid" => Input::post('uid'), "author_uid" => $author_uid];
      try {
        \Log::error("aaaaaaaaaaaaaaaaaa");
        $node->add_ungood_user(Input::post('uid'), Input::post('nid'));
        $type = 'ungood';
        $result = $user->get_user_activity(Input::post('author_uid'), Input::post('uid'), $type, Input::post('nid'));
        \Log::error("bbbbbbbbbbbbbbbbbbbbbbbbbbbbbb");
        \Log::error(print_r($result, true));
        if($result !== false && count($result) >= 1) {
          $user->update_user_activity(Input::post('author_uid'), Input::post('uid'), $type, Input::post('nid'), 0);  //が貴方の<a href='#node-". Input::post('nid'). "' class='activity'>".Input::post('title')."</a>にいいねしました。"
          \Log::error("cccccccccccccccccccccccccc");
        } else {
          \Log::error($uid);
          \Log::error($author_uid);
          if($uid == $author_uid) {
            \Log::error("if");
            $message = '自分の<a href="#'. Input::post("nid"). '" class="activity">'.Input::post("title").'</a>にだめねを付けました。';
          } else {
            \Log::error("else");
            $message = '<a href="#" class="ac_user_name" data-uid="'.$uid.'">'. Input::post("user_name"). '</a>さんが<a href="#" class="ac_user_name" data-uid="'.$author_uid.'">'.$author["user_name"].'</a>さんの<a href="#'. Input::post("nid"). '" class="activity">'.Input::post("title").'</a>にだめねと言っています。';
          }
          $return["message"] = strip_tags($message);
          \Log::error(print_r($author, true));
          $user->insert_user_activity(Input::post('author_uid'), Input::post('uid'), $message, $type, Input::post('nid'));
          $user->update_user_new_activity(Input::post('author_uid'), 1);
          \Log::error("ddddddddddddddddddddddddddddddddddddd");
        }
        
      } catch (Exception $e) {
        \Log::error("The exception was created on line: " . $e->getLine());
        \Log::error($e->getMessage());
        $node->delete_ungood_user(Input::post('uid'), Input::post('nid'));
        $type = 'cancel';
        $user->update_user_activity(Input::post('author_uid'), Input::post('uid'), "good", Input::post('nid'), 1);
        $user->update_user_new_activity_no_read(Input::post('uid'));
      }
      $return["type"] = $type;
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode($return, true);
      exit(1);
    }
  }
  
  public function action_favorite_node()
  {
    if(Input::post()) {
      $node = new Model_Node();
      $user = new Model_User();
      $uid = Input::post('uid');
      $author_uid = Input::post('author_uid');
      $author = $user->get_user($author_uid);
      $return = ["nid" => Input::post('nid'), "uid" => Input::post('uid'), "author_uid" => $author_uid];
      try {
        \Log::error("aaaaaaaaaaaaaaaaaa");
        $node->add_favorite_node(Input::post('uid'), Input::post('nid'));
        $type = 'favorite';

        $result = $user->get_user_activity(Input::post('author_uid'), Input::post('uid'), $type, Input::post('nid'));
        \Log::error("bbbbbbbbbbbbbbbbbbbbbbbbbbbbbb");
        \Log::error(print_r($result, true));
        if($result !== false && count($result) >= 1) {
          $user->update_user_activity(Input::post('author_uid'), Input::post('uid'), $type, Input::post('nid'), 0);  //が貴方の<a href='#node-". Input::post('nid'). "' class='activity'>".Input::post('title')."</a>にいいねしました。"
          \Log::error("cccccccccccccccccccccccccc");
        } else {
          if($uid == $author_uid) {
            $message = '自分の<a href="#'. Input::post("nid"). '" class="activity">'.Input::post("title").'</a>をお気に入りに登録しました。';
          } else {
            $message = '<a href="#" class="ac_user_name" data-uid="'.$uid.'">'. Input::post("user_name"). '</a>さんが<a href="#" class="ac_user_name" data-uid="'.$author_uid.'">'.$author["user_name"].'</a>さんの<a href="#'. Input::post("nid"). '" class="activity">'.Input::post("title").'</a>をお気に入りに登録しました。';
          }
          $return["message"] = strip_tags($message);
          $author = $user->get_user($author_uid);
          \Log::error(print_r($author, true));
          $user->insert_user_activity(Input::post('author_uid'), $uid, $message, $type, Input::post('nid'));
          $user->update_user_new_activity(Input::post('author_uid'), 1);
          \Log::error("ddddddddddddddddddddddddddddddddddddd");
        }
        
      } catch (Exception $e) {
        \Log::error("The exception was created on line: " . $e->getLine());
        \Log::error($e->getMessage());
        $node->delete_favorite_node(Input::post('uid'), Input::post('nid'));
        $type = 'cancel';
        $user->update_user_activity(Input::post('author_uid'), Input::post('uid'), "favorite", Input::post('nid'), 1);
        $user->update_user_new_activity_no_read(Input::post('uid'));
      }
      $return["type"] = $type;
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode($return, true);
      exit(1);
    }
  }
  
  public function action_follow()
  {
     if(Input::post()) {
       $user = new Model_User();
       $follow_user = $user->get_user(Input::post('uid'));
       $return = ["follow_uid" => Input::post('follow_uid')];
       try {
         $user->add_follow_user(Input::post('uid'), Input::post('follow_uid'));
         $type = 'follow';
         $result = $user->get_user_activity(Input::post('follow_uid'), Input::post('uid'), $type, Input::post('follow_uid'));
         
         if(count($result) >= 1) {
           $user->update_user_activity(Input::post('follow_uid'), Input::post('uid'), $type, Input::post('follow_uid'), 0);
         } else {
           $message = '<a href="#" class="ac_user_name" data-uid="'.$follow_user["uid"].'">'.$follow_user["user_name"].'</a>さんが貴方をフォローしました。';
           $return["message"] = strip_tags($message);
           $user->insert_user_activity(Input::post('follow_uid'), Input::post('uid'), '<a href="#" class="ac_user_name" data-uid="'.$follow_user["uid"].'">'.$follow_user["user_name"].'</a>さんが貴方をフォローしました。', $type, Input::post('follow_uid'));
         }
       } catch (Exception $e) {
         \Log::error($e->getMessage());
         $user->delete_follow_user(Input::post('uid'), Input::post('follow_uid'));
         $user->update_user_activity(Input::post('follow_uid'), Input::post('uid'), "follow", Input::post('follow_uid'), 1);
         $type = 'cancel';
      }
      $return['type'] = $type;
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode($return, true);
      exit(1);
    }
  }
  
  public function action_good_user()
  {
      $result = [];
      if(Input::post()) {
        $node = new Model_Node();
        $nid = Input::post('nid');
        $result = $node->get_good_user($nid, $this->image);
        $return = ["users" => $result, "type" => "good"];
      }
      
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode($return, true);
      exit(1);
  }
  
  public function action_ungood_user()
  {
      $result = [];
      if(Input::post()) {
        $node = new Model_Node();
        $nid = Input::post('nid');
        $result = $node->get_ungood_user($nid, $this->image);
        $return = ["users" => $result, "type" => "ungood"];
      }
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode($return, true);
      exit(1);
  }
  
/*
    if(Input::post()) {
      $uid = Input::post('uid');
      $node = new Model_Node();
      $user = new Model_User();
      try {
        $node->add_favorite_node(Input::post('uid'), Input::post('nid'));
        $type = 'favorite';
        $result = $user->get_user_activity(Input::post('author_uid'), Input::post('uid'), $type, Input::post('nid'));
        if(count($result) >= 1) {
          \Log::error("update_user_activity");
          $user->update_user_activity(Input::post('author_uid'), Input::post('uid'), $type, Input::post('nid'), 0);
        } else {
          //$user->insert_user_activity(29, Input::post('uid'), Input::post('user_name')."さんが貴方の<a href=\"#node-". Input::post('nid'). "\" class=\"activity\">".Input::post('title')."</a>にお気に入りに登録しました。", $type, Input::post('nid'));
          $user->insert_user_activity(Input::post('author_uid'), Input::post('uid'), '<a href="#" class="ac_user_name" data-uid="'.$uid.'">'. Input::post("user_name"). '</a>さんが貴方の<a href="#'. Input::post("nid"). '" class="activity">'.Input::post("title").'</a>をお気に入りに登録しました。', $type, Input::post('nid'));
          $user->update_user_new_activity(Input::post('author_uid'), 1);
        }
      } catch (Exception $e) {
        \Log::error($e->getMessage());
        $node->delete_favorite_node(Input::post('uid'), Input::post('nid'));
        $type = 'cancel';
        $user->update_user_activity(Input::post('author_uid'), Input::post('uid'), "favorite", Input::post('nid'), 1);
        $user->update_user_new_activity_no_read(Input::post('uid'));
      }
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode(array('nid' => Input::post('nid'), 'type' => $type), true);
      exit(1);
    }
  */
  
  public function action_post_comment()
  {
    $user = new Model_User();
    if(Input::post()) {
      $uid = Input::post('uid');
      $author_uid = Input::post('author_uid');
      $author = $user->get_user($author_uid);
      $comment = Input::post('comment');
      $nid = Input::post('nid');
      $curl = Request::forge('http://myportal.monster/api/post_comment', 'curl');
      $curl->set_params(array('nid' => $nid, 'uid' => $uid, 'comment' => $comment));
      $response = $curl->execute()->response();
      $result = \Format::forge($response->body,'json')->to_array();
      \Log::error(print_r(Input::post(), true));
      $type = "comment";
      if($author_uid == $uid) {
        $message = '自分の<a href="#'. Input::post("nid"). '" class="activity">'.Input::post("title").'</a>にコメントしました。';
      } else {
        $message = '<a href="#" class="ac_user_name" data-uid="'.$uid.'">'. Input::post("user_name"). '</a>さんが<a href="#" class="ac_user_name" data-uid="'.$author_uid.'">'.$author["user_name"].'</a>さんの<a href="#'. Input::post("nid"). '" class="activity">'.Input::post("title").'</a>にコメントしました。';
      }  
      $user->insert_user_activity($author_uid, Input::post('uid'), $message, $type, Input::post('nid'));
      $message = strip_tags($message);
      //$user->insert_user_activity(29, Input::post('uid'), Input::post('user_name')."さんが".Input::post('title')."にコメントしました。", $type, Input::post('nid'));
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode(array('uid' => $uid, 'comment' => $comment, 'cid' => $result['cid'], 'nid' => $nid, 'message' => $message, 'author_uid' => $author_uid), true);
      exit(1);
      
    }
  }
  
  public function action_activity() 
  {
    if(Input::post('uid')) {
       $uid = Input::post('uid');
    } else {
       $uid = Input::get('uid');
    }
    $user = new Model_User();
    $activity = $user->get_user_activity_by_uid($uid);
    if($activity !== false) {
      foreach($activity as $id => $data) {
        $activity[$id]["picture"] = $this->image[$data['picture']];
        $activity[$id]["updated"] = date('Y/m/d h:i', $data['updated']);
      }
    }
    $new_activity = $user->get_user_new_activity($uid);
    $my_activity = $user->get_user_activity_by_from_uid($uid);
    if($activity !== false) {
      foreach($my_activity as $id => $data) {
        $my_activity[$id]["picture"] = $this->image[$data['picture']];
        $my_activity[$id]["updated"] = date('Y/m/d h:i', $data['updated']);
      }
    }
    $data = ["notification" => ["activity" => $activity, "new_activity" => $new_activity], "my_activity" => ["activity" => $my_activity]];
    $this->returnJson($data);
  }
  
  public function action_read_activity()
  {
    $uid = Input::post('uid');
    $user = new Model_User();
    $result = $user->update_user_new_activity($uid, 0);
    $user->update_user_all_read_activity($uid);
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($result, true);
    exit(1);
  }
  
  public function action_comment_delete() {
    $cid = Input::post('cid');
    $node = new Model_Node();
    $node->delete_comment($cid);
    echo json_encode(array('cid' => $cid), true);
    exit(1);
  }
  
  public function action_node_delete() {
    $nid = Input::post('nid');
    $curl = Request::forge('http://myportal.monster/api/node_delete', 'curl');
    $curl->set_params(array('nid' => $nid));
    $response = $curl->execute()->response();
    $result = \Format::forge($response->body,'json')->to_array();
    echo json_encode(array('nid' => $nid), true);
    exit(1);
  }
  
  public function action_add_tags() {
    \Log::error("action_add_tags");
    $uid = Input::post('add_tag_uid');
    $tag_names = [];
    for($i=0; $i<8; $i++) {
      $tag_idx = $i+1;
      $tag_names[$i] = Input::post('post_tag'. $tag_idx);
      \Log::error('post_tag'. $tag_idx);
      \Log::error($tag_names[$i]);
    }
    $user = new Model_User();
    $user->add_tags($uid, $tag_names);
    $result = ["tag_names" => $tag_names];
    $tags = $user->search_tags($uid);
    $result["tags"] = $tags;
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($result, true);
    exit(1);
  }
  
  public function action_mail() {
    if(Input::get('user_name')) {
      $user_name = Input::get('user_name');
    } else {
      $user_name = Input::post('user_name');
    }
    $email = Email::forge();
    $email->from('info@myportal.monster', 'MyPORTAL');
    if(isset($user_name)) {
      $email->to('saiyuuki.world.life@gmail.com', $user_name. 'さん');
    } 
    
    $email->subject('MyPORTALへご登録ありがとうございます');
    $email->body('アプリを起動しご登録頂いたアカウントでログインしMyPORTALをお楽しみください。');
    
    try
    {
        $email->send();
    }
    catch(\EmailValidationFailedException $e)
    {
        echo "バリデーションが失敗したとき";
    }
    catch(\EmailSendingFailedException $e)
    {
        echo "メールアドレスが間違ってる可能性があります";
    }
  }
  
  public function action_test() {
    \Log::error("test!!!!!!!!!!!!!");
//    header("Access-Control-Allow-Origin: *");
//    header("Content-Type: application/json; charset=utf-8");
//    echo json_encode(["result" => "success"], true);
    $this->template->title = 'Example Page';
    $this->template->content = View::forge('api/form', [], false)->auto_filter(false);
    return $this->template;
    exit(1);
    
  }
  
  public function action_phpinfo() {
    phpinfo();
    exit(1);
  }

	/**
	 * A typical "Hello, Bob!" type example.  This uses a Presenter to
	 * show how to use them.
	 *
	 * @access  public
	 * @return  Response--*
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
