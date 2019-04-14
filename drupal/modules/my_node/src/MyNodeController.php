<?php

namespace Drupal\my_node;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;

/**
 * Hello!を表示するコントローラー
 */
class MyNodeController  extends ControllerBase {
  public function node_list($tid) {
    $node = new NodeModel();
    if($tid == 'all') {
      $result = json_decode(json_encode($node->get_node_list()), true);
    } else if(is_numeric($tid)) {
      $result = json_decode(json_encode($node->get_node_list(2, $tid)), true);
    }
    
    foreach($result as $id => $data) {
      $node = node_load($data["nid"]);
      $user = user_load($data["uid"]);
      if(isset($user->user_picture->entity)) {
        $result[$id]["picture"] = file_create_url($user->user_picture->entity->getFileUri());
      }
      if(isset($node->field_image)) {
        foreach($node->field_image as $image) {
          if($image->entity) {
            $result[$id]["image"][] = $image->entity->url();
          }
        }
      }
    }
    header("Content-Type: application/json; charset=utf-8");
    echo (json_encode($result, true));
    exit(1);
  }
  
  public function node_data($nid) {
    $node = new NodeModel();
    $result = json_decode(json_encode($node->get_node_list(1, $nid)), true);
    foreach($result as $id => $data) {
        $node = node_load($data["nid"]);
        $user = user_load($data["uid"]);
        if(isset($user->user_picture->entity)) {
            $result[$id]["picture"] = file_create_url($user->user_picture->entity->getFileUri());
        }
        if(isset($node->field_image)) {
            foreach($node->field_image as $image) {
                if($image->entity) {
                    $result[$id]["image"][] = $image->entity->url();
                }
            }
        }
    }
    header("Content-Type: application/json; charset=utf-8");
    if(isset($nid)) {
      echo (json_encode($result, true));
    } else {
      echo (json_encode($result[0], true));
    }
    exit(1);
  }
  
  public function page($tid) {
    $node = new NodeModel();
    $result = json_decode(json_encode($node->get_page_data($tid)), true);
    header("Content-Type: application/json; charset=utf-8");
    echo (json_encode($result, true));
    exit(1);
  }
  
  public function mail(Request $request) {
    $uid = $request->query->get('uid');
    $user = user_load($uid);
    $to = $user->mail->value;
    $name = $request->query->get('name');
    $contact = $request->query->get('contact');
    $type = $request->query->get('type');
    $body = $request->query->get('body');
    $params['name'] = $name;
    $params['contact'] = $contact;
    $params['message'] = 'メッセージが来ました  W(`0`)W';
    $params['body'] = $body;
    $params['type'] = $type;
    $message = \Drupal::service('plugin.manager.mail')->mail('my_node', 'notice', $to, 'ja', $params, FALSE);
    echo (json_encode($params, true));
    exit(1);
  }
  
  public function file() {
    $query = db_select('file_managed', 'f');
    $query->fields('f',array('fid', 'uri'));
    $query->orderBy('f.fid', 'ASC');
    $result = $query->execute()->fetchAll();
    $list = [];
    foreach($result as $data) {
      $list[$data->fid] = file_create_url($data->uri);              
    }
    echo (json_encode($list, true));
    exit(1);
  }
  
  public function node_post(Request $request) {
    $uid = $request->query->get('uid');
    $user = user_load($uid);
    $to = $user->mail->value;
    $title = $request->query->get('title');
    $body = $request->query->get('body');
    $type = $request->query->get('type');
    $navigation = $request->query->get('navigation');
    $image = $request->query->get('image');
    $image_data = file_get_contents($image);
    $file = file_save_data($image_data, "public://". basename($image).".jpg", FILE_EXISTS_REPLACE);
    $node = Node::create(array(
        'type' => 'article',
        'title' => $title,
        'body'  =>  $body,
        'field_page_menu' => $navigation,
        'field_image' => [
          'target_id' => $file->id(),
          'alt' => 'Image',
          'title' => 'Image File'
        ],
        'langcode' => 'ja',
        'uid' => $uid,
        'status' => 1,
    ));

    $node->save();
    echo (json_encode($params, true));
    exit(1);
  }
  
  public function node_delete(Request $request) {
    $nid = $request->query->get('nid');
    $node = node_load($nid);
    $node->delete();
    echo (json_encode(array($nid), true));
    exit(1);
  }
  
  public function upload_file(Request $request) {
    $uid = $request->query->get('uid');
    $image = $request->query->get('image');
    $image_data = file_get_contents($image);
    $file = file_save_data($image_data, "public://". basename($image).".jpg", FILE_EXISTS_REPLACE);
    $file_url = file_create_url("public://". basename($image).".jpg");
    echo (json_encode(array('file_url' => $file_url), true));
    exit(1);
  }
  
  public function aaa(Request $request) {  
    $nid = $request->query->get('nid');
    $node = node_load($nid);
    $node->title = $request->query->get('title');
    $node->body = $request->query->get('body');
    $node->field_page_menu = $request->query->get('navigation');
    $image = $request->query->get('image');
    if(isset($image) && strlen($image)) {
     $image_data = file_get_contents($image); 
     $file = file_save_data($image_data, "public://". basename($image).".jpg", FILE_EXISTS_REPLACE);
     $node->field_image = $file->id();
    }
    $result = $node->save();
    echo (json_encode(array('result' => $result), true));
    exit(1);
  }
}