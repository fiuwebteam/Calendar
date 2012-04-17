<?php
class TagsController extends AppController {

	var $name = 'Tags';
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('view', 'index');
		//$this->Auth->allow('*');
	}
	
	function index() {
		$tags = $this->Tag->find("all", array("recursive" => -1, "order" => "title"));
		$this->set(compact('tags'));
	}

	function view($id = null) {
		$params = array();

		$params["conditions"] = array("Tag.title" => $id);
		$tag = $this->Tag->find("all", $params);
		$this->set(compact('tag'));
	}
}
?>