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
      \Log::error("edit_uid:".$edit_uid);
      $file = $_FILES['picture']['tmp_name'];
      $cfile = new CURLFile($_FILES["picture"]["tmp_name"],'image/jpeg','picture');
      $curl = Request::forge('http://syokudo.jpn.org/api/user_post', 'curl');
      $curl->set_option(CURLOPT_RETURNTRANSFER,true);
      $curl->set_option(CURLOPT_BINARYTRANSFER,true);
      $curl->set_header('Content-Type','multipart/form-data');
      $params = array('name' => $name, 'user_name' => $user_name, 'user_body' => $user_body, 'mail' => $mail, 'password' => $password, 'picture' => $file, 'uid' => $edit_uid);
      $curl->set_params($params);
      \Log::error("user_postuser_postuser_post");
      $response = $curl->execute()->response();
      \Log::error("user_postuser_postuser_post2");
      $result = \Format::forge($response->body,'json')->to_array();
      \Log::error(print_r($result));
      if($result !== false && strlen($mail) >= 1 && filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        if(!(isset($edit_uid) && strlen($edit_uid) >= 1)) {
          $email = Email::forge();
          $email->from('info@syokudo.jpn.org', 'MyPORTAL');
          if(isset($user_name)) {
            $email->to($mail, $user_name. 'さん');
          } 
          $email->subject('MyPORTALへご登録ありがとうございます');
          try
          {
            $user = new Model_User();
            $pass = $user->get_user_activate_password($name);
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
    $user = new Model_User();
    $uid = $user->get_user_uid_by_activate_password($pass);
    if($uid !== false) {
      $user->update_user_activate_status($uid, 1);
    }
    
    Response::redirect('/index.php/login?activate=1', 'refresh', 200);
    
    return $this->template;
  }

  public function action_login()
  {
      $name = Input::post('name');
      $password = Input::post('password');
      $curl = Request::forge('http://syokudo.jpn.org/api/user_login', 'curl');
      $curl->set_params(array('name' => $name, 'pass' => $password));
      $response = $curl->execute()->response();
      $result = \Format::forge($response->body,'json')->to_array();
    
      \Log::error(print_r($result, true));
    
      if(isset($result['uid'])) {
        $user = new Model_User();
        $login_user = $user->get_user($result['uid']);
        // メインナビゲーションを取得
        $curl = Request::forge('http://syokudo.jpn.org/api/navigation/'.$login_user['uid'], 'curl');
        $response = $curl->execute()->response();
        $list = \Format::forge($response->body,'json')->to_array();
        $login_user["navigation"] = $list;
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
      } else {
        $login_user = ["uid" => 0];
      }
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
    $node = new Model_Node();
    $user = new Model_User();
    $curl = Request::forge('http://syokudo.jpn.org/api/node_list/'.$type. "/".$entity_id, 'curl');
    $response = $curl->execute()->response();
    $list = \Format::forge($response->body,'json')->to_array();
    
    \Log::error("http://syokudo.jpn.org/api/node_list/".$type. "/".$entity_id);
    \Log::error(print_r($list, true));
    
    foreach($list as $nid => $data) {
      $list[$nid]["created"] = date('Y/m/d h:i', $data["created"]);
      $list[$nid]["is_good_user"] = $node->is_good_user($nid);
      $list[$nid]["is_ungood_user"] = $node->is_ungood_user($nid);
      //文章と画像
      foreach($data["image_caption"] as $iamge_caption_idx => $image_caption) {
        $list[$nid]["image_caption"][$iamge_caption_idx]["field_image"] = $this->image[$image_caption["field_image"]];
      }
      //タイプによる絞り込み
      if(Input::post("type")) {
        $type = Input::post("type");
        //お気に入りの場合
        if($type == 'bookmark') {
          $uid = Input::post("uid");
          \Log::error("bookmark!!!");
          $result = $node->get_good_favorite_node($uid);
          $nids = array_keys($result);
          \Log::error(print_r($nids, true));
          if(!in_array($nid, $nids)) {
            unset($list[$nid]);
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
            unset($list[$nid]);
            continue;
          }
        //自分のみの場合
        } else if($public_scope == 3) {
          if($node_uid != $current_user_uid) {
            unset($list[$nid]);
            continue;
          }
        //それ以外の場合
        } else {
          //何もしない
        }
      }
      if(isset($list[$nid]["comment"]) && count($list[$nid]["comment"]) >= 1) {
        //コメントに画像をつける
        foreach($list[$nid]["comment"] as $comment_id => $comment_data) {
          $list[$nid]["comment"][$comment_id]["picture"] = $this->image[$comment_data["picture"]];
        }
        
      }
      
      //タグが付いている場合
      $tags = $node->get_node_tags($nid);
      if(isset($tags["tag_id"])) {
        $list[$nid]["tag_id"] = $tags["tag_id"];
        $list[$nid]["tag_name"] = $tags["name"];
        //タグによる絞り込みの場合
        if(Input::post("tag_id")) {
          $post_tag_id = Input::post("tag_id");
          if($post_tag_id != $tags["tag_id"]) {
            unset($list[$nid]);
          }
        }
       }
    }
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
    echo (json_encode($list, true));
    exit(1);
  }
  
  public function action_node_search($type, $entity_id)
  {
    $node = new Model_Node();
    $user = new Model_User();
    $word = Input::post("word");
    $curl = Request::forge('http://syokudo.jpn.org/api/node_search/'.$type. "/".$entity_id. "/".$word, 'curl');
    $response = $curl->execute()->response();
    $list = \Format::forge($response->body,'json')->to_array();
    foreach($list as $nid => $data) {
      $list[$nid]["created"] = date('Y/m/d h:i', $data["created"]);
      \Log::error($nid); 
      $list[$nid]["is_good_user"] = $node->is_good_user($nid);
      $list[$nid]["is_ungood_user"] = $node->is_ungood_user($nid);
      //タイプによる絞り込み
      if(Input::post("type")) {
        $type = Input::post("type");
        //お気に入りの場合
        if($type == 'bookmark') {
          $uid = Input::post("uid");
          \Log::error("bookmark!!!");
          $result = $node->get_good_favorite_node($uid);
          $nids = array_keys($result);
          \Log::error(print_r($nids, true));
          if(!in_array($nid, $nids)) {
            unset($list[$nid]);
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
            unset($list[$nid]);
            continue;
          }
        //自分のみの場合
        } else if($public_scope == 3) {
          if($node_uid != $current_user_uid) {
            unset($list[$nid]);
            continue;
          }
        //それ以外の場合
        } else {
          //何もしない
        }
      }
      if(isset($list[$nid]["comment"]) && count($list[$nid]["comment"]) >= 1) {
        //コメントに画像をつける
        foreach($list[$nid]["comment"] as $comment_id => $comment_data) {
          $list[$nid]["comment"][$comment_id]["picture"] = $this->image[$comment_data["picture"]];
        }
        
      }
      
      //タグが付いている場合
      $tags = $node->get_node_tags($nid);
      if(isset($tags["tag_id"])) {
        $list[$nid]["tag_id"] = $tags["tag_id"];
        $list[$nid]["tag_name"] = $tags["name"];
        //タグによる絞り込みの場合
        if(Input::post("tag_id")) {
          $post_tag_id = Input::post("tag_id");
          if($post_tag_id != $tags["tag_id"]) {
            unset($list[$nid]);
          }
        }
      }
    }
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
    echo (json_encode($list, true));
    exit(1);
  }
  
  public function action_user_list()
  {
    $curl = Request::forge('http://syokudo.jpn.org/api/user_list/all', 'curl');
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
    \Log::error("action_post");
    $node = new Model_Node();
    if(Input::post()) {
      $uid = Input::post('uid');
      \Log::error("uuuuuuuuuuuuuuuuuuuuuuuuu");
      \Log::error($uid);
      \Log::error(print_r(Input::post(), true));
      \Log::error(print_r($_FILES, true));
      $title = Input::post('post_title');
      $body  = Input::post('post_body');
      $navigation = Input::post('navigation');
      $public_scope = Input::post('public_scope');
      $file = $_FILES['post_file']['tmp_name'];
      $cfile = new CURLFile($_FILES["post_file"]["tmp_name"],'image/jpeg','test_name');
      $edit_nid = null;
      if(Input::post('edit_nid')) {
        $edit_nid = Input::post('edit_nid');
      }
      if(isset($edit_nid) && strlen($edit_nid)) {
        $post_type = "edit";
        $curl = Request::forge('http://syokudo.jpn.org/api/node_edit', 'curl');
      } else {
        $post_type = "regist";
        \Log::info("node_post!!!");
        $curl = Request::forge('http://syokudo.jpn.org/api/node_post', 'curl');
      }
      $params = array('title' => $title, 'body' => $body, 'uid' => $uid, 'navigation' => $navigation, 'image' => $file, 'nid' => $edit_nid, 'public_scope' => $public_scope);
      $i = 1;
      while(isset($_FILES['field_image'.$i]['tmp_name'])) {
        $params['field_image'.$i] = $_FILES['field_image'.$i]['tmp_name'];
        \Log::info($params['field_image'.$i]);
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
      echo json_encode(array('post_type' => $post_type,'title' => $title, 'body' => $body, 'file' => $cfile, 'nid' => $result["nid"]), true);
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
  
  public function action_good()
  {
    if(Input::post()) {
      $uid = Input::post('uid');
      $author_uid = Input::post('author_uid');
      
      $node = new Model_Node();
      $user = new Model_User();
      try {
        \Log::error("aaaaaaaaaaaaaaaaaa");
        $node->add_good_user(Input::post('uid'), Input::post('nid'));
        $type = 'good';
        $result = $user->get_user_activity(Input::post('author_uid'), Input::post('uid'), $type, Input::post('nid'));
        \Log::error("bbbbbbbbbbbbbbbbbbbbbbbbbbbbbb");
        \Log::error(print_r($result, true));
        if($result !== false && count($result) >= 1) {
          $user->update_user_activity(Input::post('author_uid'), Input::post('uid'), $type, Input::post('nid'), 0);  //が貴方の<a href='#node-". Input::post('nid'). "' class='activity'>".Input::post('title')."</a>にいいねしました。"
          \Log::error("cccccccccccccccccccccccccc");
        } else {
          $author = $user->get_user($author_uid);
          \Log::error(print_r($author, true));
          $user->insert_user_activity(Input::post('author_uid'), Input::post('uid'), '<a href="#" class="ac_user_name" data-uid="'.$uid.'">'. Input::post("user_name"). '</a>さんが<a href="#" class="ac_user_name" data-uid="'.$author_uid.'">'.$author["user_name"].'</a>さんの<a href="#'. Input::post("nid"). '" class="activity">'.Input::post("title").'</a>にいいねと言っています。', $type, Input::post('nid'));
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
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode(array('nid' => Input::post('nid'), 'type' => $type), true);
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
  
  public function action_ungood()
  {
    if(Input::post()) {
      $uid = Input::post('uid');
      $author_uid = Input::post('author_uid');
      
      $node = new Model_Node();
      $user = new Model_User();
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
          $author = $user->get_user($author_uid);
          \Log::error(print_r($author, true));
          $user->insert_user_activity(Input::post('author_uid'), Input::post('uid'), '<a href="#" class="ac_user_name" data-uid="'.$uid.'">'. Input::post("user_name"). '</a>さんが<a href="#" class="ac_user_name" data-uid="'.$author_uid.'">'.$author["user_name"].'</a>さんの<a href="#'. Input::post("nid"). '" class="activity">'.Input::post("title").'</a>にだめねと言っています。', $type, Input::post('nid'));
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
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode(array('nid' => Input::post('nid'), 'type' => $type), true);
      exit(1);
    }
  }
  
  public function action_favorite_node()
  {
    if(Input::post()) {
      $uid = Input::post('uid');
      $author_uid = Input::post('author_uid');
      
      $node = new Model_Node();
      $user = new Model_User();
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
          $author = $user->get_user($author_uid);
          \Log::error(print_r($author, true));
          $user->insert_user_activity(Input::post('author_uid'), Input::post('uid'), '<a href="#" class="ac_user_name" data-uid="'.$uid.'">'. Input::post("user_name"). '</a>さんが<a href="#" class="ac_user_name" data-uid="'.$author_uid.'">'.$author["user_name"].'</a>さんの<a href="#'. Input::post("nid"). '" class="activity">'.Input::post("title").'</a>をお気に入りに登録しました。', $type, Input::post('nid'));
          $user->update_user_new_activity(Input::post('author_uid'), 1);
          \Log::error("ddddddddddddddddddddddddddddddddddddd");
        }
        
      } catch (Exception $e) {
        \Log::error("The exception was created on line: " . $e->getLine());
        \Log::error($e->getMessage());
        $node->delete_ungood_user(Input::post('uid'), Input::post('nid'));
        $type = 'cancel';
        $user->update_user_activity(Input::post('author_uid'), Input::post('uid'), "favorite", Input::post('nid'), 1);
        $user->update_user_new_activity_no_read(Input::post('uid'));
      }
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode(array('nid' => Input::post('nid'), 'type' => $type), true);
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
  }
  
  public function action_follow()
  {
     if(Input::post()) {
       $user = new Model_User();
       $follow_user = $user->get_user(Input::post('uid'));
       try {
         $user->add_follow_user(Input::post('uid'), Input::post('follow_uid'));
         $type = 'follow';
         $result = $user->get_user_activity(Input::post('follow_uid'), Input::post('uid'), $type, Input::post('follow_uid'));
         if(count($result) >= 1) {
           $user->update_user_activity(Input::post('follow_uid'), Input::post('uid'), $type, Input::post('follow_uid'), 0);
         } else {
           $user->insert_user_activity(Input::post('follow_uid'), Input::post('uid'), '<a href="#" class="ac_user_name" data-uid="'.$follow_user["uid"].'">'.$follow_user["user_name"].'</a>さんが貴方をフォローしました。', $type, Input::post('follow_uid'));
         }
       } catch (Exception $e) {
         \Log::error($e->getMessage());
         $user->delete_follow_user(Input::post('uid'), Input::post('follow_uid'));
         $user->update_user_activity(Input::post('follow_uid'), Input::post('uid'), "follow", Input::post('follow_uid'), 1);
         $type = 'cancel';
      }
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode(array('follow_uid' => Input::post('follow_uid'), 'type' => $type), true);
      exit(1);
    }
  }
  
  public function action_post_comment()
  {
    $user = new Model_User();
    if(Input::post()) {
      $uid = Input::post('uid');
      $author_uid = Input::post('author_uid');
      $author = $user->get_user($author_uid);
      $comment = Input::post('comment');
      $nid = Input::post('nid');
      $curl = Request::forge('http://syokudo.jpn.org/api/post_comment', 'curl');
      $curl->set_params(array('nid' => $nid, 'uid' => $uid, 'comment' => $comment));
      $response = $curl->execute()->response();
      $result = \Format::forge($response->body,'json')->to_array();
      \Log::error(print_r(Input::post(), true));
      $type = "comment";
      $user->insert_user_activity($author_uid, Input::post('uid'), '<a href="#" class="ac_user_name" data-uid="'.$uid.'">'. Input::post("user_name"). '</a>さんが<a href="#" class="ac_user_name" data-uid="'.$author_uid.'">'.$author["user_name"].'</a>さんの<a href="#'. Input::post("nid"). '" class="activity">'.Input::post("title").'</a>にコメントしました。', $type, Input::post('nid'));
      //$user->insert_user_activity(29, Input::post('uid'), Input::post('user_name')."さんが".Input::post('title')."にコメントしました。", $type, Input::post('nid'));
      header("Access-Control-Allow-Origin: *");
      header("Content-Type: application/json; charset=utf-8");
      echo json_encode(array('uid' => $uid, 'comment' => $comment, 'cid' => $result['cid'], 'nid' => $nid), true);
      exit(1);
      
    }
  }
  
  public function action_activity() 
  {
    $uid = Input::post('uid');
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
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($data, true);
    exit(1);
  }
  
  public function action_read_activity()
  {
    $uid = Input::post('uid');
    $user = new Model_User();
    $result = $user->update_user_new_activity($uid, 0);
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
    $curl = Request::forge('http://syokudo.jpn.org/api/node_delete', 'curl');
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
    $email->from('info@syokudo.jpn.org', 'MyPORTAL');
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
