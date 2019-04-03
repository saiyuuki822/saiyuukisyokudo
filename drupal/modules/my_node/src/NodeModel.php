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

