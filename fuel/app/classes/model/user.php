<?php

class Model_User
{
   protected static $_primary_key = array('uid');
  
	protected static $_properties = array(
		'uid',
		'uuid',
		'langcode',
	);


	protected static $_table_name = 'users';
  
  protected static $_has_many = array(
      'user_name' => array(
          'model_to' => 'Model_User__Field_User_Name',
          'key_from' => 'uid',
          'key_to' => 'entity_id',
          'cascade_save' => true,
          'cascade_delete' => true,
        ),
  );
  
  public function get_user($uid = null) {
    $sql = 'SELECT users.uid,users.name,users.mail,user_name.field_user_name_value AS user_name,user_body.field_user_body_value AS user_body, picture.user_picture_target_id AS picture FROM users_field_data users ';
    $sql .= ' INNER JOIN user__field_user_name user_name ON users.uid = user_name.entity_id';
    $sql .= ' INNER JOIN user__field_user_body user_body ON users.uid = user_body.entity_id';
    $sql .= ' INNER JOIN user__user_picture picture ON users.uid = picture.entity_id';
    if(isset($uid)) {
      $sql .= ' WHERE uid='.$uid;
    }
    $query = DB::query($sql);
    $result = $query->execute()->as_array();
    if(!isset($uid)) {
      return $result;
    } else {
      return $result[0];
    }
  }
  
  public function get_user_by_name($name) {
    $sql = 'SELECT users.uid,users.name,users.mail,user_name.field_user_name_value AS user_name,user_body.field_user_body_value AS user_body, picture.user_picture_target_id AS picture FROM users_field_data users ';
    $sql .= ' INNER JOIN user__field_user_name user_name ON users.uid = user_name.entity_id';
    $sql .= ' INNER JOIN user__field_user_body user_body ON users.uid = user_body.entity_id';
    $sql .= ' INNER JOIN user__user_picture picture ON users.uid = picture.entity_id';
    $sql .= ' WHERE name="'.$name. '"';
    $query = DB::query($sql);
    $result = $query->execute()->as_array();
    if(!isset($name)) {
      return $result;
    } else {
      return $result[0];
    }
  }
  
  public function is_follow($uid, $follow_uid) {
    $sql = 'SELECT count(*) FROM user__field_user_follow f WHERE f.entity_id =' . $uid. ' AND f.field_user_follow_target_id = '. $follow_uid;
    $query = DB::query($sql);
    $result = $query->execute()->current();
    if(!empty($result)) {
      return true;
    } else {
      return false;
    }
  }
  
  public function get_user_follow($uid) {
    $sql = 'SELECT f.entity_id AS uid,f.field_user_follow_target_id AS follow_uid, picture.user_picture_target_id AS picture FROM users';
    $sql .= ' INNER JOIN user__field_user_follow f ON users.uid = f.entity_id';
    $sql .= ' INNER JOIN user__user_picture picture ON f.field_user_follow_target_id = picture.entity_id';
    if(isset($uid)) {
      $sql .= ' WHERE users.uid='.$uid;
    }
    $query = DB::query($sql);
    $result = $query->execute()->as_array();
    return $result;
  }
  
  public function get_follower($uid) {
    $sql = 'SELECT f.entity_id AS uid,f.field_user_follow_target_id AS follow_uid, picture.user_picture_target_id AS picture FROM users';
    $sql .= ' INNER JOIN user__field_user_follow f ON users.uid = f.entity_id';
    $sql .= ' INNER JOIN user__user_picture picture ON f.field_user_follow_target_id = picture.entity_id';
    if(isset($uid)) {
      $sql .= ' WHERE f.field_user_follow_target_id='.$uid;
    }
    $query = DB::query($sql);
    $result = $query->execute()->as_array("uid");
    return $result;
  }
  
  public function get_user_good_node($uid) {
    $sql = 'SELECT g.entity_id AS nid, g.field_good_user_target_id AS uid FROM node__field_good_user g';
    if(isset($uid)) {
      $sql .= ' WHERE g.field_good_user_target_id='.$uid;
    }
    \Log::error($sql);
    $query = DB::query($sql);
    $result = $query->execute()->as_array();
    return $result;
  }
  
  public function get_user_ungood_node($uid) {
    $sql = 'SELECT g.entity_id AS nid, g.field_ungood_user_target_id AS uid FROM node__field_ungood_user g';
    if(isset($uid)) {
      $sql .= ' WHERE g.field_ungood_user_target_id='.$uid;
    }
    $query = DB::query($sql);
    $result = $query->execute()->as_array();
    return $result;
  }
  
