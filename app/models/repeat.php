<?php
class Repeat extends AppModel {

	var $name = 'Repeat';
	var $validate = array(
		'event_id' => array('numeric'),
		'type' => array('numeric'),
		//'variable' => array('notempty'),
		'start_date' => array('date'),
		'end_date' => array('date'),
		'start_time' => array('time'),
		'end_time' => array('time')
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}
?>