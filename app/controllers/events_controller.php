<?php
class EventsController extends AppController {

	var $name = 'Events';
	
	function beforeFilter() {
		parent::beforeFilter();
		
        $this->Auth->allow(
		 	"spreadsheet", "faq", "ical", "index", "pdf", "play", "plug", 
		 	"rss", "search", "view", "month", "json", "fblike"

		);
	}
	
	function fblike() {
		 //Configure::write('debug', 0);
	    //header("Content-type: text/plain");
		$this->autoRender = false;
        $this->layout = 'ajax';
	     
	  /*loop through json object passed from view and create a new array formatted for saveall to update
	    fb_like for specific event ids*/
	  for ($x=0;$x < count($this->data); $x++)
	    {
	      
	      $dataw['Event'][$x]['fblike']= $this->data[$x]['fql_result_set'][0]['like_count'];
	      $dataw['Event'][$x]['id']= intval($this->data[$x]['name']);
	     
	    }
	   // echo "dump: ".var_dump( $dataw);
	   //echo "look: ".$dataw[0]['id'];
	   //echo json_encode("asfd"); 
	  // echo "Im working";
       // $this->Event->updateAll($this->data); 
	   	   
	   // if ( $this->Event->saveAll($dataw['Event']) )
	    	//echo "ok"; else echo "BAD";
	    	
	     $this->Event->updateAll(array('Event.fblike' => $this->data['fblike']), array('Event.id' => $this->data["id"]));
		
		//echo $this->data["id"]; 	
	  
   	}  
	
	function add() {
		$calendars = $this->Event->Calendar->find('all', array("recursive" => -1, 'order' => 'title'));
		$categories = $this->Event->Category->find('list', array("recursive" => -1));
		$categories = array_merge(array(0 => ""), $categories);
		$this->set(compact('calendars', 'categories'));
		//$errors = $this->Event->invalidFields();
		// Saving upon submission.
		if (!empty($this->data)) {
		  //var_dump($this->data ); exit();
			if($this->Event->save($this->data)) {
				$calendars = $this->Event->calendarsToSave($this->data["Calendar"]["Calendar"]);
				$this->_emailEditorsForPending($this->User->getEditorsToEmailFor($calendars));				
				$this->Session->setFlash('The Event Has Been Saved', 'default', array('class' => 'flash_good'));
				$this->redirect(array('action' => 'view', $this->Event->id));
			} else {
				$this->Session->setFlash(__('The Event could not be saved. Please, try again.', true));
				$this->set('error', true);
			}
		}		
	}
	
