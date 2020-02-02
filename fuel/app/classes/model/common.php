<?php

class Model_Common
{
  public function insert($table_name, $params) {
    try {
      $query = DB::insert($table_name)->set($params);
      $connection = Database_Connection::instance();
      $sql = $query->compile($connection);
      \Log::error($sql);
      $result = $query->execute();
      return $result[0];
    } catch(Exception $e) {
      \Log::error($e->getMessage());
      return false;
    }
  } 
  
  public function update($table_name, $params, $condition) {
    try {
      $query = DB::update($table_name)->set($params);
      foreach($condition as $id => $value) {
        $query->where($id, $value);
      }
      $connection = Database_Connection::instance();
      $sql = $query->compile($connection);
      $result = $query->execute();
      \Log::error($sql);
      return $result;
    } catch(Exception $e) {
      \Log::error($e->getMessage());
      return false;
    }
  }
  
  public function delete($table_name, $condition) {
    try {
      $query = DB::delete($table_name);
      foreach($condition as $id => $value) {
        $query->where($id, $value);
      }
      $connection = Database_Connection::instance();
      $sql = $query->compile($connection);
      $result = $query->execute();
      \Log::error($sql);
    } catch(Exception $e) {
      \Log::error($e->getMessage());
      return false;
    }
  } 
  
  public function select($table_name, $condition, $is_list = false, $key_name = null, $order_by = null) {
    try {
      $query = DB::select()->from($table_name);
      if(isset($condition["in"])) {
        foreach($condition["in"] as $column_name => $column_data) { 
          $query->where($column_name, 'in', $column_data);
        }
        unset($condition["in"]);
      }
      foreach($condition as $id => $value) {
        $query->where($id, $value);
      }
      if(isset($order_by)) {
        foreach($order_by as $id => $value) {
          $query->order_by($id, $value);
        }
      }
      $connection = Database_Connection::instance();
      $sql = $query->compile($connection);
      if($is_list) {
        $result = $query->execute()->as_array($key_name);
      } else {
        $result = $query->execute()->current();
      } 
      \Log::error(DB::last_query());
      if(is_null($result) || empty($result)) {
        return [];
      }
       
      return $result;
    } catch(Exception $e) {
      \Log::error($e->getMessage());
      return false;
    }
  }
}