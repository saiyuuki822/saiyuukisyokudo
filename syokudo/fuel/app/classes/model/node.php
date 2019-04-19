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
  public function add_good_user($uid, $nid) {
    $sql = "INSERT INTO node__field_good_user(entity_id,field_good_user_target_id) VALUES(".$nid.",".$uid.")";
    $query = DB::query($sql);
    $result = $query->execute();
    return $result;
  }

  /**
   * ユーザー参照
   */
  public function add_ungood_user($uid, $nid) {
    $sql = "INSERT INTO node__field_ungood_user(entity_id,field_ungood_user_target_id) VALUES(".$nid.",".$uid.")";
    $query = DB::query($sql);
    $result = $query->execute();
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
  
  /**
   * コンテンツ参照
   */
  public function add_favorite_node($uid, $nid) {
    $sql = "INSERT INTO user__field_favorite_node(entity_id,field_favorite_node_target_id) VALUES(".$uid.",".$nid.")";
    $query = DB::query($sql);
    $result = $query->execute();
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
