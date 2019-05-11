<?php

namespace Drupal\my_node;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class NodeModel {
    public function get_node_list($id_type=null, $id = null) {
        $query = db_select('node', 'n');
        $query->join('node_field_data', 'f', 'n.nid = f.nid');
        $query->join('node__body', 'b', 'n.nid = b.entity_id');
        $query->join('users', 'u', 'f.uid = u.uid');
        $query->join('user__field_user_name', 'm', 'f.uid = m.entity_id');
        $query->join('node__field_page_menu', 'pm', 'n.nid = pm.entity_id');
        
        $query->fields('n',array('nid'));
        $query->fields('f', array('title','uid','created'));
        $query->fields('b', array('body_value'));
        $query->fields('m', array('field_user_name_value'));
        $query->addField('pm', 'field_page_menu_target_id', 'page_menu');
//        $query->fields('u', array('name'));
        if(isset($id_type) && $id_type == 1 && isset($id)) {
          $query->condition('n.nid', $id, "=");
        } else if(isset($id_type) && $id_type == 2 && isset($id)) {
          $query->condition('pm.field_page_menu_target_id', $id, "=");
        }
        $query->condition('n.type', "article", "=");
        $query->orderBy('nid');
        $result = $query->execute()->fetchAll();
        return $result;
    }
  
    public function get_comment_list($nid) {
    //$sql = 'SELECT comment.cid,comment.entity_id AS nid, comment.uid, body.comment_body_value AS body, user_name.field_user_name_value AS user_name, picture.user_picture_target_id AS picture FROM `comment_field_data` AS comment';
    //$sql .= ' INNER JOIN comment__comment_body body ON comment.cid = body.entity_id';
    //$sql .= ' INNER JOIN user__field_user_name user_name ON comment.uid = user_name.entity_id';
    //$sql .= ' INNER JOIN user__user_picture picture ON comment.uid = picture.entity_id';
    //$sql .= ' ORDER BY comment.cid ASC';
      $query = db_select('comment_field_data', 'c');
      $query->join('comment__comment_body', 'b', 'c.cid = b.entity_id');
      $query->join('user__field_user_name', 'n', 'c.uid = n.entity_id');
      $query->join('user__user_picture', 'p', 'c.uid = p.entity_id');
      $query->fields('c',array('cid'));
      $query->addField('c', 'entity_id', 'nid');
      $query->addField('c', 'created');
      $query->addField('b', 'comment_body_value', 'body');
      $query->addField('n', 'field_user_name_value', 'user_name');
      $query->addField('p', 'user_picture_target_id', 'picture');
      $query->condition('c.entity_id', $nid, "=");
      $query->orderBy('c.created');
      $result = $query->execute()->fetchAll();
      foreach($result as $id => $value) {
        $result[$id]->created = date('Y/m/d h:i', $value->created);
      }
      
      return $result;
    }
  
    public function get_page_data($tid) {
        $query = db_select('node', 'n');
        $query->join('node_field_data', 'f', 'n.nid = f.nid');
        $query->join('node__body', 'b', 'n.nid = b.entity_id');
        $query->join('users', 'u', 'f.uid = u.uid');
        $query->join('user__field_user_name', 'm', 'f.uid = m.entity_id');
        $query->join('node__field_page_menu', 'mn', 'n.nid = mn.entity_id');
        $query->fields('n',array('nid'));
        $query->fields('f', array('title','uid'));
        $query->fields('b', array('body_value'));
        $query->fields('m', array('field_user_name_value'));
        $query->condition('n.type', "page", "=");
        $query->condition('mn.field_page_menu_target_id', $tid, "=");
        $query->orderBy('nid');
        $result = $query->execute()->fetchAll();
        return $result;
    }
}

