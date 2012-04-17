<?php
class Calendar extends AppModel {

	var $name = 'Calendar';
	var $validate = array(
		'id' => array('numeric'),
		'title' => array('notempty','isUnique'),
		'url' => array('notempty', 'isUnique'),
		'description' => array('notempty'),
		'parent_id' => array('numeric'),
		'private' => array('boolean')
	);
	
	var $hasMany = array(
		'CalendarsIcal' => array(
			'className' => 'CalendarsIcal',
			'foreignKey' => 'calendar_id',
			'dependent' => false
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasAndBelongsToMany = array(
		'Event' => array(
			'className' => 'Event',
			'joinTable' => 'calendars_events',
			'foreignKey' => 'calendar_id',
			'associationForeignKey' => 'event_id',
			'unique' => false
		),
		'Group' => array(
			'className' => 'Group',
			'joinTable' => 'calendars_groups_users',
			'foreignKey' => 'calendar_id',
			'associationForeignKey' => 'group_id',
			'unique' => false
		),
		'User' => array(
			'className' => 'User',
			'joinTable' => 'calendars_groups_users',
			'foreignKey' => 'calendar_id',
			'associationForeignKey' => 'user_id',
			'unique' => false
		)
	);
	
	function save($data = null, $validate = true, $fieldList = array()) {
		
		$memcache = new Memcache();
    	$memcache->connect("localhost") or die ("Could not connect to memcache");
		$memcache->flush();
		
		if ($data["Calendar"]["ical"] != "") {
			$calendarsIcal = array("calendar_id" => 0, "url" => $data["Calendar"]["ical"]);
			$this->CalendarsIcal->data = $calendarsIcal;
			if (!$this->CalendarsIcal->validates()) {
				 $errors = $this->CalendarsIcal->invalidFields();
				 $this->invalidate("ical", $errors["url"]);
			}
		}
		
		if ($this->validates()) {			
			parent::save($data, false, $fieldList);
			$this->CalendarsIcal->deleteAll(array("calendar_id" => $this->id));
			if (isset($calendarsIcal)) {
				$calendarsIcal["calendar_id"] = $this->id;
				 $this->CalendarsIcal->save($calendarsIcal, false);
			}
			return true;
		}
	}	
	
	
	function getCalendarByUrlName($urlName) {
		$calendar = $this->find("first", 
			array( 
				"recursive" => -1, 
				"conditions" => array("url" => $urlName)
			)
		);
		return $calendar;
	}
	
	function getIdByUrlName($urlName) {
		$calendarId = $this->find("first", 
			array( 
				"fields" => array("id"), 
				"recursive" => -1, 
				"conditions" => array("url" => $urlName)
			)
		);
		$calendarId = $calendarId["Calendar"]["id"];
		return $calendarId;
	}
	
	function getParents($id, $includePrivate = false) {
		$results = array();
		while($id != 0) {
			$params = array();
			$params["conditions"] = array("id" => $id);
			$params["recursive"] = 0;
			$result = $this->find("first", $params);
			if ( $includePrivate || !$result["Calendar"]["private"]) {
				$results[] = $result; 
			}
			$id = $result["Calendar"]["parent_id"];
		}
		return $results;
	}
	
	
	
	function getCalendarsUnderUser($userId, $returnArray = false, $editor = false) {
		$group_id = $this->User->ADMINISTRATOR;
		if ($editor) { $group_id = $this->User->EDITOR; }
		$calendarsUser = $this->CalendarsGroupsUser->find("all", 
			array( 
				"fields" => array("calendar_id"), 
				"recursive" => -1, 
				"conditions" => array("user_id" => $userId, "group_id <=" => $group_id)
			)
		);		
		$params = array();
		$params["recursive"] = -1;
		$params["order"] = "title";
		foreach($calendarsUser as $cu) {
			$params["conditions"]["OR"][] = array("Calendar.id" => $cu["CalendarsGroupsUser"]["calendar_id"]); 
		}		
		$calendars = $this->find("all", $params);
		
		if ($returnArray) {
			$tmp = array();
			foreach ($calendars as $key => $value) { $tmp[$value["Calendar"]["id"]] = $value["Calendar"]["title"]; }
			$calendars = $tmp;
		}
		
		return $calendars;
	}
	
	function getAllCalendars() {
    	$this->recursive = -1;
		$params = array();
    	$params['fields'] = array('Calendar.*');
		$params["order"] = "title";
		$calendarsList = $this->find("all", $params);
		return $calendarsList;
    }
    
    function updateParent($oldParent, $newParent) {
    	$this->updateAll(array("Calendar.parent_id" => $newParent), array("Calendar.parent_id" => $oldParent));
    	//$this->query("UPDATE calendars SET parent_id='$newParent' WHERE parent_id='$oldParent'");
    }
    
    function getHighestCalendarUnderUser($userId) {
    	$calendarsList = $this->getCalendarsUnderUser($userId);
   		$top = $calendarsList[0]["Calendar"]["parent_id"];
		$calendar = $calendarsList[0];
		foreach($calendarsList as $value) {
			if ($value["Calendar"]["parent_id"] < $top) {
				$top = $value["Calendar"]["parent_id"];
				$calendar = $value;
			}
		}
		return $calendar;
    }
    
	function getCalUsersPaginationParam($calendarId) {
		$calendarsGroupsUsers = $this->CalendarsGroupsUser->find("all", 
			array( "conditions" => array( "calendar_id" => $calendarId ), 
			"recursive" => -1 )
		);		
		
		$params = array();	
		$params["conditions"]["OR"][] = array("User.id" => 0);
		foreach($calendarsGroupsUsers as $value) {
			$params["conditions"]["OR"][] = array("User.id" => 
			$value["CalendarsGroupsUser"]["user_id"]);		
		}	
		$params["conditions"][] = array("CalendarsGroupsUser.group_id <" => 5);
		$params["fields"] = array("User.*", "CalendarsGroupsUser.group_id");
		$params["recursive"] = -1;
		$params["joins"] = array(
			array(
					'table' => 'calendars_groups_users',
					'alias' => 'CalendarsGroupsUser',
					'type' => 'LEFT',
					'conditions' => array('CalendarsGroupsUser.user_id = User.id', 
					"CalendarsGroupsUser.calendar_id" => $calendarId)				
			)
		);
		return $params;
	}
	
	function getCalUnderUserPaginationParams($userId) {
		$allCalendars = $this->getCalendarsUnderUser($userId);		
		$params = array("recursive" => -1, "order" => "id");
		$params["conditions"]["OR"][] = array("Calendar.id" => -1);
		foreach($allCalendars as $value) {
			$params["conditions"]["OR"][] = array("Calendar.id" => $value["Calendar"]["id"]); 
		}
		return $params;
	}
	
	function getEventUnderCalendarPaginationParams($calendarId) {
		$calendarsEvents = $this->CalendarsEvent->find("all", 
			array("conditions" => array("calendar_id" => $calendarId, "active" => 1 ), 
			"fields" => array("DISTINCT event_id"), "recursive" => -1));
		
		$params = array();	
		$params["conditions"]["OR"][] = array("Event.id" => 0);
		foreach($calendarsEvents as $calendarsEvent) {
			$params["conditions"]["OR"][] = array("Event.id" => $calendarsEvent["CalendarsEvent"]["event_id"]);
		}
		$params["fields"] = array("Event.*", "User.id", "User.username");
		$params["recursive"] = 0;
		return $params;
	}
	
	function getCalendarsGroupsUserForCalendar($calendarId) {
		$params = array(
			"conditions" => array( "calendar_id" => $calendarId ),
			"recursive" => -1,
			"fields" => array("user_id", "group_id")
		);
		return $this->CalendarsGroupsUser->find("all", $params);
		
	}
    
}
?>