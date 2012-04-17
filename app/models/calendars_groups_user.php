<?php
class CalendarsGroupsUser extends AppModel {

	var $name = 'CalendarsGroupsUser';
	var $validate = array(
		'id' => array('numeric'),
		'calendar_id' => array('numeric'),
		'user_id' => array('numeric'),
		'group_id' => array('numeric')
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
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	//----------------------------------------------------------------------------------------------------------
}
?>