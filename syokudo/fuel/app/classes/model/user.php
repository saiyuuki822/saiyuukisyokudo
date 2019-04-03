<?php

class Model_User extends \Orm\Model
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
    $sql = 'SELECT users.uid,user_name.field_user_name_value AS user_name,user_body.field_user_body_value AS user_body, picture.user_picture_target_id AS picture FROM `users`';
    $sql .= ' INNER JOIN user__field_user_name user_name ON users.uid = user_name.entity_id';
    $sql .= ' INNER JOIN user__field_user_body user_body ON users.uid = user_body.entity_id';
    $sql .= ' INNER JOIN user__user_picture picture ON users.uid = picture.entity_id';
    if(isset($uid)) {
      $sql .= ' WHERE uid='.$uid;
    }
    $query = DB::query($sql);
    $result = $query->execute()->as_array();
    return $result;
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

}
