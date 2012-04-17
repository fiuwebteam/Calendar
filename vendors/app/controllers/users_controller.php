<?php
class UsersController extends AppController {

	var $name = 'Users';
	
	function beforeFilter() {
		parent::beforeFilter(); 
		$this->Auth->allow( 
			"ajaxlogin", "login", "logout", 
			"register", "activate", "resetpswd"
		);
	}
	
	function changeTier() {
		if (isset($this->data)) {
			if ($this->data["User"]["User"] == 1) {
				$this->Session->setFlash('User cannot be edited.', 'default', array('class' => 'flash_bad'));
			} else {
				$cgu = $this->User->CalendarsGroupsUser->find("first", array( "conditions" => 
				array("user_id" => $this->data["User"]["User"], 
				"calendar_id" => $this->data["Calendar"]["Calendar"] )));	
				if (empty($cgu)) {
					$newCGU = array();
    				$newCGU["calendar_id"] = $this->data["Calendar"]["Calendar"];
    				$newCGU["user_id"] = $this->data["User"]["User"];
    				if ($this->User->CalendarsGroupsUser->save($newCGU)) {
    					$this->Session->setFlash('User updated.', 'default', array('class' => 'flash_good'));
    				} else {
    					$this->Session->setFlash('Something went wrong.', 'default', array('class' => 'flash_bad'));
    				}
				} else {
					$this->User->CalendarsGroupsUser->id = $cgu["CalendarsGroupsUser"]["id"];
					$this->User->CalendarsGroupsUser->set("group_id", $this->data["Group"]["Group"]);
					if ($this->User->CalendarsGroupsUser->save()) {
						$this->Session->setFlash('User updated.', 'default', array('class' => 'flash_good'));
					} else {			
						$this->Session->setFlash('Something went wrong.', 'default', array('class' => 'flash_bad'));				
					}
				}
			}
		}
		$userId = $this->Auth->user("id");				
		$calendars = $this->Calendar->getCalendarsUnderUser($userId);		
		$users = $this->User->getUsersUnderCalendars($calendars);
		// Making them pretty for the form
		$tmp = array();
		foreach($users as $user) { $tmp[$user["User"]["id"]] = $user["User"]["username"]; }
		$users = $tmp;		
		$tmp = array();
		foreach($calendars as $calendar) { $tmp[$calendar["Calendar"]["id"]] = $calendar["Calendar"]["title"]; }
		$calendars = $tmp;		
		$this->set(compact('calendars', 'users'));
	}
	