  public function get_user_favorite_node($uid) {
    $sql = 'SELECT g.entity_id AS uid, g.field_favorite_node_target_id AS nid FROM user__field_favorite_node g';
    if(isset($uid)) {
      $sql .= ' WHERE g.entity_id='.$uid;
    }
    $query = DB::query($sql);
    $result = $query->execute()->as_array();
    return $result;
  }
  
  public function get_user_favorite_url($uid) {
    $sql = 'SELECT n.field_link_name_value AS link_name, u.field_link_url_value AS link_url FROM user__field_user_favorite_url f';
    $sql .= ' INNER JOIN paragraph__field_link_name n ON f.field_user_favorite_url_target_id = n.entity_id';
    $sql .= ' INNER JOIN paragraph__field_link_url u  ON f.field_user_favorite_url_target_id = u.entity_id';
    if(isset($uid)) {
      $sql .= ' WHERE f.entity_id ='.$uid;
    }
    $query = DB::query($sql);
    $result = $query->execute()->as_array();
    return $result;
  } 
  
  public function add_follow_user($current_uid, $follow_uid) {
    $sql = "INSERT INTO user__field_user_follow(delta,entity_id,field_user_follow_target_id) VALUES(".$follow_uid.",".$current_uid.",".$follow_uid.")";
    $query = DB::query($sql);
    $result = $query->execute();
    return $result;
  }
  
  public function delete_follow_user($current_uid, $follow_uid) {
    $sql = "DELETE FROM user__field_user_follow WHERE entity_id = ".$current_uid. " AND field_user_follow_target_id =" .$follow_uid;
    $query = DB::query($sql);
    $result = $query->execute();
    return $result;
  }
  
  public function insert_user_activity($uid, $from_uid, $content, $type, $target_id) {
    try {
      $sql = "INSERT INTO my_activity(uid,from_uid,content,type,target_id,updated,read_flg) VALUES(".$uid.",".$from_uid.",'".$content."','".$type."',".$target_id.",".time().",0)";
      $query = DB::query($sql);
      $result = $query->execute();
    } catch(Exception $e) {
      \Log::error($e->getMessage());
    }
    return $result;
  }
  
  public function update_user_activity($uid, $from_uid, $type, $target_id, $delete_flg) {
    try {
      $sql = "UPDATE my_activity SET delete_flg = ". $delete_flg.", updated = ". time(). " WHERE uid =".$uid." AND from_uid = ".$from_uid." AND type ='".$type."' AND target_id = ".$target_id;
      $query = DB::query($sql);
      $result = $query->execute();
    } catch(Exception $e) {
      \Log::error($e->getMessage());
    }
    return $result;
  }
  
  public function update_user_all_read_activity($uid) {
    try {
      $sql = "UPDATE my_activity SET read_flg = 1 WHERE uid =".$uid;
      $query = DB::query($sql);
      $result = $query->execute();
    } catch(Exception $e) {
      \Log::error($e->getMessage());
    }
    return $result;
  }
  
  public function get_user_activity($uid, $from_uid, $type, $target_id) {
    try {
      $sql = "SELECT * FROM my_activity WHERE uid =".$uid." AND from_uid = ".$from_uid." AND type ='".$type."' AND target_id = ".$target_id. " ORDER BY updated ASC";
      $query = DB::query($sql);
      $result = $query->execute()->as_array();
    } catch(Exception $e) {
      \Log::error($e->getMessage());
    }
    return $result;
  }
  
  public function delete_user_activity($uid, $from_uid, $type, $target_id) {
    try {
      $sql = "DELETE FROM my_activity WHERE uid =".$uid." AND from_uid = ".$from_uid." AND type ='".$type."' AND target_id = ".$target_id;
      $query = DB::query($sql);
      $result = $query->execute();
    } catch(Exception $e) {
      \Log::error($e->getMessage());
    }
  }
  
  public function get_user_new_activity($uid) {
    try {
      $sql = "SELECT * FROM my_user_new_activity WHERE uid =".$uid;
      $query = DB::query($sql);
      $result = $query->execute()->current();
      DB::last_query();
    } catch(Exception $e) {
      \Log::error(DB::last_query());
      return false;
    }
    return $result;
  }
  
  public function insert_user_new_activity($uid) {
    try {
      \Log::debug("insert_user_new_activity");
      $sql = "INSERT INTO my_user_new_activity(uid,new_flg,updated) VALUES(".$uid.",1,".time().")";
      $query = DB::query($sql);
      $result = $query->execute();
    } catch(Exception $e) {
      \Log::error($e->getMessage());
    }
    return $result;
  }
  
