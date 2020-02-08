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
class Component_Common
{
  private $userModel;
  private $nodeModel;
  private $commonModel;
  
  public function __construct() {
    $this->userModel = new Model_User();
    $this->commonModel = new Model_Common();
  }
  
  public function getUser($uid) {
    $result = $this->userModel->get_user($uid);
    $field_user_site_name = $this->userModel->select_text_field('field_user_site_name', 'user', $uid);
    if($field_user_site_name !== false) {
      $result["user_site_name"] = $field_user_site_name;
    }
    $field_user_site_logo = $this->userModel->select_image_field('field_user_site_logo', 'user', $uid);
    if($field_user_site_logo !== false) {
      $result["user_site_logo"] = $field_user_site_logo;
    }
    $field_user_site_main_image1 = $this->userModel->select_image_field('field_user_site_main_image1', 'user', $uid);
    if($field_user_site_main_image1 !== false) {
      $result["user_site_main_image1"] = $field_user_site_main_image1;
    }
    $field_user_site_top_image = $this->userModel->select_image_field('field_user_site_top_image', 'user', $uid);
    if($field_user_site_top_image !== false) {
      $result["user_site_top_image"] = $field_user_site_top_image;
    }
    $my_navigation = $this->commonModel->select("my_navigation", ['uid' => $uid], true, 'delta');
    if($my_navigation !== false) {
      $result["my_navigation"] = $my_navigation;
    } else {
      $result["my_navigation"] = [];
    }
    $my_tags = $this->commonModel->select("my_tags", ['uid' => $uid], true, 'tag_id');
    if($my_tags !== false) {
      $result["my_tags"] = $my_tags;
    }
    $my_nav_tags_data = $this->commonModel->select("my_navigation_tags", ['uid' => $uid], true);
    $my_nav_tags = [];
    if($my_nav_tags_data !== false) {
      foreach($my_nav_tags_data as $id => $nav_tags) {
        $my_nav_tags[$nav_tags["delta"]][] = $nav_tags["tag_id"];
      }
      $result["my_nav_tags"] = $my_nav_tags;
    }
    $my_page_content = $this->commonModel->select("my_page_content", ['uid' => $uid], true, null, ["delta" => "asc", "sort" => "asc"]);
    $result["caption_image"] = [];
    $result["page_content"] = [];
    if(isset($my_page_content) && $my_page_content !== false) {
      foreach($my_page_content as $id => $data) {
        if(isset($result["page_content"][$data["delta"]][0])) {
          $keys = array_keys($result["page_content"][$data["delta"]]);
          //$idx = max($keys);
          $idx = $idx + 1;
          $result["page_content"][$data["delta"]][$idx] = $data;
        } else {
          $idx = 0;
          $result["page_content"][$data["delta"]][$idx] = $data;
        }
        
        $caption_image = $this->commonModel->select("my_page_content_caption_image", ["content_id" => $data["content_id"]], false);
        if($caption_image !== false) {
          $result["page_content"][$data["delta"]][$idx]["caption_image"][$data["content_id"]] = $caption_image;
        }
        $table_data = $this->commonModel->select("my_page_content_table_data", ["content_id" => $data["content_id"]], true, 'sort');
        if($table_data !== false) {
          $result["page_content"][$data["delta"]][$idx]["table"][$data["content_id"]] = $table_data;
        }
      }
    }
    $my_sub_menu = $this->commonModel->select("my_page_sub_menu", ['uid' => $uid], true, null, ["sort" => "asc"]);
    $result["my_sub_menu"] = [];
    if(isset($my_sub_menu) && $my_sub_menu !== false) {
      
      foreach($my_sub_menu as $id => $data) {
        $result["my_sub_menu"][$id]["title"] = $data["title"];
        $result["my_sub_menu"][$id]["links"] = $this->commonModel->select("my_page_sub_menu_links", ['sub_menu_id' => $data["sub_menu_id"]], true, null, ["sort" => "asc"]);
      }
    }
    $result["my_user_theme"] = $this->commonModel->select("my_user_theme", ['uid' => $uid], false, null);
    $result["my_theme_color"] = $this->commonModel->select("my_theme_color", ['color_id' => $result["my_user_theme"]["theme_color"]], false, null);
    $result["my_theme"] = $this->commonModel->select("my_theme", ['theme_id' => $result["my_user_theme"]["theme_id"]], false, null);
    
    return $result;
  }
  
