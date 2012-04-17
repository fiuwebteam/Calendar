<?php
class User extends AppModel {

	var $name = 'User';
	var $validate = array(
		'username' => array('notempty', 'isUnique'=>array('rule'=>'isUnique', 'message'=>'User already exists')),
		'password' => array('rule' => array('confirmPassword', 'password'), 'message' => 'Passwords do not match'),		
		'email' => array('email'),
		'first_name' => array('notempty'),
		'last_name' => array('notempty'),
		'active' => array('boolean'),
		'receives_email' => array('numeric')
	);
	
	var $CONTRIBUTOR = 5;
	var $AUTHOR = 4;
	var $EDITOR = 3;
	var $ADMINISTRATOR = 2;
	var $SUPERADMINISTRATOR = 1;
	

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'user_id',
			'dependent' => false
		),
		'CalendarsGroupsUser' => array(
			'className' => 'CalendarsGroupsUser',
			'foreignKey' => 'user_id',
			'dependent' => false
		)
	);
	
	var $hasAndBelongsToMany = array(
		'Group' => array(
			'className' => 'Group',
			'joinTable' => 'calendars_groups_users',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'group_id',
			'unique' => false
		),
		'Calendar' => array(
			'className' => 'Calendar',
			'joinTable' => 'calendars_groups_users',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'calendar_id',
			'unique' => false
		)
	);
	
	function getHighestPriviledge($userId) {
		$params = array();
		$params["recursive"] = -1;
		$params["conditions"] = array( "user_id" => $userId );
		$params["fields"] = array( "DISTINCT group_id");
		$params["order"] = array("group_id ASC");
		$results = $this->CalendarsGroupsUser->find("first", $params);
		return $results["CalendarsGroupsUser"]["group_id"];
		
	}
	
	function confirmPassword($data) {
		$valid = false;
		if ($data['password'] == Security::hash($this->data['User']['password_confirm'])) {
			$valid = true;
		}
		return $valid;
	}
	
	function getEditorsToEmailFor($calendars) {
		$params = array();
		$params['fields'] = "DISTINCT User.id, User.username, User.email";
		$params['recursive'] = -1;
		$params['order'] = "id";
		$params['joins'] = array(
			array(
				'table' => 'calendars_groups_users',
				'alias' => 'CalendarsGroupsUser',
				'type' => 'LEFT',
				'conditions' => array('User.id = CalendarsGroupsUser.user_id')		
			)
		);
		$params['conditions'] = array(
			"CalendarsGroupsUser.group_id <=" => $this->EDITOR,
			"User.receives_email" => 2
		);
		
		$params['conditions']["OR"][] = array("CalendarsGroupsUser.calendar_id" => -1);
		
		foreach($calendars as $c) {
			$params['conditions']["OR"][] = array("CalendarsGroupsUser.calendar_id" => $c);
		}
		
		
		
		return $this->find("all", $params);
	}
	
	function isAuthorOrBetter($userId, $eventId) {
		$params = array();
		$params["joins"] = array(
			array(
				'table' => 'calendars_events',
				'alias' => 'CalendarsEvent',
				'type' => 'LEFT',
				'conditions' => array('CalendarsGroupsUser.calendar_id = CalendarsEvent.calendar_id')
			)
		);
		$params["conditions"] = array(
			"CalendarsGroupsUser.user_id" => $userId,
			"CalendarsEvent.event_id" => $eventId,
			// author or better
			"CalendarsGroupsUser.group_id <=" => $this->AUTHOR
		);
		
		$params["recursive"] = -1;
		$check  = $this->CalendarsGroupsUser->find("first", $params);
		if (empty($check)) {
			return false;
		}
		return true;
	}
	
	function getCalendarsUnderUser($userId) {
		$params = array();
		$params["recursive"] = -1;
		$params["fields"] = array("calendar_id");
		$params["conditions"] = array(
			"user_id" => $userId,
			"group_id <= " => $this->ADMINISTRATOR
		);
		// Calendar list this user has administrator or better		
		$calendarsIds = $this->CalendarsGroupsUser->find("all", $params);
		return $calendarsIds;
	}
	

	function getUsersUnderCalendars($calendars) {
		$params = array();
		$params["order"] = "User.username ASC";
		$params["recursive"] = -1;
		$params["fields"] = array( "DISTINCT User.id", "User.username" );
		$params["joins"] = array(
			array(
				'table' => 'calendars_groups_users',
				'alias' => 'CalendarsGroupsUser',
				'type' => 'LEFT',
				'conditions' => array('User.id = CalendarsGroupsUser.user_id')		
			)
		);				
		foreach($calendars as $calendarsId) {
			$params["conditions"]["OR"][] = array("CalendarsGroupsUser.calendar_id" => $calendarsId["Calendar"]["id"]);
		}
		// Users under stated calendars, aka those this user has power over.
		$users = $this->find("all", $params);
		return $users;
	}
	
	function getUsersIdsUnderCalendars($calendarsIds, $filter) {
		$params = array();
		$params["recursive"] = -1;
		$params["fields"] = array( "DISTINCT User.id" );
		$params["joins"] = array(
			array(
				'table' => 'calendars_groups_users',
				'alias' => 'CalendarsGroupsUser',
				'type' => 'LEFT',
				'conditions' => array('User.id = CalendarsGroupsUser.user_id')		
			)
		);	
		$params["conditions"][] = array("CalendarsGroupsUser.group_id <" => $this->CONTRIBUTOR);			
		foreach($calendarsIds as $calendarsId) {
			$params["conditions"]["OR"][] = array("CalendarsGroupsUser.calendar_id" =>
			$calendarsId["CalendarsGroupsUser"]["calendar_id"]);
		}		
		if ($filter != "") {
			$params["conditions"][] = array("User.last_name LIKE" => $filter . "%" );
		}
		$userIds = $this->find("all", $params);
		return $userIds;
	}
	
	function getUsersPaginationParams($userIds) {
		$params = array();
		$params["recursive"] = -1;
		$params["fields"] = array( "User.*" );
		foreach($userIds as $userId) {
			$params["conditions"]["OR"][] = array("User.id" => $userId["User"]["id"]);
		}
		return $params;
	}
	
	function getAdminsForCalendar($calendarId) {
		$params = array(
			"fields" => array("User.*"),
			"recursive" => -1,
			"joins" => array(
				array(
					'table' => 'calendars_groups_users',
					'alias' => 'CalendarsGroupsUser',
					'type' => 'LEFT',
					'conditions' => array('User.id = CalendarsGroupsUser.user_id')
				)
			),
			"conditions" => array(
				"CalendarsGroupsUser.group_id =" => $this->ADMINISTRATOR,
				"CalendarsGroupsUser.calendar_id =" => $calendarId,
				"User.active" => 1
			)
		);
		return $this->find("all", $params);
	}
	
	function usersToEmail() {
		$params = array(
			"fields" => array("DISTINCT Calendar.id", "Calendar.title"),
			"recursive" => 1, 
			"conditions" => array("CalendarsEvent.active" => 0)
		);
		
		$calendarsEvents = $this->Calendar->CalendarsEvent->find("all", $params);
		
		$params = array();
		$params['fields'] = "DISTINCT User.id, User.username, User.email";
		$params['recursive'] = -1;
		$params['order'] = "id";
		$params['joins'] = array(
			array(
				'table' => 'calendars_groups_users',
				'alias' => 'CalendarsGroupsUser',
				'type' => 'LEFT',
				'conditions' => array('User.id = CalendarsGroupsUser.user_id')		
			)
		);
		$params['conditions'] = array(
			"CalendarsGroupsUser.group_id <=" => $this->EDITOR,
			"User.receives_email" => 1,
			"User.active" => 1			
		);
		$params['conditions']["OR"][] = array("CalendarsGroupsUser.calendar_id" => -1);
		foreach($calendarsEvents as $cE) {
			$params['conditions']["OR"][] = array("CalendarsGroupsUser.calendar_id" => $cE["Calendar"]["id"]);
		}
		
		$users = $this->find("all", $params);
		return $users;
	}
	
}
?>