  public function update_user_new_activity($uid, $new_flg) {
    try {
      $result = $this->get_user_new_activity($uid);
      if( !empty($result)) {
        $sql = "UPDATE my_user_new_activity SET new_flg = ". $new_flg.", updated = ". time(). " WHERE uid =".$uid;
      } else {
        $sql = $this->insert_user_new_activity($uid, 1);
      }
      $query = DB::query($sql);
      $result = $query->execute();
    } catch(Exception $e) {
      \Log::error($e->getMessage());
      \Log::error("exception");
      return false;
    }
    return $result;
  }
  
  public function get_user_activity_by_uid($uid) {
    try {
      $sql = "SELECT my_activity.*,my_activity.updated,picture.user_picture_target_id AS picture FROM my_activity";
      $sql .= ' INNER JOIN user__user_picture picture ON my_activity.from_uid = picture.entity_id';
      $sql .= " WHERE uid =".$uid. " ORDER BY updated DESC";
      //自分から自分を除外、いったんフロントでエラーが出るのでコメントアウト
      //$sql .= " WHERE uid =".$uid. "AND uid != from_uid ORDER BY updated DESC";
      \Log::error($sql);
      $query = DB::query($sql);
      $result = $query->execute()->as_array();
    } catch(Exception $e) {
      \Log::error($e->getMessage());
      $result = false;
    }
    return $result;
  }
  
  public function get_user_activity_by_from_uid($uid) {
    try {
      $sql = "SELECT my_activity.*,my_activity.updated,picture.user_picture_target_id AS picture FROM my_activity";
      $sql .= ' INNER JOIN user__user_picture picture ON my_activity.uid = picture.entity_id';
      $sql .= " WHERE from_uid =".$uid. " ORDER BY updated DESC";
      $query = DB::query($sql);
      $result = $query->execute()->as_array();
    } catch(Exception $e) {
      \Log::error($e->getMessage());
      $result = false;
    }
    return $result;
  }
  
  public function get_user_activity_no_read($uid, $time = null) {
    try {
      $sql = "SELECT activity_id FROM my_activity";
      $sql .= " WHERE uid =".$uid. " AND read_flg = 0 AND delete_flg = 0";
      if(isset($time)) {
        $sql .= " AND updated >= ". $time;
      }
      $query = DB::query($sql);
      $result = $query->execute()->as_array();
    } catch(Exception $e) {
      \Log::error($e->getMessage());
      return false;
    }
    return $result;
  }
  
  public function get_user_activity_no_read_count($uid, $time = null) {
    $result = $this->get_user_activity_no_read($uid);
    if(empty($result)) {
      return 0;
    } else {
      return count($result);
    }
    
  }
  
  public function update_user_activity_by_id($activity_id) {
    try {
      $sql = "UPDATE my_activity SET delete_flg = 0 WHERE activity_id = ".$activity_id;
      $query = DB::query($sql);
      $result = $query->execute();
    } catch(Exception $e) {
      \Log::error($e->getMessage());
    }
    return $result;
  }
                                             
  public function update_user_new_activity_no_read($uid) {
    $user_new_activity = $this->get_user_new_activity($uid);
    $result = $this->get_user_activity_no_read($uid, $user_new_activity["updated"]);
    if($result != false && count($result) < 1) {
      $this->update_user_new_activity($uid, 0);
    }
  }
  
  public function add_tags($uid, $tag_names) {
    foreach($tag_names as $key => $value) {
       $result = $this->search_tags($uid, $key+1);
       if(strlen($value) >= 1) {
         if($result !== false && count($result) >= 1) {
           $this->update_tags($uid, $value, $key+1);
         } else {
           $this->insert_tags($uid, $value, $key+1);
         }
         \Log::error(DB::last_query());
       }
    }
  }
  
  public function search_tags($uid, $sort = null) {
    try {
      $sql = "SELECT * FROM my_tags WHERE uid = ". $uid ;
      if(isset($sort)) {
        $sql .=  " AND sort = ". $sort;
      }
      $sql .= " ORDER BY sort ASC";
      $query = DB::query($sql);
      $result = $query->execute()->as_array();
    } catch(Exception $e) {
      \Log::error($e->getMessage());
      return false;
    }
    return $result;
  }
  
