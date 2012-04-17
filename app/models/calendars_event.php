<?php
class CalendarsEvent extends AppModel {

	var $name = 'CalendarsEvent';
	var $validate = array(
		'id' => array('numeric'),
		'start_date_time' => array('notempty'),
		'end_date_time' => array('notempty'),
		'calendar_id' => array('numeric'),
		'event_id' => array('numeric'),
		'featured' => array('boolean')
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'Calendar' => array(
			'className' => 'Calendar',
			'foreignKey' => 'calendar_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
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