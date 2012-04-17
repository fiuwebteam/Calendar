<?php
class Category extends AppModel {

	var $name = 'Category';
	var $validate = array(
		'id' => array('numeric'),
		'title' => array('notempty'),
		'description' => array('notempty'),
		'created' => array('notempty'),
		'modified' => array('notempty')
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'category_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
?>