  public function insert_tags($uid, $tag_name, $sort) {
    try {
        $sql = "INSERT INTO my_tags(uid,name,sort) VALUES(".$uid. ",'" .$tag_name. "',". $sort. ")";
        $query = DB::query($sql);
        $result = $query->execute();
    } catch(Exception $e) {
      \Log::error($e->getMessage());
      $result = false;
    }
    return $result;
  }
  
  
  public function update_tags($uid, $tag_name, $sort) {
    try {
      $sql = "UPDATE my_tags SET uid = ". $uid. ",name='". $tag_name. "' WHERE uid = ".$uid. " AND sort=". $sort;
      $query = DB::query($sql);
      $result = $query->execute();
    } catch(Exception $e) {
      \Log::error($e->getMessage());
    }
    return $result;
  }
  
  public function get_user_activate_password($name) {
    try {
      $sql = "SELECT pass FROM users_field_data WHERE name = '". $name. "'";
      $query = DB::query($sql);
      $result = $query->execute()->current();
    } catch(Exception $e) {
      \Log::error($e->getMessage());
      return false;
    }
    return $result["pass"];
  }
  
  
  public function get_user_uid_by_activate_password($pass) {
    try {
      $sql = "SELECT uid FROM my_pass_token WHERE token = '". $pass. "'";
      
      $query = DB::query($sql);
      $result = $query->execute()->current();
      
      if(empty($result)) {
        return false;
      }
    } catch(Exception $e) {
      \Log::error($e->getMessage());
      return false;
    }
    
    return $result["uid"];
  }
  
  public function update_user_activate_status($uid, $status) {
    try {
      $sql = "UPDATE users_field_data SET status = ". $status. " WHERE uid = ". $uid;
      $query = DB::query($sql);
      $result = $query->execute();
    } catch(Exception $e) {
    }
    return $result;
  }
  
  public function insert_text_field($field_name, $uid, $value) {
    try {
      $result = DB::insert("user__".$field_name)->set(
        array(
          'bundle'=>'user',
          'deleted'=>0,
          'entity_id'=>$uid,
          'revision_id'=>$uid,  
          'delta' => 0, 
          $field_name."_value" => $value
        )
      )->execute();
    } catch(Exception $e) {
      \Log::error($e->getMessage());
      return false;
    }
    return $result;
  }
  public function update_text_field($field_name, $uid, $value) {
    $result = DB::update("user__".$field_name)->set(
      array(
        'bundle'=>'user',
        'deleted'=>0,
        'entity_id'=>$uid,
        'revision_id'=>$uid,  
        'delta' => 0, 
        $field_name."_value" => $value
      )
    )->where('entity_id', $uid)->execute();
    return $result;
  }
  public function select_text_field($field_name, $entity_type, $entity_id, $return_array = false) {
    if($return_array) {
      $result = DB::select($field_name."_value")->from($entity_type."__".$field_name)->where('entity_id', $entity_id)->execute()->as_array($field_name."_value");
      if(count($result) >= 1) {
        return array_keys($result);
      } else {
        return false;
      }
    } else {
      $result = DB::select()->from($entity_type."__".$field_name)->where('entity_id', $entity_id)->execute()->current();
      return $result[$field_name."_value"];
    }

  }
  
  public function insert_image_field($field_name, $uid, $value) {
    $result = DB::insert("user__".$field_name)->set(
      array(
        'bundle'=>'user',
        'deleted'=>0,
        'entity_id'=>$uid,
        'revision_id'=>$uid,
        'langcode' => 'ja',
        'delta' => 0, 
        $field_name."_target_id" => $value,
        $field_name.'_alt' => 'test',
        $field_name.'_width' => 400,
        $field_name.'_height' => 100,
      )
    )->execute();
    return $result;
  }
  
  public function update_image_field($field_name, $uid, $value) {
    $result = DB::update("user__".$field_name)->set(
      array(
        'bundle'=>'user',
        'deleted'=>0,
        'entity_id'=>$uid,
        'revision_id'=>$uid,
        'langcode' => 'ja',
        'delta' => 0, 
        $field_name."_target_id" => $value
      )
    )->where('entity_id', $uid)->execute(); 
    return $result;
  }
  
  public function select_image_field($field_name, $entity_type, $entity_id, $return_array = false) {
    
    if($return_array) {
      $result = DB::select($field_name."_target_id")->from($entity_type."__".$field_name)->where('entity_id', $entity_id)->execute()->as_array($field_name."_value");
      if(count($result) >= 1) {
        return array_keys($result);
      } else {
        return false;
      }
    } else {
      $result = DB::select()->from($entity_type."__".$field_name)->where('entity_id', $entity_id)->execute()->current();
      if(is_null($result)) {
        return false;
      }
      
      return $result[$field_name."_target_id"];
    }

  }
}
