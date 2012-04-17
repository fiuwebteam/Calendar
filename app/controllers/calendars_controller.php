<?php
class CalendarsController extends AppController {

	var $name = 'Calendars';
	
	function beforeFilter() {
		parent::beforeFilter(); 
		$this->Auth->allow("daily");
		//$this->Auth->allow('*');
	}
	
	function index() {
		$userId = $this->Auth->user("id");
		$this->paginate = $this->Calendar->getCalUnderUserPaginationParams($userId);;
		$calendars = $this->paginate("Calendar");	
		$this->set(compact('calendars'));
	}
	
	function users() {
		$userId = $this->Auth->user("id");
		$calendarsList = $this->Calendar->getCalendarsUnderUser($userId);
		if (isset($this->params["named"]["calendar"])) {
			$calendarId = $this->Calendar->getIdByUrlName($this->params["named"]["calendar"]);
		} else {
			$topCalendar = $this->Calendar->getHighestCalendarUnderUser($userId);		
			$calendarId = $topCalendar["Calendar"]["id"];			
		}
		$this->paginate = array( "User" => $this->Calendar->getCalUsersPaginationParam($calendarId) );
		$users = $this->paginate("User");
		$this->set(compact('users', 'calendarsList', 'calendarId'));
	}
	
	function events() {		
		if (isset($this->data["drop"]) && isset($this->data["CalendarsEvent"])) {	
			foreach($this->data["CalendarsEvent"] as $key1 => $ce) {
				$calendar_id = $key1;
				foreach($ce as $key2 => $value) {
					$event_id = $key2;
					if ($value == 1) {							
						if ($this->Calendar->CalendarsEvent->deleteAll(array("calendar_id" => $calendar_id, "event_id" => $event_id), false)) {
							$check = $this->Calendar->CalendarsEvent->find("count", array("recursive" => -1, "conditions" => array("event_id" => $event_id)));
							if ($check == 0) { $this->Event->delete($event_id);}
						} 
					}
				}
			}
		}		
		$userId = $this->Auth->user("id");		
		$calendarsList = $this->Calendar->getCalendarsUnderUser($userId);		
		if (isset($this->params["named"]["calendar"])) {
			$calendarId = $this->Calendar->getIdByUrlName($this->params["named"]["calendar"]);
		} else {
			$topCalendar = $this->Calendar->getHighestCalendarUnderUser($userId);		
			$calendarId = $topCalendar["Calendar"]["id"];			
		}	
		$this->paginate = array("Event" => $this->Calendar->getEventUnderCalendarPaginationParams($calendarId));
		$events = $this->paginate("Event");
		$events = $this->Event->addCalendarsEvents($events);		
		$this->set(compact('events', 'calendarsList', 'calendarId'));
	}

	function add() {
		$userId = $this->Auth->user("id");		
		$calendars = $this->Calendar->getCalendarsUnderUser($userId, true);
		
		if (!empty($this->data)) {
			$goodToGo = false;
			foreach ($calendars as $key => $value) {
				if ($key == $this->data["Calendar"]["parent_id"]) {$goodToGo = true;}
			}			
			if (!$goodToGo) {
				$this->flash(__("Sorry, you don't have the permission to do that.", true), array('action' => 'index'));
				return;
			} 
			$this->Calendar->create();
			if ($this->Calendar->save($this->data)) {
				$calendarId = $this->Calendar->id;
				$calendars_groups_users = $this->Calendar->getCalendarsGroupsUserForCalendar($this->data["Calendar"]["parent_id"]);
				$new_calendars_groups_users = array();
				if (empty($calendars_groups_users)) {
					$new_calendars_groups_users["CalendarsGroupsUser"]["calendar_id"] = $calendarId;
					$new_calendars_groups_users["CalendarsGroupsUser"]["user_id"] = $userId;
					$new_calendars_groups_users["CalendarsGroupsUser"]["group_id"] = $this->Calendar->User->ADMINISTRATOR;
				} else {
					foreach($calendars_groups_users as $calendars_groups_user) {
						$new = array();
						$new["CalendarsGroupsUser"]["calendar_id"] = $calendarId;
						$new["CalendarsGroupsUser"]["user_id"] = $calendars_groups_user["CalendarsGroupsUser"]["user_id"];
						$new["CalendarsGroupsUser"]["group_id"] = $calendars_groups_user["CalendarsGroupsUser"]["group_id"];
						$new_calendars_groups_users[] = $new;
					}
				}				
				if ($this->Calendar->CalendarsGroupsUser->saveAll($new_calendars_groups_users, array("validate" => "only"))) {
					$this->Calendar->CalendarsGroupsUser->saveAll($new_calendars_groups_users, array("validate" => false));
					$this->flash(__('Calendar saved.', true), array('action' => 'index'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Calendar->delete($calendarId);
					$this->flash(__('Error, contact the site administration.', true), array('action' => 'index'));
				}
			}			
		}
		$this->set(compact('calendars'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->flash(__('Invalid Calendar', true), array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->data["Calendar"]["id"] = $id;
			if ($this->Calendar->save($this->data)) {
				$this->flash(__('The Calendar has been saved.', true), array('action' => 'index'));
				$this->redirect(array('action' => 'index'));
			}
		}
		if (empty($this->data)) {
			$this->Calendar->recursive = -1;
			$this->data = $this->Calendar->read(null, $id);
			$params = array("conditions" => array("calendar_id" => $id), "recursive" => -1);
			$ical = $this->Calendar->CalendarsIcal->find("first", $params);
			$this->data["Calendar"]["ical"] = $ical["CalendarsIcal"]["url"];
			
		}
		$userId = $this->Auth->user("id");
		if ($id != 1) {
			$calendars = $this->Calendar->getAllCalendars();
			$this->set(compact('calendars'));
		}
	}

	function delete($id = null) {
		if (!$id || $id == 1) {
			$this->flash(__('Invalid Calendar', true), array('action' => 'index'));
			$this->redirect(array('action' => 'index'));
		}
		$userId = $this->Auth->user("id");
		
		$cgu = $this->Calendar->CalendarsGroupsUser->find("first", 
		array("recursive" => -1, "conditions" => array("user_id" => $userId, "calendar_id" => $id)));
		
		if ($cgu["CalendarsGroupsUser"]["group_id"] <= 2) {
			$parent_id = $this->Calendar->read("parent_id", $id);
			$parent_id = $parent_id["Calendar"]["parent_id"];
			if ($this->Calendar->del($id)) {
				$this->Calendar->updateParent($id, $parent_id);
				$this->Calendar->CalendarsGroupsUser->deleteAll(array("calendar_id" => $id, false));			
				$this->flash(__('Calendar deleted', true), array('action' => 'index'));
				$this->redirect(array('action' => 'index'));
			}
		}
	}
	
	function daily() {

		if ($_SERVER["REMOTE_ADDR"] != "72.32.184.60") { 
			echo "Error";
			return;
		} else {
			echo "Working";
		}
		
		$users = $this->User->usersToEmail();
		
		foreach($users as $user) {
			$this->Email->from = 'FIU Calendar <noreply@fiu.edu>';
			$this->Email->to = ($user["User"]['username'] . '<' . $user["User"]['email'] . '>');
			$this->Email->subject = 'FIU Calendar Pending Requests';
			$body = "
Hello User {$user["User"]['username']}, 
You have pending events in one of your calendars.

Please visit:
http://calendar.fiu.edu/events/pending
" ;
			$this->Email->send($body);
		}
		$this->Event->updateIcalEvents();
		
		
		
	}
}
?>