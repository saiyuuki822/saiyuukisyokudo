<?php
/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.8.1
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2018 Fuel Development Team
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
class Controller_Blog extends Controller_My
{
	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
        // Request_Curl オブジェクトを作成
        $curl = Request::forge('http://syokudo.jpn.org/api/node_list/taxonomy/7', 'curl');

        // リクエストを実行
        $response = $curl->execute()->response();
        $list = \Format::forge($response->body,'json')->to_array();
        foreach($list as $id => $data) {
          $list[$id]["body_value"] = strip_tags(str_replace(PHP_EOL, '', mb_substr($data["body_value"], 0, 100)));
        }
        $this->template->content = View::forge('syokudo/menu/index', ["list" => $list], false);
	}

  public function action_detail($nid)
  {
        // Request_Curl オブジェクトを作成
        $curl = Request::forge('http://syokudo.jpn.org/api/node_data/'.$nid, 'curl');
        // リクエストを実行
        $response = $curl->execute()->response();
        $data = \Format::forge($response->body,'json')->to_array();
    
        if(strpos($data[0]["body_value"],'myportal') === false) {
          $data[0]["body_value"] = str_replace('/sites', 'http://syokudo.jpn.org/sites', $data[0]["body_value"]);
        }
        if(isset($data[0]['image'][0])) {
          $this->template->image =  $data[0]['image'][0];
          //var_dump($this->template->image);
        }
        foreach($data[0]["image_caption"] as $idx => $image_caption){
          if(isset($this->image[$data[0]["image_caption"][$idx]["field_image"]])) {
            $data[0]["image_caption"][$idx]["field_image"] = $this->image[$data[0]["image_caption"][$idx]["field_image"]];
          }
        }
    
        $this->template->title = $data[0]["title"];
        $this->template->content = View::forge('syokudo/blog/detail', ["data" => $data[0], "title" => $this->template->title], false);
  }
  
	/**
	 * The 404 action for the application.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		return Response::forge(Presenter::forge('welcome/404'), 404);
	}
}