	function edit() {
		
		$id = isset($this->params["pass"][0]) ? $this->params["pass"][0] : null;		
		$calendars = $this->Event->Calendar->find('all', array("order" => "title", "recursive" => -1));
		$categories = $this->Event->Category->find('list', array("recursive" => -1));
		$this->set(compact('calendars','categories'));	
		
		$params = array(
			"conditions" => array("id" => $id),
			"fields" => array("user_id"),
			"recursive" => -1
		);
		
		$authorId = $this->Event->find("first", $params);
		$authorId = $authorId["Event"]["user_id"];
		
		$userId = $_SESSION["Auth"]["User"]["id"];
		
		if ($this->User->getHighestPriviledge($userId) > 3 && $authorId != $userId ) {
			$this->Session->setFlash(__('You do not have permission to do that.', true));
			$this->redirect(array('action' => 'my_events'));
			return;
		}
			
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Event', true));
			$this->redirect(array('action' => 'my_events'));
		}		
		if (!empty($this->data)) {
			$this->Event->id = $id;
			if($this->Event->save($this->data)) {
				$this->Session->setFlash('The Event Has Been Edited', 'default', array('class' => 'flash_good'));
				$this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('The Event could not be saved. Please, try again.', true));
				$this->set('error', true);
			}
		} else {
			$this->data = $this->Event->formFormat($id);			
		}		
	}	
	
	function appendEventToCalendar($event, $calendar) {
		$check = $this->Calendar->CalendarsGroupsUser->find("count", array("conditions" => array("calendar_id" => $calendar, "group_id <=" => 2, "user_id" => $this->Session->read("Auth.User.id")), "recursive" => -1 ));
		if ($check == 0) {
			$this->Session->setFlash(__('You do not have permission to do that.', true));
			$this->redirect(array('action' => 'view', $event));
			return;
		}		
		$check = $this->Calendar->CalendarsEvent->find("count", array("recursive" => -1, "conditions" => array("event_id" => $event, "calendar_id" => $calendar)) );
		if ($check == 0) {
			$input = $this->Event->calendarEventsToAppend($event, $calendar);
			$validCalendarsEvents = $this->Event->CalendarsEvent->saveAll($input, array("validate" => "only"));
			if ($validCalendarsEvents) {
				$this->Event->CalendarsEvent->saveAll($input);
				$this->Session->setFlash('Event added to the Calendar.', 'default', array('class' => 'flash_good'));
				$this->redirect(array('action' => 'view', $event));
			} else {
				$this->Session->setFlash(__('Something went wrong.', true));
				$this->redirect(array('action' => 'view', $event));
			}
		} else {
			$this->Session->setFlash(__('That event exists in that calendar already.', true));
			$this->redirect(array('action' => 'view', $event));
		}		
	}

	/*
	 * Delete an existing event.
	 */

	function delete($id = null) {
		
		
		$params = array(
				"conditions" => array("id" => $id),
				"fields" => array("user_id"),
				"recursive" => -1
		);
		
		$authorId = $this->Event->find("first", $params);
		$authorId = $authorId["Event"]["user_id"];
		
		$userId = $_SESSION["Auth"]["User"]["id"];
		
		if ($this->User->getHighestPriviledge($userId) > 3 && $authorId != $userId ) {
			$this->Session->setFlash(__('You do not have permission to do that.', true));
			$this->redirect(array('action' => 'my_events'));
			return;
		}
		
		
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Event', true));
			$this->redirect(array('action' => 'my_events'));
		}
		if ($this->Event->del($id)) {
			$params = array("event_id" => $id);
			$this->Event->EventsTag->deleteAll($params, true);
			$this->Event->Repeat->deleteAll($params, true);
			$this->Event->CalendarsEvent->deleteAll($params, true);
			$this->Session->setFlash(__('Event deleted', true));
			$this->redirect(array('action' => 'my_events'));
		}
		$this->Session->setFlash(__('The Event could not be deleted. Please, try again.', true));
		$this->redirect(array('action' => 'my_events'));
	}	
	
	function faq() {
		return;
	}

	function feature() {		
		if (isset($this->data)) {
			if (isset($this->data["featured"])) {
				foreach($this->data["Feature"] as $key => $value) {
					if ($value == 1) {
						$this->Event->CalendarsEvent->id = $key;
						$this->Event->CalendarsEvent->set('featured', 1);
						$this->Event->CalendarsEvent->save();
					}
				}
			} else if (isset($this->data["no_feature"])) {
				foreach($this->data["Feature"] as $key => $value) {
					if ($value == 1) {
						$this->Event->CalendarsEvent->id = $key;
						$this->Event->CalendarsEvent->set('featured', 0);
						$this->Event->CalendarsEvent->save();
					}
				}
			}
			$memcache = new Memcache();
	    	$memcache->connect("localhost") or die ("Could not connect to memcache");
			$memcache->flush();
		}
		
		$calendarsList = $this->Calendar->getAllCalendars();
		$userId = $this->Auth->user("id");
		$calendarId = $this->Calendar->getIdByUrlName((isset($this->params["named"]["calendar"]) ? $this->params["named"]["calendar"] : "main"));
				
		$filter = isset($this->params["named"]["filter"]) ? $this->params["named"]["filter"] : "";
		$startDate = isset($this->params["named"]["startDate"]) ? $this->params["named"]["startDate"] : "";
		$endDate = isset($this->params["named"]["endDate"]) ? $this->params["named"]["endDate"] : "";
		$params = $this->Event->getFeaturedEventsParams($calendarId, $filter, $endDate, $startDate );
				
		$this->paginate = array( "Event" => $params );
		$events = $this->paginate("Event");

		$this->set(compact('events', 'calendarsList'));
	}

	function ical() {
		return $this->rss();
	}
	
	function _standardUrl() {
		$url = "";
		foreach($this->params["named"] as $key => $value) {
			if (!(($key == "start" || $key == "end") && (isset($this->params["form"]["start"]) || isset($this->params["form"]["end"])))) {
				$url.= "$key:$value/";
			}
		}
		$tmp = "/events/index";
		if (isset($this->params["calendar"])) { $tmp .= "/calendar:" . $this->params["calendar"]; }
		else if (!isset($this->params["named"]["calendar"])) { $tmp .= "/calendar:main"; }
		$url = $tmp . "/$url";
		if (isset($this->params["form"]["start"]) && isset($this->params["form"]["end"])) {
			$url .= "start:" . date("Y-m-d", strtotime($this->params["form"]["start"])) . "/";
			$url .= "end:" . date("Y-m-d", strtotime($this->params["form"]["end"]));
		}
		$this->redirect($url);
		exit();
	}
	
	function index() {
		// Making the url standard
		if (!isset($this->params["named"]["calendar"]) || isset($this->params["form"]["start"])) {
			$this->_standardUrl();
		}
		
		$categories = array_merge( array( 0 => "All"), $this->Event->Category->find('list', array("recursive" => -1)) );
		$calendar = $this->params["named"]["calendar"];
		$this->Calendar->recursive = -1;
		$calendarId = $this->Calendar->getIdByUrlName($calendar);
		
		$calendarName = $this->Calendar->read("title", $calendarId);
		$calendarsList = $this->Calendar->getAllCalendars();
		$calendarName = $calendarName["Calendar"]["title"];		
		$noCategory = $noDate = $noType = $noView = array("controller"=>"events","action"=>"index","calendar" => $calendar);		
		// The default view
		$viewType = "list";
		// The search parameters
		
		$paginateParams = $this->Event->getIndexPaginationParams($calendarId, $this->params["named"]);
		
		$startDate = $paginateParams["conditions"]["CalendarsEvent.end_date_time >="];
        $endDate = $paginateParams["conditions"]["CalendarsEvent.start_date_time <="];
        
        $this->paginate = $paginateParams;
    	$events = $this->paginate("Event");
        
    	$featured = $this->Event->getFeaturedEventsForCalendar($calendarId);
        
        $today = date("Y-m-d");		
		
		$this->set(compact('calendarsList' , 'events', 'categories', 'noCategory', 'noDate', 
		'noType', 'noView', 'viewType', 'calendar', 'featured', 'calendarName', 'startDate', 'endDate', 'today'));
	
		if(isset($this->params['requested'])) {
			return $featured;
		} 
        //for facebook query
        $this->set('FQL','');
	}
	
	
	
	
	
	function json() {
		return $this->rss();
	}

	function massInput() {
		ini_set('auto_detect_line_endings', true);
		$calendars = $this->Event->Calendar->find('all', array("recursive" => -1, 'order' => 'title'));
		$this->set(compact('calendars'));
		if (isset($this->data)) {
			$return = $this->Event->massInput($this->data);
			if ($return["valid"]) {
				$this->Session->setFlash($return["message"], 'default', array('class' => 'flash_good'));
			} else {
				$this->Session->setFlash(__($return["message"], true));
			}
		}
		
	}
	
	function month() {
		if (!isset($this->params["named"]["calendar"])) {
			$url = "/events/month/calendar:main";
			foreach($this->params["named"] as $key => $value) { $url.= "/$key:$value"; }			
			$this->redirect($url);
			exit();
		}
		
		$calendar = $this->params["named"]["calendar"];
		$this->Calendar->recursive = -1;
		$calendarId = $this->Event->Calendar->getIdByUrlName($calendar);
		$calendarName = $this->Calendar->read("title", $calendarId);
		$calendarName = $calendarName["Calendar"]["title"];
		
		$calendarId = $this->Event->Calendar->getIdByUrlName($this->params["named"]["calendar"]);
		if (isset($this->params["named"]["year"]) && isset($this->params["named"]["month"])) {
			$date = date("Y-m-d", strtotime($this->params["named"]["year"] . "-" . $this->params["named"]["month"] . "-01" ));
		} else {
			$date = date("Y-m-d");
		}	
		
		$lastMonthYear = date("Y", strtotime("$date -1 month"));
		$lastMonthMonth = date("m", strtotime("$date -1 month"));
		$lastMonthDays = date("t", strtotime("$date -1 month"));		
		$nextMonthYear = date("Y", strtotime("$date +1 month"));
		$nextMonthMonth = date("m", strtotime("$date +1 month"));		
		$firstWeekdayOfTheMonth = date("w", strtotime( date("Y-m-", strtotime($date)) . "01"));
		
		$noDate = array("controller"=>"events","action"=>"index","calendar" => $calendar);
		
		
		
		if ($firstWeekdayOfTheMonth == 0 ) {
			$start = date("Y-m-", strtotime($date) ) . "01";
		} else {
			$start = $lastMonthYear . "-" . $lastMonthMonth . "-" . ($lastMonthDays - $firstWeekdayOfTheMonth + 1 );
		}
		
		//$end = date("Y-m-", strtotime("$date +1 month")) . sprintf("%02d",(42 - date("t") - $firstWeekdayOfTheMonth));
		$end = date ("Y-m-d", strtotime($start . " +" . ( ( 7 * 6 ) - 1) . " days" ));
		
		$events = $this->Event->getEvents($calendarId, $start, $end, null, 1000, 0, true);
		
		$today = date("Y-m-d");
		
		$this->set(compact('date', 'lastMonthYear', 'lastMonthMonth',  
		'events', 'nextMonthYear', 'nextMonthMonth', 'calendar', 'calendarName', 'noDate', 'start', 'today' ));
		
		
		//call index action to show featured event data in month view/element.
		// Andre: Don't do this. This causes the events to be overridden. Simply call the two functions you need in here. calendar and calendarName
		// $this->index(); 
	}


	function my_events() {
		$this->paginate = $this->Event->getMyEventsParams($this->Auth->user("id"));
		$events = $this->paginate("Event");
		$this->set(compact('events'));
	}

	function pending() {
		if (isset($this->data)) {
			$memcache = new Memcache();
	    	$memcache->connect("localhost") or die ("Could not connect to memcache");
			$memcache->flush();
			if (isset($this->data["deny"])) {
				$this->Event->denyPending($this->data["Pending"]);
			}
			if (isset($this->data["approve"])) {
				$this->Event->approvePending($this->data["Pending"]);
			}
		}
		$userId = $this->Auth->user("id");		
		$calendars = $this->Calendar->getCalendarsUnderUser($userId, false, true);		
		$events = $this->Event->getEventIdsFromCalendars($calendars);		
		$this->paginate = array( "Event" => $this->Event->getEventPaginationParams($events) );
		$events = $this->paginate("Event");		
		$events = $this->Event->appendCalendarsEvents($events);
		$events = $this->Event->appendRepeats($events);
		$this->set(compact('events'));
	}

	function pdf() {
		return $this->rss();
	}

	function play() {
		$this->layout = 'ajax';
		$calendarId = 0;
		if (isset($this->params["named"]["calendar"])) {
			$calendarId = $this->Calendar->getIdByUrlName(strtolower($this->params["named"]["calendar"]));
		} else if (isset($this->params["calendar"])) {
			$calendarId = $this->Calendar->getIdByUrlName(strtolower($this->params["calendar"]));
		}
		$count = 100;
		if (isset($this->params["named"]["count"])) {
			$count = $this->params["named"]["count"];
		} else if (isset($this->params["url"]["count"])) {
			$count = $this->params["url"]["count"];
		}
		$events = $this->Event->getEvents($calendarId, date("Y-m-d H:1:s"), null, null, $count);
		$this->set(compact('events'));
	}
	
	function plug() {
		$calendar = $this->Calendar->getCalendarByUrlName($this->params["named"]["calendar"]);
		$calendarTitle = $calendar["Calendar"]["title"];
		$output = "";
		if (isset($this->data)) {
			$calendarId = $this->Calendar->getIdByUrlName(strtolower($this->params["named"]["calendar"]));
			foreach($this->data["Plug"] as $key => $value) {
				$output .= "/$key:$value";
			}
		}
		$this->set(compact('calendarTitle', 'output'));
	}

