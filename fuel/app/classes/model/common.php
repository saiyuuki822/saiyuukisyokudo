<?php

class Model_Node extends \Orm\Model
{
  public function field_entity_insert($table_name, $entity_type, $entity_id, $value) {
    $sql = "INSERT INTO ". $table_name ."
      (bundle, entity_id, revision_id, langcode, delta,	field_activity_target_id) VALUES
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
  } 
}