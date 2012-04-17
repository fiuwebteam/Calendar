<?php
class AppController extends Controller {
	var $helpers = array('Html', 'Form', 'Javascript', 'Chooser', 'Text');
	var $components = array('Email','Session', 'Auth', 'Cookie');
	var $uses = array("Calendar", "User", "Event", "Tag");
	
	function beforeFilter() {
		$isLogged = false;
		if (!($this->Session->check('Auth.User'))) {
			$userName = $this->Cookie->read('userName');
			$password = $this->Cookie->read('password');
			if ($userName != "" && $password != "") {
				$data = array("User" => array("username" => $userName, "password" => $password));
				$isLogged = $this->Auth->login($data);
			}
		} else {
			$isLogged = true;
		}
		$this->set(compact('isLogged'));
		$priviledge = 6;
		if ($isLogged) {
			$priviledge = $this->User->getHighestPriviledge($this->Auth->user("id"));
		}
		$this->set(compact('priviledge'));
		//$this->Auth->allow('*');
		$this->Auth->userScope = array('User.active' => 1);
		$this->Auth->authorize = "controller";
		$this->Auth->loginRedirect = array('controller' => 'events', 'action' => 'index');
		$this->Auth->logoutRedirect = array('controller' => 'events', 'action' => 'index', 'calendar' => 'main');	
	}
	
function isAuthorized() {
	
		$params = array();
		$params["fields"] = array("CalendarsGroupsUser.group_id");
		$params["conditions"] = array("user_id" => $this->Auth->user("id"));
		$params["order"] = array("CalendarsGroupsUser.group_id ASC");
		$permissions = $this->User->CalendarsGroupsUser->find("first", $params);
		$topPermission = $permissions["CalendarsGroupsUser"]["group_id"];
	
		switch($this->name) {
			case "Users":
				switch($this->action) {
					
					case "add":
					case "delete":
					case "index":
					case "changeTier":
					case "view":
						if ($topPermission <= $this->User->ADMINISTRATOR) {return true;}
						else { return false; }
						break;
					// edit
					default:					
						return true;
						
				}			
				break;				
			case "Events":
				switch($this->action) {
					case "massInput":
					case "pending":																
						if ($topPermission <= $this->User->EDITOR) {return true;}
						else {return false;}
						break;									
					case "feature":
					case "appendEventToCalendar":
						if ($topPermission <= $this->User->ADMINISTRATOR) {return true; }
						else {return false;}
						break;
					// add delete edit my_events pdf play plug rss search view 
					default:						
						return true;
				}
				break;
			case "Calendars":
				switch($this->action) {
					case "index":
					case "users":
					case "events":
					case "add":
					case "edit":
					case "delete":
						if ($topPermission <= $this->User->ADMINISTRATOR) {return true;}
						else {return false;}
						break;
					default:
						return true;
				}
				break;			
			default:
				return true;
		}
	}
	
	
}
?>