function rss() {
		$this->layout = 'ajax';
		
		$calendarId = 0;
		if (isset($this->params["named"]["calendar"])) {
			$calendarId = $this->Calendar->getIdByUrlName(strtolower($this->params["named"]["calendar"]));
		} else if (isset($this->params["calendar"])) {
			$calendarId = $this->Calendar->getIdByUrlName(strtolower($this->params["calendar"]));
		}
		$events = $this->Event->getRssEvents($calendarId, $this->params);
		$this->Calendar->recursive = -1;
		$calendarTitle = $this->Calendar->read("title", $calendarId);
		$calendarTitle = $calendarTitle["Calendar"]["title"];
		$this->Calendar->recursive = -1;
		$calendarDescription = $this->Calendar->read("description", $calendarId);
		$calendarDescription = $calendarDescription["Calendar"]["description"];

		$this->set(compact('events', 'calendarTitle', 'calendarDescription'));
	}

	function search() {
		if (isset($this->params["url"]["search"])) {
			$this->redirect(array("action" => "search", "search" => $this->params["url"]["search"] ));
			exit();
		}
		if (!empty($this->params['named']['search'])) {
			$this->paginate = $this->Event->searchPaginationParams($this->params);
		}
		$events = $this->paginate();
		$categories = array_merge( array( 0 => "All"), $this->Event->Category->find('list', array("recursive" => -1)) );		
		$this->set(compact('events', 'categories'));
	}

	function spreadsheet() {
		return $this->rss();
	}

	function view($id = null) {
		$userId = $this->Session->check('Auth.User');
		$event = $this->Event->read(null, $id);
		if (!$userId) {
			$active = 0; 
			foreach ($event["CalendarsEvent"] as $value) {
				if ($value["active"] > $active) {
					$active = 1;
					break;
				}
			}
			
			if ( $active == 0  ) {
				$this->Session->setFlash(__('You do not have permission to do that.', true));
				$this->redirect(array('action' => 'index'));
				return;
			}
		}
		
		
		if (!$id) {
			$this->Session->setFlash(__('Invalid Event', true));
			$this->redirect(array('action' => 'index'));
		}		
		if (count($this->params["pass"]) == 3) {
			$date = $this->params["pass"][0] . "-" . $this->params["pass"][1] . "-" . $this->params["pass"][2];
			$this->redirect(array('action' => 'index', "/calendar:main/start:$date/end:$date" ));			
		}
		
		$dateOutput = $this->Event->dateStringOutput($event);		
		$this->Event->eraseOldPictures($event);		
		$this->set('event', $event);
		$this->set('dateOutput', $dateOutput);
		$this->set('calendarName', $this->Event->getTopParentName($id));
		$this->set('startDateTime', $event["Repeat"][0]["start_date"]."T".$event["Repeat"][0]["start_time"]);
		$this->set('endDateTime', $event["Repeat"][0]["end_date"]."T".$event["Repeat"][0]["end_time"]);
		
		if ($this->Session->check('Auth.User')) {
			if ($this->User->isAuthorOrBetter($this->Session->read("Auth.User.id"), $id)) {
			//if ($this->User->isAuthorOrBetter(456, $id)) {
				$this->set('owner', true);
				$topPriv = $this->User->getHighestPriviledge($this->Session->read("Auth.User.id"));
				if ($topPriv <= 2) { 
					$calendars = $this->Calendar->getCalendarsUnderUser($this->Session->read("Auth.User.id"));
					$this->set('calendars', $calendars);
				}
			}
		}

	}	
	
	function _emailEditorsForPending($users) {
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
		
	}

}
?>