  public function saveSettingsBasic($uid, $params, $files) {
    
    try {
      $field_user_site_name = $this->userModel->select_text_field('field_user_site_name', 'user', $uid);
      if($field_user_site_name !== false && isset($field_user_site_name)) {
        $this->userModel->update_text_field('field_user_site_name', $uid, $params["user_site_name"]);
      } else {
        $this->userModel->insert_text_field('field_user_site_name', $uid, $params["user_site_name"]);
      }
      
      if($files) {
        $file_upload_result = $this->fileUpload($files);
      }
      
      foreach($file_upload_result as $file_id => $file) {
         $field_image = $this->userModel->select_image_field('field_'.$file_id, 'user', $uid);
         if($field_image !== false) {
           $this->userModel->update_image_field('field_'.$file_id, $uid, $file["fid"]);
         } else {
           $this->userModel->insert_image_field('field_'.$file_id, $uid, $file["fid"]);
         }
      }
      //$this->commonModel->delete('my_navigation_tags', ["uid" => $uid]);
      foreach($params as $id => $value) {
        if(strpos($id, 'nav_name') !== false) {
          $idx = preg_replace('/[^0-9]/', '', $id);
          $result = $this->commonModel->select("my_navigation", ['uid' => $uid, 'delta' => $idx]);
          if($result !== false && !empty($result)) {
            $this->commonModel->update("my_navigation", ['name' => $value], ['uid' => $uid, 'delta' => $idx]);
          } else {
            $this->commonModel->insert("my_navigation", ['uid' => $uid, 'name' => $value, 'delta' => $idx]);
          }
        }
        if(strpos($id, 'nav_type') !== false) {
          $idx = preg_replace('/[^0-9]/', '', $id);
          $result = $this->commonModel->select("my_navigation", ['uid' => $uid, 'delta' => $idx]);
          if($result !== false) {
            $this->commonModel->update("my_navigation", ['type' => $value], ['uid' => $uid, 'delta' => $idx]);
          } else {
            $this->commonModel->insert("my_navigation", ['uid' => $uid, 'type' => $value, 'delta' => $idx]);
          }
        }
        //if(strpos($id, 'nav_tags') !== false) {
        //  $idx = preg_replace('/[^0-9]/', '', $id);
        //  foreach($params[$id] as $tag_id) {
        //    $this->commonModel->insert("my_navigation_tags", ['navigation_id' => $tag_id, 'uid' => $uid, 'tag_id' => $tag_id]);
        //  }
        //}
      }
      return ["save_data" => $params, "upload_result" => $file_upload_result];
    } catch(Exception $e) {
      return false;
    }
  }
  
  public function saveSettingsHome($uid, $params, $files) {
    try {
      $file_upload_result = $this->fileUpload($files);
      
      foreach($file_upload_result as $file_id => $file) {
         $field_image = $this->userModel->select_image_field('field_'.$file_id, 'user', $uid);
         if($field_image !== false) {
           $this->userModel->update_image_field('field_'.$file_id, $uid, $file["fid"]);
         } else {
           $this->userModel->insert_image_field('field_'.$file_id, $uid, $file["fid"]);
         }
      }
      return ["save_data" => $params, "upload_result" => $file_upload_result];
    } catch(Exception $e) {
      \Log::error($e->getMessage());
      return false;
    }
  }
  
  public function saveSettingsContent($uid, $params, $files) {
    try {
      $uid = $params["uid"];
      $delta = $params["delta"];
      $tags_id = $params["tags_id"];
      $this->commonModel->delete('my_navigation_tags', ["uid" => $uid, "delta" => $delta]);
      foreach($tags_id as $value) {
        $this->commonModel->insert("my_navigation_tags", ['tag_id' => $value, 'uid' => $uid, 'delta' => $delta]);
      }
      return ["save_data" => $params];
    } catch(Exception $e) {
      \Log::error($e->getMessage());
      return false;
    }
  }
  
