<?php

class Model_Node extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'nid',
		'vid',
		'type',
		'uuid',
		'langcode',
	);


	protected static $_table_name = 'nodes';
    
  public function get_node($nid = null) {
    $sql = 'SELECT node.nid,nd.uid,nd.title,body.body_value AS body,image.field_image_target_id AS image, user_name.field_user_name_value AS user_name, user_site_url.field_user_site_url_value AS user_site_url, picture.user_picture_target_id  AS picture FROM `node`';
    $sql .= 'INNER JOIN node_field_data nd ON node.nid = nd.nid';
    $sql .= ' INNER JOIN node__field_image image ON node.nid = image.entity_id';
    $sql .= ' INNER JOIN node__body body ON node.nid = body.entity_id';
    $sql .= ' INNER JOIN user__field_user_name user_name ON nd.uid = user_name.entity_id';
    $sql .= ' INNER JOIN user__field_user_site_url user_site_url ON nd.uid = user_site_url.entity_id';
    $sql .= ' INNER JOIN user__user_picture picture ON nd.uid = picture.entity_id';
    $sql .= ' INNER JOIN paragraphs_item_field_data field_image ON node.nid = field_image.parent_id';
    $sql .= ' ORDER BY node.nid ASC';
    
    if(isset($uid)) {
      $sql .= ' WHERE nid='.$nid;
    }
    $query = DB::query($sql);
    $result = $query->execute();
    
    $nid = 0;
    $list = array();
    foreach($result as $id => $data) {
      if($nid == $data["nid"]) {
        $list[$nid]['image'][] = $data["image"];
      } else {
        $nid = $data["nid"];
        $list[$nid] = $data;
        $list[$nid]["image"] = array($data["image"]);
      }
    }
    return $list;
  }
  
  public function get_node_comment_list() {
    $sql = 'SELECT comment.cid,comment.entity_id AS nid, comment.uid, body.comment_body_value AS body, user_name.field_user_name_value AS user_name, picture.user_picture_target_id AS picture FROM `comment_field_data` AS comment';
    $sql .= ' INNER JOIN comment__comment_body body ON comment.cid = body.entity_id';
    $sql .= ' INNER JOIN user__field_user_name user_name ON comment.uid = user_name.entity_id';
    $sql .= ' INNER JOIN user__user_picture picture ON comment.uid = picture.entity_id';
    $sql .= ' ORDER BY comment.cid ASC';
    $query = DB::query($sql);
    $result = $query->execute()->as_array();
    $nid = 0;
    $list = array();
    foreach($result as $id => $data) {
      if($nid == $data["nid"]) {
        $list[$nid][] = $data;
      } else {
        $nid = $data["nid"];
        $list[$nid] = array($data);
      }
    }
    
    return $list;
  }
  
  
  public function delete_comment($cid) {
    $sql = "DELETE FROM comment__comment_body WHERE entity_id = ".$cid;
    $query = DB::query($sql);
    $result = $query->execute();
    $sql = "DELETE FROM comment_field_data WHERE cid = ".$cid;
    $query = DB::query($sql);
    $result = $query->execute();
    $sql = "DELETE FROM comment WHERE cid = ".$cid;
    $query = DB::query($sql);
    $result = $query->execute();
    return $result;
  }
  
  /**
   * 数値型
   *
   */
  public function add_good($nid) {
    $sql = "UPDATE node__field_good SET field_good_value = field_good_value + 1 WHERE entity_id = ".$nid;
    $query = DB::query($sql);
    $result = $query->execute();
    return $result;
  }
  
  /**
   * ユーザー参照
   */
  public function get_good_user($nid, $image) {
//    $sql = "SELECT g.entity_id AS nid, g.field_good_user_target_id AS uid,user_name.field_user_name_value AS user_name,user_site_url.field_user_site_url_value AS user_site_url,user_body.field_user_body_value AS user_body,picture.user_picture_target_id AS picture FROM node__field_good_user g";
    $sql = "SELECT g.entity_id AS nid, g.field_good_user_target_id AS uid,user_name.field_user_name_value AS user_name,user_body.field_user_body_value AS user_body,picture.user_picture_target_id AS picture FROM node__field_good_user g";
    $sql .= ' INNER JOIN user__user_picture picture ON g.field_good_user_target_id = picture.entity_id';
    $sql .= ' INNER JOIN user__field_user_name user_name ON g.field_good_user_target_id = user_name.entity_id';
    $sql .= ' INNER JOIN user__field_user_body user_body ON g.field_good_user_target_id = user_body.entity_id';
    //$sql .= ' INNER JOIN user__field_user_site_url user_site_url ON g.field_good_user_target_id = user_site_url.entity_id';
    $sql .= ' WHERE g.entity_id = '. $nid;
    $query = DB::query($sql);
    $result = $query->execute()->as_array();
    foreach($result as $key => $value) {
      if(mb_strlen($result[$key]['user_body']) >= 50) {
        $result[$key]['user_body'] = mb_substr($value['user_body'], 0, 30). "...";
      }
      $result[$key]['picture'] = $image[$value['picture']];
    }
    return $result;
  }
  
  public function is_good_user($nid) {
    $sql = "SELECT field_good_user_target_id FROM node__field_good_user g WHERE g.entity_id = ". $nid;
    $query = DB::query($sql);
    $result = $query->execute()->as_array();
    if(empty($result)) {
      return false;
    }
    return true;
  }
  
  public function is_ungood_user($nid) {
    $sql = "SELECT field_ungood_user_target_id FROM node__field_ungood_user g WHERE g.entity_id = ". $nid;
    $query = DB::query($sql);
    $result = $query->execute()->as_array();
    if(empty($result)) {
      return false;
    }
    return true;
  }
  
  /**
   * ユーザー参照
   */
  public function get_ungood_user($nid, $image) {
    //$sql = "SELECT g.entity_id AS nid, g.field_ungood_user_target_id AS uid,user_name.field_user_name_value AS user_name,user_site_url.field_user_site_url_value AS user_site_url,user_body.field_user_body_value AS user_body,picture.user_picture_target_id AS picture FROM node__field_ungood_user g";
    $sql = "SELECT g.entity_id AS nid, g.field_ungood_user_target_id AS uid,user_name.field_user_name_value AS user_name,user_body.field_user_body_value AS user_body,picture.user_picture_target_id AS picture FROM node__field_ungood_user g";
    $sql .= ' INNER JOIN user__user_picture picture ON g.field_ungood_user_target_id = picture.entity_id';
    $sql .= ' INNER JOIN user__field_user_name user_name ON g.field_ungood_user_target_id = user_name.entity_id';
    $sql .= ' INNER JOIN user__field_user_body user_body ON g.field_ungood_user_target_id = user_body.entity_id';
    //$sql .= ' INNER JOIN user__field_user_site_url user_site_url ON g.field_ungood_user_target_id = user_site_url.entity_id';
    $sql .= ' WHERE g.entity_id = '. $nid;
    $query = DB::query($sql);
    $result = $query->execute()->as_array();
    foreach($result as $key => $value) {
      $result[$key]['picture'] = $image[$value['picture']];
    }
    return $result;
  }
  
  public function get_good_user_data($nid, $uid=null) {
    $sql = "SELECT g.entity_id AS nid, field_good_user_target_id AS uid FROM node__field_good_user g";
    $sql .= ' WHERE g.entity_id = '. $nid;
    if(isset($uid)) {
      $sql .= ' AND g.field_good_user_target_id = '. $uid;
    }
    $query = DB::query($sql);
    $result = $query->execute()->as_array();
    return $result;
  }
  
  /**
   * ユーザー参照
   */
  public function add_good_user($uid, $nid) {
    $sql = "INSERT INTO node__field_good_user(delta,entity_id,field_good_user_target_id) VALUES(".$uid.",".$nid.",".$uid.")";
    $query = DB::query($sql);
    $result = $query->execute();
    return $result;
  }

    public function get_ungood_user_data($nid, $uid=null) {
    $sql = "SELECT g.entity_id AS nid, field_ungood_user_target_id AS uid FROM node__field_ungood_user g";
    $sql .= ' WHERE g.entity_id = '. $nid;
    if(isset($uid)) {
      $sql .= ' AND g.field_ungood_user_target_id = '. $uid;
    }
    $query = DB::query($sql);
    $result = $query->execute()->as_array();
    return $result;
  }
  
  /**
   * ユーザー参照
   */
  public function add_ungood_user($uid, $nid) {
    $sql = "INSERT INTO node__field_ungood_user(delta,entity_id,field_ungood_user_target_id) VALUES(".$uid.",".$nid.",".$uid.")";
    $query = DB::query($sql);
    $result = $query->execute();
    return $result;
  }
  
  public function regist_node_tags($nid, $tag_id) {
    $result = $this->get_node_tags($nid);
    if(!empty($result)) {
      $this->update_node_tags($nid, $tag_id);
    } else {
      $this->add_node_tags($nid, $tag_id);
    }
  }
  
  public function add_node_tags($nid, $tag_id) {
    $sql = "INSERT INTO my_node_tags(nid, tag_id) VALUES(".$nid.",".$tag_id.")";
    $query = DB::query($sql);
    $result = $query->execute();
    return $result;
  }
  
  public function update_node_tags($nid, $tag_id) {
    $sql = "UPDATE my_node_tags SET tag_id = ". $tag_id. " WHERE nid = ".$nid;
    $query = DB::query($sql);
    $result = $query->execute();
    return $result;
  }
  
  public function get_node_tags($nid) {
    $sql = 'SELECT t.tag_id, t.nid, g.name FROM my_node_tags t';
    $sql .= ' INNER JOIN my_tags g ON t.tag_id = g.tag_id';
    if(isset($nid)) {
      $sql .= ' WHERE t.nid='.$nid;
    }
    $query = DB::query($sql);
    $result = $query->execute()->current();
    return $result;
  }

  /**
   * ユーザー参照
   */
  public function delete_good_user($uid, $nid) {
    $sql = "DELETE FROM node__field_good_user WHERE entity_id = ".$nid. " AND field_good_user_target_id =" .$uid;
    $query = DB::query($sql);
    $result = $query->execute();
    return $result;
  }
  
  /**
   * ユーザー参照
   */
  public function delete_ungood_user($uid, $nid) {
    $sql = "DELETE FROM node__field_ungood_user WHERE entity_id = ".$nid. " AND field_ungood_user_target_id =" .$uid;
    $query = DB::query($sql);
    $result = $query->execute();
    return $result;
  }
  
  
  public function get_good_favorite_node($uid, $nid=null) {
    $sql = "SELECT f.entity_id AS uid, f.field_favorite_node_target_id AS nid FROM user__field_favorite_node f";
    $sql .= ' WHERE f.entity_id = '. $uid;
    if(isset($nid)) {
      $sql .= ' AND f.field_favorite_node_target_id = '. $nid;
    }
    $query = DB::query($sql);
    $result = $query->execute()->as_array('nid');
    return $result;
  }

  /**
   * コンテンツ参照
   */
  public function add_favorite_node($uid, $nid) {
    $sql = "SELECT * FROM user__field_favorite_node WHERE entity_id = ". $uid. "&& field_favorite_node_target_id = ". $nid;
    $query = DB::query($sql);
    $result = $query->execute()->as_array();
    if(count($result) < 1) {
      $delta = 0;
      $sql = "SELECT MAX(delta) + 1 AS delta FROM user__field_favorite_node";
      $query = DB::query($sql);
      $result = $query->execute()->current();
      \Log::error(print_r($result, true));
      if(isset($result["delta"])) {
        $delta = $result["delta"];
      }
      $sql = "INSERT INTO user__field_favorite_node
        (bundle,revision_id,entity_id,langcode,delta,field_favorite_node_target_id) VALUES
        (
          'user',"
          .$uid.","
          .$uid.","
          ."'ja',"
          . $delta.","
          .$nid.
        ")";
      $query = DB::query($sql);
      $result = $query->execute();
    } else {
      throw new Exception();
    }
    return $result;
  }
  
  /**
   * コンテンツ参照
   */
  public function delete_favorite_node($uid, $nid) {
    $sql = "DELETE FROM user__field_favorite_node WHERE entity_id = ".$uid. " AND field_favorite_node_target_id =" .$nid;
    $query = DB::query($sql);
    $result = $query->execute();
    return $result;
  }
}