	function index() {
		$userId = $this->Auth->user("id");
		$calendarsIds = $this->User->getCalendarsUnderUser($userId);	
		$filter = (isset($this->params["named"]["filter"])) ? $filter = $this->params["named"]["filter"] : "";
		$userIds = $this->User->getUsersIdsUnderCalendars($calendarsIds, $filter);		
		$this->paginate = $this->User->getUsersPaginationParams($userIds);		
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}
	
	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalid User.', 'default', array('class' => 'flash_bad'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$data = $this->data;
    		$data["User"]["active"] = 1;
    		$this->User->create();
			if ($this->User->save($data)) {
				$calendars = $this->Calendar->getAllCalendars();				
    			$calendarsGroupsUser = array();
    			foreach($calendars as $calendar) {
    				$tmp = array();
    				$tmp["calendar_id"] = $calendar["Calendar"]["id"];
    				$tmp["user_id"] = $this->User->id;
    				$calendarsGroupsUser[] = $tmp;
    			}
    			if ($this->User->CalendarsGroupsUser->saveAll($calendarsGroupsUser)) {
					$this->Session->setFlash('The User has been saved.', 'default', array('class' => 'flash_good'));					
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash('The User could not be saved.', 'default', array('class' => 'flash_bad'));					
				}
			} else {
				$this->Session->setFlash('The User could not be saved.', 'default', array('class' => 'flash_bad'));
			}
		}
		$groups = $this->User->Group->find('list');
		$calendars = $this->User->Calendar->find('list');
		$this->set(compact('groups', 'calendars'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid User', true));
			$this->redirect(array('action' => 'index'));
		}
		if ($this->Auth->user("id") != $id) {
			$this->Session->setFlash(__("This isn't your profile.", true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->User->save($this->data)) {
				$this->Session->setFlash('The User has been saved.', 'default', array('class' => 'flash_good'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The User could not be saved.', 'default', array('class' => 'flash_bad'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
		}		
	}

	function delete($id = null) {
		if (!$id || $id == 1) {		
			$this->Session->setFlash('Invalid ID for User.', 'default', array('class' => 'flash_bad'));			
			$this->redirect(array('action' => 'index'));
		}
		if ($this->User->del($id)) {
			$params = array("user_id" => $id);
			$this->User->CalendarsGroupsUser->deleteAll($params, true);
			$this->Session->setFlash('User deleted.', 'default', array('class' => 'flash_good'));			
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash('The User could not be deleted. Please, try again.', 'default', array('class' => 'flash_bad'));
		$this->redirect(array('action' => 'index'));
	}
	
	function ajaxlogin() {
		$this->layout = "ajax";
		$isLogged = $this->Auth->login($this->data);
		$priviledge = 6;
		$userId = 0;
		if ($isLogged && $this->data["User"]["remember"] == 1) {
			$this->Cookie->write('userName', $this->data["User"]['username'], true, '30 days');
			$this->Cookie->write('password', $this->data["User"]['password'], true, '30 days');
			$userId = $this->Auth->user("id");
			$priviledge = $this->User->getHighestPriviledge($userId);			
		}		
		$this->set(compact('priviledge'));
		$this->set(compact('userId'));
    }
	
	function login() {
		if ($this->data["User"]["remember"] == 1) {
			$this->Cookie->write('userName', $this->data["User"]['username'], true, '30 days');
			$this->Cookie->write('password', $this->data["User"]['password'], true, '30 days');
		}
    }

    function logout() {
    	$this->Cookie->del("userName");
    	$this->Cookie->del("password");
        $this->redirect($this->Auth->logout());
    }
    
    function register() {
    	if (!empty($this->data)) {
    		$data = $this->data;
    		$data["User"]["receives_email"] = 1;
    		$data["User"]["active"] = 0;
    		$data["User"]["activation_code"] = substr(sha1(date("YmdGisu")), 0, 10);
    		$this->User->create();
			
    		if ($this->User->save($data)) {
    			$calendars = $this->User->Calendar->getAllCalendars();
    			$calendarsGroupsUser = array();
    			foreach($calendars as $calendar) {
    				$tmp = array();
    				$tmp["calendar_id"] = $calendar["Calendar"]["id"];
    				$tmp["user_id"] = $this->User->id;
    				$calendarsGroupsUser[] = $tmp;
    			}
				if ($this->User->CalendarsGroupsUser->saveAll($calendarsGroupsUser)) {
					$this->Email->from = 'FIU Calendar <noreply@fiu.edu>';
					$this->Email->to = ($this->data["User"]['username'] . '<' . $this->data["User"]['email'] . '>');
					$this->Email->subject = 'FIU Calendar Registration';
					$body =
"This is your confirmation email for the FIU Calendar.
Please vist the following site to activate your account:
http://" . str_replace("register", "activate", ($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])) . "/" . $data["User"]["activation_code"] ;
					$this->Email->send($body);			
					$this->Session->setFlash('The User has been registered. You will receive an email to activate your account.', 'default', array('class' => 'flash_good'));
					$this->redirect(array("controller" => "main"));
				} else {
					$this->Session->setFlash('The User could not be saved. Please, try again.', 'default', array('class' => 'flash_bad'));
				}				
			} else {				
				$this->Session->setFlash('The User could not be saved. Please, try again.', 'default', array('class' => 'flash_bad'));
			}
		}
    }
    
    function activate($code = null) {
    	if ($code == null) {$this->redirect(array('action' => 'login')); return;}
    	$user = $this->User->find('first', array('conditions' => array('User.activation_code' => $code)));
    	if (empty($user) || $user["User"]["active"]) {
    		$this->Session->setFlash(__('User not found.', true));
    		$this->redirect(array('controller' => 'main'));
    	}
    	$this->User->id = $user["User"]["id"];
    	$this->User->set('active', 1);
    	$this->User->save();
    	$this->Session->setFlash('The User has been activated. Welcome!', 'default', array('class' => 'flash_good'));
    	
		$this->redirect(array('controller' => 'main'));
    }
    
	function resetpswd() {
    	if (!empty($this->data)) {
    		$params = array();
    		$params["conditions"] = array("User.username" => $this->data["User"]["username"], "User.email" => $this->data["User"]["email"] );
    		$user = $this->User->find("first", $params);
    		if (empty($user)) {
    			$this->Session->setFlash('Error: invalid username and/or password', 'default', array('class' => 'flash_bad'));
    		} else {
    			$password = substr(sha1( date("Y-m-d G:i:s") ), 0, 6 );
    			$data = array();    			
    			$data["User"] = array( "password_confirm" => $password,  "password" => $this->Auth->password($password), "id" => $user["User"]["id"] );
    			if ($this->User->save($data)) {
    				$this->Email->from = 'FIU Calendar <noreply@fiu.edu>';
					$this->Email->to = ($user["User"]['username'] . '<' . $user["User"]['email'] . '>');
					$this->Email->subject = 'FIU Calendar Password Reset';
					$body = "
Hello User {$user["User"]['username']}, 
Your new password is '$password'
If you didn't initialize this, please contact an administrator of the FIU Calendar immediately." ;
					$this->Email->send($body);
	    			$this->Session->setFlash('Please check your email for your new password.', 'default', array('class' => 'flash_good'));
	    			
    			} else {    								
    				$this->Session->setFlash('Unable to save data! Contact the Administrator.', 'default', array('class' => 'flash_bad'));
    			}
    		}
    	}
    }
    
}
?>