  public function saveSettingsPageContent($uid, $params, $files) {
      $uid = $params["uid"];
      $delta = $params["delta"];
//    if(isset($params["delete_sort"])) {
      foreach($params["delete_sort"] as $value) {
        if($value !== "0") {
          $delete_target = $this->commonModel->select("my_page_content", ['uid' => $uid, 'delta' => $delta, 'sort' => $value], true, 'content_id');
          foreach($delete_target as $content_id => $data) {
            $this->commonModel->delete('my_page_content_caption_image', ["content_id" => $content_id]);
            $this->commonModel->delete('my_page_content', ["content_id" => $content_id]);
          }
        }
      }
    $file_upload_result = $this->fileUpload($files);
    $idx = 1;
    while((isset($params["title".$idx]) && strlen($params["title".$idx]) >= 1) && isset($params["type".$idx]) && strlen($params["type".$idx]) >= 1) {
      $result = $this->commonModel->select("my_page_content", ['uid' => $uid, 'delta' => $delta, 'sort' => $idx], false);
      if(count($result) >= 1) {
        $this->commonModel->update("my_page_content", ['title' => $params["title".$idx], 'type' => $params["type".$idx]], 
        [
           'uid' => $uid, 
           'delta' => $delta,
           'sort' => $idx
        ]);
        if($params["type".$idx] == 'right_image_caption' || $params["type".$idx] == 'left_image_caption' || $params["type".$idx] == 'only_sentence') {
          $data = [
            'caption' => $params["sentence".$idx], 
          ];
          if(isset($file_upload_result["image".$idx])) {
            $data["image_id"] = $file_upload_result["image".$idx]["fid"];
          }
          $this->commonModel->update("my_page_content_caption_image", $data, ['content_id' => $result["content_id"]]);
        } else if($params["type".$idx] == 'table') {
           $table_idx = $idx;
           $sort_idx = 1;
           //add 10.28
           $table_data = $this->commonModel->delete("my_page_content_table_data", ['content_id' => $result["content_id"]]);
           while(isset($params["table_title".$table_idx."_".$sort_idx]) && strlen($params["table_title".$table_idx."_".$sort_idx]) >= 1 && isset($params["table_sentence".$table_idx."_".$sort_idx]) && strlen($params["table_sentence".$table_idx."_".$sort_idx]) >= 1) {
             //$table_data = $this->commonModel->select("my_page_content_table_data", ['content_id' => $result["content_id"], 'sort' => $sort_idx], false);
             //if(count($table_data) >= 1) {
             // $save_params = ['title' => $params["table_title".$table_idx."_".$sort_idx], 'sentence' => $params["table_sentence".$table_idx."_".$sort_idx]];
             // $condition = ['content_id' => $result["content_id"], 'sort' => $sort_idx];
             // $this->commonModel->update("my_page_content_table_data", $save_params, $condition);
             // } else {
              $this->commonModel->insert("my_page_content_table_data", [
                'content_id' => $result["content_id"], 
                'sort' => $sort_idx, 
                'title' => $params["table_title".$table_idx."_".$sort_idx], 
                'sentence' => $params["table_sentence".$table_idx."_".$sort_idx], 
              ]);
             //}
              $sort_idx = $sort_idx + 1;
           }
        }
      } else {
        $content_id = $this->commonModel->insert("my_page_content", [
          'uid' => $uid, 
          'delta' => $delta, 
          'title' => $params["title".$idx], 
          'type' => $params["type".$idx], 
          'sort' => $idx, 
          'created' => time(), 
          'updated' => time()
        ]);
        if($params["type".$idx] == 'right_image_caption' || $params["type".$idx] == 'left_image_caption' || $params["type".$idx] == 'only_sentence') {
          $data = [
            'content_id' => $content_id,
            'caption' => $params["sentence".$idx], 
          ];
          if(isset($file_upload_result["image".$idx])) {
            $data["image_id"] = $file_upload_result["image".$idx]["fid"];
          }
          $this->commonModel->insert("my_page_content_caption_image", $data);
          
          
          /*
          if(isset($file_upload_result["image".$idx])) {
            $this->commonModel->insert("my_page_content_caption_image", [
              'content_id' => $content_id, 
              'caption' => $params["sentence".$idx], 
              'image_id' => $file_upload_result["image".$idx]["fid"]
            ]);
          }
          */
        } else if($params["type".$idx] == 'table') {
          $table_idx = $idx;
          $sort_idx = 1;
          while((isset($params["table_title".$table_idx."_".$sort_idx]) && strlen($params["table_title".$table_idx."_".$sort_idx]) >= 1) && isset($params["table_sentence".$table_idx."_".$sort_idx]) && strlen($params["table_sentence".$table_idx."_".$sort_idx]) >= 1) {
            $this->commonModel->insert("my_page_content_table_data", [
              'content_id' => $content_id, 
              'sort' => $sort_idx, 
              'title' => $params["table_title".$table_idx."_".$sort_idx], 
              'sentence' => $params["table_sentence".$table_idx."_".$sort_idx], 
            ]);
            $sort_idx++;
            
          }
        }
      }
      $idx++;
    }
  }
  
