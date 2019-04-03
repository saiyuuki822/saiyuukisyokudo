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
    $sql = 'SELECT comment.entity_id AS nid, comment.uid, body.comment_body_value AS body, user_name.field_user_name_value AS user_name, picture.user_picture_target_id AS picture FROM `comment_field_data` AS comment';
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
}