  public function saveSettingsOtherContent($uid, $params, $files) {
    $idx = 1;
    while(isset($params["title".$idx]) && strlen($params["title".$idx]) >= 1) {
      $result = $this->commonModel->select("my_page_sub_menu", ['uid' => $uid, 'sort' => $idx], false);
      if(isset($result) && count($result) >= 1) {
        $this->commonModel->update("my_page_sub_menu", ['title' => $params["title".$idx]], 
        [
           'uid' => $uid, 
           'sort' => $idx
        ]);
        $sort_idx = 1;
        $ret = $this->commonModel->delete("my_page_sub_menu_links", ['sub_menu_id' => $result["sub_menu_id"]]);
        while(isset($params["table_title".$idx."_".$sort_idx]) && strlen($params["table_title".$idx."_".$sort_idx]) >= 1 && isset($params["table_sentence".$idx."_".$sort_idx]) && strlen($params["table_sentence".$idx."_".$sort_idx]) >= 1) {
          //$table_data = $this->commonModel->select("my_page_sub_menu_links", ['sub_menu_id' => $result["sub_menu_id"], 'sort' => $sort_idx], false);
          //if(count($table_data) >= 1) {
            //$save_params = ['title' => $params["table_title".$idx."_".$sort_idx], 'url' => $params["table_sentence".$idx."_".$sort_idx]];
            //$condition = ['sub_menu_id' => $result["sub_menu_id"], 'sort' => $sort_idx];
            //$this->commonModel->update("my_page_sub_menu_links", $save_params, $condition);
          //} else {
            $this->commonModel->insert("my_page_sub_menu_links", [
              'sub_menu_id' => $result["sub_menu_id"], 
              'sort' => $sort_idx, 
              'title' => $params["table_title".$idx."_".$sort_idx], 
              'url' => $params["table_sentence".$idx."_".$sort_idx], 
            ]);
          //}
          $sort_idx++;
        }
      } else {
        $content_id = $this->commonModel->insert("my_page_sub_menu", [
          'uid' => $uid, 
          'title' => $params["title".$idx], 
          'sort' => $idx, 
        ]);
        $sort_idx = 1;
        while(isset($params["table_title".$idx."_".$sort_idx]) && strlen($params["table_title".$idx."_".$sort_idx]) >= 1 && isset($params["table_sentence".$idx."_".$sort_idx]) && strlen($params["table_sentence".$idx."_".$sort_idx]) >= 1) {
          $this->commonModel->insert("my_page_sub_menu_links", [
            'sub_menu_id' => $content_id, 
            'sort' => $sort_idx, 
            'title' => $params["table_title".$idx."_".$sort_idx], 
            'url' => $params["table_sentence".$idx."_".$sort_idx], 
          ]);
          $sort_idx++;
        }
      }
      $idx++;
    }
  }
  
  public function getTheme() {
    $result = $this->commonModel->select("my_theme", ['enable' => 1], true);
    return $result;
  }
  
  
  public function fileUpload($files) {
      $return = [];
      foreach($files as $file_id => $file) {
        if(strlen($files[$file_id]['tmp_name']) >= 1) {
          $file = $files[$file_id]['tmp_name'];
          $cfile = new CURLFile($files[$file_id]["tmp_name"],'image/jpeg','test_name');
          $curl = Request::forge('http://syokudo.jpn.org/api/upload_file', 'curl');
          $curl->set_option(CURLOPT_RETURNTRANSFER,true);
          $curl->set_option(CURLOPT_BINARYTRANSFER,true);
          $curl->set_header('Content-Type','multipart/form-data');
          $curl->set_params(array('image' => $file));
          $response = $curl->execute()->response();
          $result = \Format::forge($response->body,'json')->to_array();
          $return[$file_id] = ["fid" => $result['fid'], "file_url" => $result["file_url"]];
        }
      }
      return $return;
  }
}