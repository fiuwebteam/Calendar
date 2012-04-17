<?php
class Event extends AppModel {
	
	var $flyerSize = 40960;
	var $flyerWidth = 330;
	var $flyerHeight = 136;
	
	var $thumbnailSize = 15360;
	var $thumbnailWidth = 69;
	var $thumbnailHeight = 69;

	var $name = 'Event';
	var $validate = array(
		'id' => array('numeric'),
		'title' => array(
			'rule'=>array('notEmpty'),
			'message'=>'Cannot be blank'),
		'description' => array(
			'rule'=>array('notEmpty'),
			'message'=>'Cannot be blank'),
		'location' => array(
			'rule'=>array('notEmpty'),
			'message'=>'Cannot be blank'),
		'contact' => array(
			'rule'=>array('notEmpty'),
			'message'=>'Cannot be blank'),
		//'email' => array('email'),
		//'phone' => array('phone'),
		'url' => array(
			'rule' => array('urlRule'),
			'message' => 'i.e., "http://www.google.com"'
		 ),
		'type' => array(
	        'rule' => array('comparison', '>=', 1),
	        'message' => 'Please choose an event type.'
	    ),
		'user_id' => array('numeric'),
		'category_id' => array('numeric')	   
	);
	var $actsAs = array('Sphinx');

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id'
		)
	);

	var $hasMany = array(
		'Repeat' => array(
			'className' => 'Repeat',
			'foreignKey' => 'event_id',
			'dependent' => false
		),
		'CalendarsEvent' => array(
			'className' => 'CalendarsEvent',
			'foreignKey' => 'event_id',
			'dependent' => false
		)
	);

	var $hasAndBelongsToMany = array(
		'Calendar' => array(
			'className' => 'Calendar',
			'joinTable' => 'calendars_events',
			'foreignKey' => 'event_id',
			'associationForeignKey' => 'calendar_id',
			'unique' => true
		),
		'Tag' => array(
			'className' => 'Tag',
			'joinTable' => 'events_tags',
			'foreignKey' => 'event_id',
			'associationForeignKey' => 'tag_id',
			'unique' => true		
		)
	);
	
	function getEventType($data){
		$startDate = $data["Event"]["start"]["month"].
		 			 $data["Event"]["start"]["day"].
		 			 $data["Event"]["start"]["year"];	
		$startTime = $data["Event"]["start"]["hour"].
		 			 $data["Event"]["start"]["minute"].
		 			 $data["Event"]["start"]["meridian"];
 		$endDate   = $data["Event"]["end"]["month"].
 					 $data["Event"]["end"]["day"].
		             $data["Event"]["end"]["year"];
		$endTime   = $data["Event"]["end"]["hour"].
		             $data["Event"]["end"]["minute"].
		             $data["Event"]["end"]["meridian"];
			             

		if ($startDate != $endDate) 
			$data['Event']['type'] = 2; //ongoing
		else if ( $startTime != $endTime ) 
			$data['Event']['type'] = 1; //normal
		else 
			$data['Event']['type'] = 3; //deadline
		
		return 	$data['Event']['type'];
	
	}
	
	function errorMessage($error) {
		$message = "Something went wrong with your file upload.";
		switch($error) {
			case 1:				
			case 2:
				$message = "The uploaded file exceeds the upload max filesize.";
				break;
			case 3:
				$message = "The uploaded file was only partially uploaded.";
				break;
			case 6:
				$message = "Missing a temporary folder.";
				break;
			case 7:
				$message = "Failed to write file to disk.";
				break;
		}
		return $message;
	}
	
	function eventTagsToSave($eventId, $tags) {
		$events_tags = array();
		foreach( array_unique(split("," , str_replace(' ', '',$tags))) as $tag) {
			$tagId = 0;
			$existingTags = $this->Tag->find("first", array("recursive" => -1, "conditions" => array('title' => $tag)));
			if (empty($existingTags)) {
				$this->Tag->create();
				$this->Tag->save(array("Tag" => array( 'title' => $tag )));
				$tagId = $this->Tag->id;
			} else {
				$tagId = $existingTags["Tag"]["id"];
			}
			$events_tags[] = array("event_id" => $eventId, "tag_id" => $tagId);
		}
		return $events_tags;
	}
	
	function calendarsToSave($calendarId) {
		foreach($this->Calendar->getParents($calendarId) as $calendar) {
			$allCalendars[] = $calendar["Calendar"]["id"];
		}
		return array_unique($allCalendars);
	}
	
	function calendarEventsToSave($eventId, $eventType, $calendars, $repeats) {
		$userId = $_SESSION["Auth"]["User"]["id"];
		$params = array(
			"conditions" => array("user_id" => $userId),
			"recursive" => -1,
			"fields" => array("calendar_id", "group_id")			
		);
		$calendarsGroupsUsers = $this->Calendar->CalendarsGroupsUser->find("all", $params);
		$calendars_events = array();
		$existingCalendarsEvent = $this->CalendarsEvent->find("all",
		array(
			"recursive" => -1, 
			"conditions" => array("event_id" => $eventId),
			"fields" => "featured, start_date_time, end_date_time, calendar_id, active"
		));		
		switch($eventType) {
			// normal
			case 1:
				foreach ($repeats as $repeat) {
					$marker = $repeat["start_date"];
					$data = array();
					$tmp = explode( ".", $repeat["variable"]);
					if ($repeat["type"] == 3) {
						$data["Monday"] = $tmp[0];
						$data["Tuesday"] = $tmp[1];
						$data["Wednesday"] = $tmp[2];
						$data["Thursday"] = $tmp[3];
						$data["Friday"] = $tmp[4];
						$data["Saturday"] = $tmp[5];
						$data["Sunday"] = $tmp[6];
					}
					while($marker <= $repeat["end_date"]) {
						if ($repeat["type"] != 3 || ( $repeat["type"] == 3 && $data[date("l", strtotime($marker))] == 1 )) {
							$start_date_time = "$marker {$repeat["start_time"]}";
							$end_date_time = "$marker {$repeat["end_time"]}";
							foreach($calendars as $calendarId) {
								$tmp = array(
									"start_date_time" => $start_date_time, 
									"end_date_time" => $end_date_time, 
									"calendar_id" => $calendarId, 
									"event_id" => $eventId, 
									"featured" => 0,
								 	"active" => 0
								);								
								foreach($existingCalendarsEvent as $ece) {
									if ($ece["CalendarsEvent"]["start_date_time"] == $start_date_time && 
										$ece["CalendarsEvent"]["end_date_time"] == $end_date_time &&
										$ece["CalendarsEvent"]["calendar_id"] == $calendarId) {
										if ($ece["CalendarsEvent"]["featured"] == 1) {
											$tmp["featured"] = 1;
										}
										if ($ece["CalendarsEvent"]["active"] == 1) {
											$tmp["active"] = 1;
										}
										break;
									}
								}
								
								foreach ($calendarsGroupsUsers as $cgu) {
									if ($cgu["CalendarsGroupsUser"]["calendar_id"] == $calendarId &&
									$cgu["CalendarsGroupsUser"]["group_id"] <= $this->User->AUTHOR ) {
										$tmp["active"] = 1;
										break;
									}
								}								
								$calendars_events[] = $tmp;
							}
						}
						switch($repeat["type"]) {
							case 1: $marker = date("Y-m-d", strtotime($marker . "+1 day")); break;
							case 2: $marker = date("Y-m-d", strtotime($marker . "+{$repeat["variable"]} day")); break;
							case 3: $marker = date("Y-m-d", strtotime($marker . "+1 day")); break;
							case 4: $marker = date("Y-m-d", strtotime($marker . "+1 month")); break;
							case 5: $marker = date("Y-m-d", strtotime($marker . "+1 year")); break;
						}
					}
				}					
				break;
				// ongoing
			case 2:
				foreach ($repeats as $repeat) {
					$start_date_time = "{$repeat["start_date"]} {$repeat["start_time"]}";
					$end_date_time = "{$repeat["end_date"]} {$repeat["end_time"]}";
					foreach($calendars as $calendarId) {
						$tmp = array( "start_date_time" => $start_date_time, "end_date_time" => $end_date_time, "calendar_id" => $calendarId, "event_id" => $eventId, "featured" => 0, "active" => 0);
						foreach($existingCalendarsEvent as $ece) {
							if ($ece["CalendarsEvent"]["start_date_time"] == $start_date_time && 
								$ece["CalendarsEvent"]["end_date_time"] == $end_date_time &&
								$ece["CalendarsEvent"]["calendar_id"] == $calendarId) {
								if ($ece["CalendarsEvent"]["featured"] == 1) { $tmp["featured"] = 1; }
								if ($ece["CalendarsEvent"]["active"] == 1) { $tmp["active"] = 1; }
								break;
							}
						}							
						foreach ($calendarsGroupsUsers as $cgu) {
							if ($cgu["CalendarsGroupsUser"]["calendar_id"] == $calendarId &&
							$cgu["CalendarsGroupsUser"]["group_id"] <= $this->User->AUTHOR ) {
								$tmp["active"] = 1;
								break;
							}
						}
						$calendars_events[] = $tmp;
					}					
				}
				break;
				// deadline
			case 3:
				foreach ($repeats as $repeat) {
					$end_date_time = $start_date_time = "{$repeat["start_date"]} {$repeat["start_time"]}";
					foreach($calendars as $calendarId) {
						$tmp = array( "start_date_time" => $start_date_time, "end_date_time" => $end_date_time, "calendar_id" => $calendarId, "event_id" => $eventId, "featured" => 0, "active" => 0);
						
						foreach($existingCalendarsEvent as $ece) {
							if ($ece["CalendarsEvent"]["start_date_time"] == $start_date_time && 
								$ece["CalendarsEvent"]["end_date_time"] == $end_date_time &&
								$ece["CalendarsEvent"]["calendar_id"] == $calendarId) {
								if ($ece["CalendarsEvent"]["featured"] == 1) {
									$tmp["featured"] = 1;
								}
								if ($ece["CalendarsEvent"]["active"] == 1) {
									$tmp["active"] = 1;
								}
								break;
							}
						}						
						foreach ($calendarsGroupsUsers as $cgu) {
							if ($cgu["CalendarsGroupsUser"]["calendar_id"] == $calendarId &&
							$cgu["CalendarsGroupsUser"]["group_id"] <= $this->User->AUTHOR ) {
								$tmp["active"] = 1;
								break;
							}
						}
						$calendars_events[] = $tmp;
					}
				}
				break;
		}
		return $calendars_events;
	}
	
	function save($data = null, $validate = true, $fieldList = array()) {
		// Are there images, if yes, validate the images.
		if ( $data["Event"]["flyer"]["error"] == 0 ) { 
			if (!$this->flyerSizeRule($data)) {
				$this->invalidate("flyer", "Your flyer must not be bigger than ".($this->flyerSize / 1024)."KB.");
			} else if (!$this->flyerDimensionsRule($data)) {
				$this->invalidate("flyer", "Your flyer must be ".$this->flyerWidth." x ".$this->flyerHeight." pixels.");
			}
		} else if ($data["Event"]["flyer"]["error"] != 4) {
			$this->invalidate("flyer", $this->errorMessage($data["Event"]["flyer"]["error"]));
		}	
		
		if ( $data["Event"]["thumbnail"]["error"] == 0 ) { 
			if (!$this->thumbnailSizeRule($data)) {
				$this->invalidate("thumbnail", "Your thumbnail must not be bigger than ".($this->thumbnailSize / 1024)."KB.");
			} else if (!$this->thumbnailDimensionsRule($data)) {
				$this->invalidate("thumbnail", "Your thumbnail must be ".$this->thumbnailWidth."x ".$this->thumbnailHeight." pixels.");	
			}
		} else if ($data["Event"]["thumbnail"]["error"] != 4) {
			$this->invalidate("thumbnail", $this->errorMessage($data["Event"]["thumbnail"]["error"]));			
		}
		
		// Now validate event inputs
		// This function will replace the variables for the image uploads. 
		// So don't overwrite the original $data variable or we lose the location of the temporary file.
		
		$data["Event"]["type"] = $this->getEventType($data);
		$event = $this->cleanEventArray($data);
		
		$eventSaved = parent::save($event, $validate, $fieldList);
		
		$eventId = $this->id;
		
		if ($eventSaved) {
			$events_tags = $this->eventTagsToSave($eventId, $data["Tag"]["Tag"]);
			$privateCheck = $this->Calendar->read("private", $data["Calendar"]["Calendar"]);
			if ($privateCheck["Calendar"]["private"] == 1) {
				$calendars = array(0 => $data["Calendar"]["Calendar"]);
			} else {
				$calendars = $this->calendarsToSave($data["Calendar"]["Calendar"]);
			}
			$repeats = $this->repeatsToSave($eventId, $data);
			
			$calendars_events = $this->calendarEventsToSave($eventId, $event["Event"]["type"], $calendars, $repeats);
			
			$validEventsTags = $this->EventsTag->saveAll($events_tags, array("validate" => "only"));
			$validRepeats = $this->Repeat->saveAll($repeats, array("validate" => "only"));
			$validCalendarsEvents = $this->CalendarsEvent->saveAll($calendars_events, array("validate" => "only"));
			
			
			if ($validEventsTags && $validRepeats && $validCalendarsEvents) {
				
				$params = array("event_id" => $eventId);
				$this->EventsTag->deleteAll($params, true);
				$this->Repeat->deleteAll($params, true);
				$this->CalendarsEvent->deleteAll($params, true);				
				
				$this->EventsTag->saveAll($events_tags, array("validate" => false));
				$this->Repeat->saveAll($repeats, array("validate" => false));
				$this->CalendarsEvent->saveAll($calendars_events, array("validate" => false));
				
				$imageLocation = getcwd() . "/img/";
				if ($event["Event"]["flyer"] != "") {
					move_uploaded_file($data["Event"]["flyer"]['tmp_name'], $imageLocation . "flyers/" . $event["Event"]["flyer"] );
				}
				if ($event["Event"]["thumbnail"] != "") {
					move_uploaded_file($data["Event"]["thumbnail"]['tmp_name'], $imageLocation . "thumbnails/" . $event["Event"]["thumbnail"] );
				}
				return true;				
			} else {					
				$this->del($eventId);
				return false;
			}
		}
		
	}
	
	
	function repeatsToSave($eventId, $formData) {
		$repeats = array();
		$repeat = array();
		$repeat["event_id"] = $eventId;
		$repeat["type"] = 0;
		$repeat["variable"] = 0;
		$repeat["start_date"] = $formData["Event"]["start"]["year"] . "-" . $formData["Event"]["start"]["month"] . "-" . $formData["Event"]["start"]["day"];
		if ($formData["Event"]["start"]["meridian"] == "am" && $formData["Event"]["start"]["hour"] == "12") {
			$formData["Event"]["start"]["hour"] = "00";
		}
		$repeat["start_time"] = (($formData["Event"]["start"]["meridian"] == "pm" && $formData["Event"]["start"]["hour"] != "12") ? sprintf("%02d",(12 + $formData["Event"]["start"]["hour"])) : sprintf("%02d",$formData["Event"]["start"]["hour"]) ) . ":" . sprintf("%02d",$formData["Event"]["start"]["minute"]) . ":00";
		$repeat["end_date"] = $formData["Event"]["end"]["year"] . "-" . $formData["Event"]["end"]["month"] . "-" . $formData["Event"]["end"]["day"];
		if ($formData["Event"]["end"]["meridian"] == "am" && $formData["Event"]["end"]["hour"] == "12") {
			$formData["Event"]["end"]["hour"] = "00";
		}
		$repeat["end_time"] = (($formData["Event"]["end"]["meridian"] == "pm" && $formData["Event"]["end"]["hour"] != "12") ? sprintf("%02d",(12 + $formData["Event"]["end"]["hour"])) : sprintf("%02d",$formData["Event"]["end"]["hour"]) ) . ":" . sprintf("%02d",$formData["Event"]["end"]["minute"]) . ":00";
		if ($repeat["end_time"] == "24:00:00") {
			$repeat["end_time"] = "00:00:00";
		}		
		if ($formData["Event"]["type"] > 1) {
			$repeat["type"] = $formData["Event"]["type"] + 4;
			$repeat["variable"] = 0;
			$repeats[] = $repeat;
		} else {	
			for($x = 1; $x <= $formData["Event"]["repeats"]; $x++ ) {
				$repeat["type"] = $formData["Event"]["repeatsType$x"] + 1;
				$repeat["variable"] = $formData["Event"]["dateParameter_1_$x"];
				switch($formData["Event"]["repeatsType$x"]) {
					// do not repeat
					case 0:
						$repeat["variable"] = 0;
						$repeat["end_date"] = $repeat["start_date"];
						break;
					// repeat daily
					case 1:
						$repeat["end_date"] = date("Y-m-d", strtotime("{$repeat["start_date"]} + {$formData["Event"]["dateParameter_2_$x"]} days"));
						break;
					// repeat weekly
					case 2:
						$repeat["variable"] = $formData["Event"]["Monday"][$x].".".$formData["Event"]["Tuesday"][$x].".".$formData["Event"]["Wednesday"][$x].".".$formData["Event"]["Thursday"][$x].".".$formData["Event"]["Friday"][$x].".".$formData["Event"]["Saturday"][$x].".".$formData["Event"]["Sunday"][$x];
						$repeat["end_date"] = date("Y-m-d", strtotime("{$repeat["start_date"]} + {$formData["Event"]["dateParameter_2_$x"]} weeks"));
						break;
					// repeat monthly
					case 3:
						$repeat["end_date"] = date("Y-m-d", strtotime("{$repeat["start_date"]} + {$formData["Event"]["dateParameter_2_$x"]} months"));
						break;
					// repeat yearly
					case 4:
						$repeat["end_date"] = date("Y-m-d", strtotime("{$repeat["start_date"]} + {$formData["Event"]["dateParameter_2_$x"]} years"));
						break;
				}
				$repeats[] = $repeat;
			}
		}
		return $repeats;		
	}
	
	function generateImageName($type, $image) {
		return substr(sha1( $type . $image["name"] . date("YmdGisu")), 0, 10) . "." . substr($image["type"], 6);
	}
	
	function cleanEventArray($data) {
		
		$oldFlyer = $oldThumbnail = $oldAuthor = null;
		if (isset($this->id)) {
			$this->recursive = -1;
			$oldFlyer = $this->read("flyer");
			$oldFlyer = $oldFlyer["Event"]["flyer"];
			$oldThumbnail = $this->read("thumbnail");
			$oldThumbnail = $oldThumbnail["Event"]["thumbnail"];
			$oldAuthor = $this->read("user_id");
			$oldAuthor = $oldAuthor["Event"]["user_id"];
		}
		
		$event = array();
		if (isset($data["Event"]["id"])) {
			$event["Event"]["id"] = $data["Event"]["id"];
		}
		$event["Event"]["title"] = $data["Event"]["title"];
		$event["Event"]["description"] = $data["Event"]["description"];
		$event["Event"]["location"] = $data["Event"]["location"];
		$event["Event"]["contact"] = $data["Event"]["contact"];
		$event["Event"]["email"] = $data["Event"]["email"];
		$event["Event"]["phone"] = $data["Event"]["phone"];
		$event["Event"]["url"] = $data["Event"]["url"];
		$event["Event"]["type"] = $data["Event"]["type"]; 
		$event["Event"]["user_id"] = ($oldAuthor != null) ? $oldAuthor : $_SESSION["Auth"]["User"]["id"];
		$event["Event"]["category_id"] = $data["Event"]["category_id"];
		$event["Event"]["fblike"] = isset($data["Event"]["fblike"]) ? $data["Event"]["fblike"] : "";
		$event["Event"]["is_ical"] = isset($data["Event"]["is_ical"]) ? $data["Event"]["is_ical"] : "";
		if ($data["Event"]["flyer"]["error"] == 0 && $event["Event"]["flyer"] != "") {
			if (empty($this->validationErrors["flyer"])) {
				$event["Event"]["flyer"] = $this->generateImageName("flyer", $data["Event"]["flyer"]);
			}
		} else if ($oldFlyer != null) { $event["Event"]["flyer"] = $oldFlyer; } 
		else { $event["Event"]["flyer"] = ""; }
		
		if ($data["Event"]["thumbnail"]["error"] == 0  && $event["Event"]["thumnail"] != "") {
			if (empty($this->validationErrors["thumnail"])) {
				$event["Event"]["thumbnail"] = $this->generateImageName("thumbnail", $data["Event"]["thumbnail"]);
			}
		} else if ($oldThumbnail != null) { $event["Event"]["thumbnail"] = $oldThumbnail; } 
		else { $event["Event"]["thumbnail"] = ""; }
		
		return $event;
	}
	
	function flyerSizeRule($data) {
		if ($data["Event"]['flyer']['size'] < $this->flyerSize) {
			return true;
		}
		return false;
	}
	
	function flyerDimensionsRule($data) {
		list($width, $height, $type, $attr) = getimagesize($data["Event"]['flyer']["tmp_name"]);
		if ($width != $this->flyerWidth || $height != $this->flyerHeight) {
			return false;
		}
		return true;
	}
	
	function thumbnailSizeRule($data) {
		if ($data["Event"]['thumbnail']['size'] > $this->thumbnailSize) {
			return false;
		}
		return true;
	}
	
	function thumbnailDimensionsRule($data) {
		list($width, $height, $type, $attr) = getimagesize($data["Event"]['thumbnail']["tmp_name"]);
		if ($width != $this->thumbnailWidth || $height != $this->thumbnailHeight) {
			return false;
		}
		return true;
	}	
	
	function urlRule( $field=array()) {
    	if ($field["url"] == "") {
    		return true;
    	} 
    	return preg_match("((https?|ftp|gopher|telnet|file|notes|ms-help):((//)|(\\\\))+[\w\d:#@%/;$()~_?\+-=\\\.&]*)", $field["url"]);
    }
    
    function getEvents($calendarId, $start, $end = null, $categoryId = null, $limit = 100, $featured = 0, $isRss = false) {
    	
    	$memcache = new Memcache();
    	$memcache->connect("localhost") or die ("Could not connect to memcache");
    	$memKey = sha1("$calendarId $start $end $categoryId $limit $featured testData");
    	
    	$results = $memcache->get($memKey);
    	
    	if ($results != null) {
    		return $results;
    	} else {
    		$params = array();
			$params["conditions"] = array(
				"CalendarsEvent.calendar_id" => $calendarId,
				"CalendarsEvent.end_date_time >= " => $start,			
				"CalendarsEvent.active" => 1				
			);
			if ($featured) {
				$params["conditions"]["CalendarsEvent.featured"] = $featured;
			}
    		if ($isRss) {
				$params["conditions"]["Event.type !="] = 2;
			}
			if ($end != null) { $params["conditions"]["CalendarsEvent.start_date_time <= "] = $end; }
			if ($categoryId != null) { $params["conditions"]["Event.category_id"] = $categoryId; }
			$params["order"] = "CalendarsEvent.start_date_time ASC";
			$params["limit"] = $limit;
			$params["recursive"] = -1;
			$params["fields"] = array("Event.*", "CalendarsEvent.start_date_time", "CalendarsEvent.end_date_time");
			$params["joins"] = array(
				array(
					'table' => 'calendars_events',
					'alias' => 'CalendarsEvent',
					'type' => 'LEFT',
					'conditions' => array('Event.id = CalendarsEvent.event_id')
				)
			);
			$results = $this->find("all", $params);			
			$memcache->set($memKey, $results, 0, 3600);
			return $results;
    	}
    }
    
    function calendarEventsToAppend($event, $calendar) {
    	
    	$cE = $this->CalendarsEvent->find("first", array("fields" => "calendar_id", "group" => "calendar_id", "recursive" => -1, "conditions" => array("event_id" => $event)));
		$cE = $this->CalendarsEvent->find("all", array("conditions" => array("event_id" => $event, "calendar_id" => $cE["CalendarsEvent"]["calendar_id"] )));
		
		$input = array();
		foreach($cE as $value) {
			$tmp = array(
				"start_date_time" => $value["CalendarsEvent"]["start_date_time"], 
				"end_date_time" => $value["CalendarsEvent"]["end_date_time"], 
				"calendar_id" => $calendar, 
				"event_id" => $event, 
				"featured" => 0,
			 	"active" => 1
			);
			$input[] = $tmp;
		}
		
		return $input;
    	
    }
    
    function addCalendarsEvents($events) {
  	  	$params = array();
		$params["conditions"]["OR"][] = array("CalendarsEvent.event_id" => 0);
		foreach($events as $event) {
			$params["conditions"]["OR"][] = array("CalendarsEvent.event_id" => $event["Event"]["id"]);
		}
		$params["fields"] = array("Calendar.title", "CalendarsEvent.*");
		$params["recursive"] = -1;
		$params["joins"] = array(
		array(
				'table' => 'calendars',
				'alias' => 'Calendar',
				'type' => 'LEFT',
				'conditions' => array('Calendar.id = CalendarsEvent.calendar_id')				
		)
		);
		$params['order'] = 'calendar_id';
		//************************************************
		$calendarsEvents = $this->CalendarsEvent->find("all", $params );
		foreach($events as $key => $event) {
			foreach($calendarsEvents as $calendarsEvent) {
				if ($calendarsEvent["CalendarsEvent"]["event_id"] == $event["Event"]["id"]) {
					$tmp = $calendarsEvent["CalendarsEvent"];
					$tmp["Calendar_title"] = $calendarsEvent["Calendar"]["title"];
					$events[$key]["CalendarsEvent"][] = $tmp;
				}
			}
		}
		return $events;		
    }
    
    function getFeaturedEventsForCalendar($calendarId) {
    	$params = array(
			'recursive' => 0,
			'order' => array('CalendarsEvent.start_date_time' => 'ASC'),
			'fields' => array('Event.*', 'CalendarsEvent.*'),
			'joins' => array(
				array(
					'table' => 'calendars_events',
					'alias' => 'CalendarsEvent',
					'type' => 'LEFT',
					'conditions' => array('Event.id = CalendarsEvent.event_id')
				)
			),
			'limit' => 5,
			'conditions' => array(
				"CalendarsEvent.calendar_id" => $calendarId,
				"CalendarsEvent.end_date_time >=" => (date("Y-m-d G:i:s")),
				"CalendarsEvent.featured" => 1,
				"CalendarsEvent.active" => 1
			)
		);

		return $this->find("all", $params);
    }
    
    function dateStringOutput($event) {
    	$dateOutput = "";
    	switch($event["Event"]["type"]) {
			// normal
			case 1:
				foreach($event["Repeat"] as $repeat) {
					switch($repeat["type"]) {
						// once
						case 1:
							$dateOutput .= "On " . date( "F j, Y", strtotime($repeat["start_date"])) . " from ";
							$dateOutput .= date("g:i a", strtotime($repeat["start_time"]));
							$dateOutput .= " to " . date("g:i a", strtotime($repeat["end_time"]));
							break;
							// daily
						case 2:
							$dateOutput .= "Every " . floor($repeat["variable"]) . " days starting on ";
							$dateOutput .= date("F j, Y", strtotime($repeat["start_date"]));
							$dateOutput .= " from " . date("g:i a", strtotime($repeat["start_time"]));
							$dateOutput .= " to " . date("g:i a", strtotime($repeat["end_time"])) . ". Ending on ";
							$dateOutput .= date( "F j, Y", strtotime($repeat["end_date"]));
							break;
							// weekly
						case 3:
							$dateOutput .= "Every ";
							$weekdays = explode(".", $repeat["variable"]);
							foreach ($weekdays as $key => $value) {
								if ($value == "1") {
									switch($key) {
										case 0:
											$dateOutput .= "Monday ";
											break;
										case 1:
											$dateOutput .= "Tuesday ";
											break;
										case 2:
											$dateOutput .= "Wednesday ";
											break;
										case 3:
											$dateOutput .= "Thursday ";
											break;
										case 4:
											$dateOutput .= "Friday ";
											break;
										case 5:
											$dateOutput .= "Saturday ";
											break;
										case 6:
											$dateOutput .= "Sunday ";
											break;
									}
								}
							}
							$dateOutput .= " starting on ";
							$dateOutput .= date("F j, Y", strtotime($repeat["start_date"]));
							$dateOutput .= " from " . date("g:i a", strtotime($repeat["start_time"]));
							$dateOutput .= " to " . date("g:i a", strtotime($repeat["end_time"])) . ". Ending on ";
							$dateOutput .= date( "F j, Y", strtotime($repeat["end_date"]));
							break;
							// monthly
						case 4:
							$dateOutput .= "Every " . floor($repeat["variable"]) . "th day of the month starting on ";
							$dateOutput .= date("F j, Y", strtotime($repeat["start_date"]));
							$dateOutput .= " from " . date("g:i a", strtotime($repeat["start_time"]));
							$dateOutput .= " to " . date("g:i a", strtotime($repeat["end_time"])) . ". Ending on ";
							$dateOutput .= date( "F j, Y", strtotime($repeat["end_date"]));
							break;
							// yearly
						case 5:
							$dateOutput .= "Every " . floor($repeat["variable"]) . "th day of the year starting on ";
							$dateOutput .= date("F j, Y", strtotime($repeat["start_date"]));
							$dateOutput .= " from " . date("g:i a", strtotime($repeat["start_time"]));
							$dateOutput .= " to " . date("g:i a", strtotime($repeat["end_time"])) . ". Ending on ";
							$dateOutput .= date( "F j, Y", strtotime($repeat["end_date"]));
							break;
					}
					$dateOutput .= "<br/>";
				}
				break;
				// ongoing
			case 2:
				if ($event["Repeat"][0]["end_date"] == $event["Repeat"][0]["start_date"]) {
					$dateOutput .= "All day on " . date("F j, Y", strtotime($event["Repeat"][0]["start_date"]));
				} else {
					$dateOutput .= "Ongoing from " . date("F j, Y", strtotime($event["Repeat"][0]["start_date"]));
					$dateOutput .=  " to " . date( "F j, Y", strtotime($event["Repeat"][0]["end_date"]));
				}
				break;
				// deadline
			case 3:
				$dateOutput .= "Deadline at " . date("F j, Y", strtotime($event["Repeat"][0]["start_date"]));
				$dateOutput .=  " " . date("g:i a", strtotime($event["Repeat"][0]["start_time"]));
				break;
		}
		
		return $dateOutput;
    }
    
    function eraseOldPictures($event) {
    	// Erase the picture if the event is done.
		$endDate = date("Y-m-d");
		// Get the latest end date  
		foreach($event["Repeat"] as $repeat) {
			if ($endDate < $repeat["end_date"]) { $endDate = $repeat["end_date"]; } 
		}
		if ($endDate >= date("Y-m-d", strtotime( date("Y-m-d") . " + 2 months"))) {
			if ($event["Event"]["flyer"] != "") {
				$imageLocation = getcwd() . "/img/" . $event["Event"]["flyer"];
				if (fopen($imageLocation, "r")) { unlink($imageLocation); }
			}			
		}
    }
    
    function getTopParentName($id) {
    	$topParent = $this->CalendarsEvent->find("first", array(
			"conditions" => array("CalendarsEvent.event_id" => $id),
			"fields" => array("calendar_id"),
			"order" => array("id ASC")
		));
		
		$calendarName = $this->Calendar->find("first", 
		array( "recursive" => -1, "fields" => array("title"),  
		"conditions" => array("id" => $topParent["CalendarsEvent"]["calendar_id"]) ));
		
		$calendarName = $calendarName["Calendar"]["title"];
		
		return $calendarName;
    }
    
    
    function searchPaginationParams($passedParams) {
   		$searchParams = explode(" ", trim($passedParams['named']['search']));
		$search = $start = $end = "";
		
		foreach ($searchParams as $value) {
			if (substr($value, 0,2) == "s:" ) { $start = substr($value, 2, strlen($value) - 2); } 
			else if (substr($value, 0,2) == "e:") { $end = substr($value, 2, strlen($value) - 2); } 
			else { $search .= "$value "; }
		}
		$search = trim($search);
		
		$sort = ', created DESC';
		if (!empty($passedParams['named']['sort'])) {
			$sort = explode('.', $passedParams['named']['sort']);
			$sort = ', ' . $sort[1] . ' DESC';
		}
		$params = array();
		//$params["Event"]["recursive"] = -1;
		$params['sphinx']['matchMode'] = SPH_MATCH_ALL;
		$params['sphinx']['sortMode'] = array(SPH_SORT_EXTENDED => '@relevance DESC' . $sort);
		$params['search'] = $search;
		
		if($start != "" && $end != "") {
			$events = $this->find("all", $params);				
			$ids = array();				
			foreach($events as $value) {
				foreach($value["CalendarsEvent"] as $ce) {
					if ($ce["start_date_time"] <= $end && $ce["end_date_time"] >= $start) {
						$ids[$ce["event_id"]] = $ce["event_id"];
					}
				}
			}
			$params = array();
			$params["conditions"]["OR"][] = array("Event.id" => 0);
			foreach($ids as $value) {
				$params["conditions"]["OR"][] = array("Event.id" => $value);
			}
		}
		return $params;
    }
    
    
    function getRssEvents($calendarId, $passedParams) {
    	$isRss = ($passedParams["action"] == "rss") ? true : false;
    	
    	$featured = 0;
		if (isset($passedParams['url']['featured']) && $passedParams['url']['featured'] == "y" ) {
			$featured = 1;
		}
		
		$cat = null;
		if (isset($passedParams['url']['cat'])) {
			if (is_numeric($passedParams['url']['cat'])) {
				$cat = $passedParams['url']['cat'];
			} else {
				switch(strtolower($passedParams['url']['cat'])) {
					case "academics":
						$cat = 1;
						break;
					case "alumni_and_community":
						$cat = 2;
						break;
					case "arts_and_entertainment":
						$cat = 3;
						break;
					case "athletic_events":
						$cat = 4;
						break;
					case "lectures_and_conferences":
						$cat = 5;
						break;
					case "student_life":
						$cat = 6;
						break;
					case "faculty_and_staff_life":
						$cat = 7;
						break;
				}
			}
		}

		$year = isset($passedParams['url']['year']) ? $passedParams['url']['year'] : null;
		$month = isset($passedParams['url']['month']) ? $passedParams['url']['month'] : null;
		
		$events = null;
		if (isset($passedParams["named"]["id"])) {
			$params = array();
			$params["conditions"] = array( "Event.id" => $passedParams["named"]["id"] );
			$params["limit"] = 1;
			$params["recursive"] = -1;
			$params["fields"] = array("Event.*", "CalendarsEvent.start_date_time", "CalendarsEvent.end_date_time");
			$params["joins"] = array(
				array(
					'table' => 'calendars_events',
					'alias' => 'CalendarsEvent',
					'type' => 'LEFT',
					'conditions' => array('Event.id = CalendarsEvent.event_id')
				)
			);
			$events = $this->find("all", $params);
		} else if (isset($passedParams['url']['start']) && isset($passedParams['url']['end'])) {
			$startDate = $passedParams['url']['start']. " 00:00:00";
			$endDate = $passedParams['url']['end'] . " 23:59:59";
			$events = $this->getEvents($calendarId, $startDate, $endDate, $cat, 100, $featured, $isRss);			
		} else if ($month == null && $year == null ) {
			$events = $this->getEvents($calendarId, date("Y-m-d"), null, $cat, 100, $featured, $isRss);			
		} else {
			if ($year == null) {
				$year = date("Y");
				$startDate = "$year-$month-01 00:00:00";
				$endDate = "$year-$month-31 23:59:59";
			} else if ($month == null) {
				$startDate = "$year-01-01 00:00:00";
				$endDate = "$year-12-31 23:59:59";
			} else {
				$startDate = "$year-$month-01 00:00:00";
				$endDate = "$year-$month-31 23:59:59";
			}
			$events = $this->getEvents($calendarId, $startDate, $endDate, $cat, 100, $featured, $isRss);
		}

		if (isset($passedParams['url']['search'])) {
			$temp = array();
			$search = strtolower($passedParams['url']['search']);
			foreach($events as $event) {
				$pos = strpos(strtolower($event["Event"]["title"]), $search);
				if ($pos !== false) {
					$temp[] = $event;
				}
			}
			$events = $temp;
		}

		if (isset($passedParams['url']['tag'])) {
			$tags = explode("," , $passedParams['url']['tag']);
			$params = array();
			$params["conditions"] = array();
			foreach($tags as $tag) {
				$params["conditions"]["AND"][]["title"] = $tag;
			}
			$tags = $this->Tag->find("all", $params);
			$temp = array();
			foreach($tags as $tag) {
				foreach($tag["Event"] as $tagEvent) {
					foreach ($events as $event) {
						if ($tagEvent["id"] == $event["Event"]["id"]) {
							$temp[] = $event;
						}
					}
				}
			}
			$events = $temp;
		}
		return $events;
    }
    
    function appendRepeats($events) {
    	$params = array();
		
		$params["conditions"]["OR"][] = array("Repeat.event_id" => 0);
		foreach($events as $event) {
			$params["conditions"]["OR"][] = array("Repeat.event_id" => $event["Event"]["id"]);
		}
		$params["recursive"] = -1;
		
		$repeats = $this->Repeat->find("all", $params );

		foreach($events as $key => $event) {
			foreach($repeats as $repeat) {
				if ($repeat["Repeat"]["event_id"] == $event["Event"]["id"]) {
					$events[$key]["Repeat"][] = $repeat["Repeat"];
				}
			}
		}
		return $events;
    }
    
    function appendCalendarsEvents($events) {
    	$params = array();
		$params["conditions"]["CalendarsEvent.active"] = 0;
		$params["conditions"]["OR"][] = array("CalendarsEvent.event_id" => 0);
		foreach($events as $event) {
			$params["conditions"]["OR"][] = array("CalendarsEvent.event_id" => $event["Event"]["id"]);
		}
		$params["fields"] = array("Calendar.title", "CalendarsEvent.*");
		$params["recursive"] = -1;
		$params["joins"] = array(
		array(
				'table' => 'calendars',
				'alias' => 'Calendar',
				'type' => 'LEFT',
				'conditions' => array('Calendar.id = CalendarsEvent.calendar_id')				
		)
		);
		$params['order'] = 'calendar_id';

		$calendarsEvents = $this->CalendarsEvent->find("all", $params );

		foreach($events as $key => $event) {
			foreach($calendarsEvents as $calendarsEvent) {
				if ($calendarsEvent["CalendarsEvent"]["event_id"] == $event["Event"]["id"]) {
					$tmp = $calendarsEvent["CalendarsEvent"];
					$tmp["Calendar_title"] = $calendarsEvent["Calendar"]["title"];
					$events[$key]["CalendarsEvent"][] = $tmp;
				}
			}
		}
		return $events;
    }
    
    function getEventPaginationParams($events) {
    	$params = array();
		$params["fields"] = array("Event.*", "User.id", "User.username");
		$params["recursive"] = -1;
		
		$params["joins"] = array(
			array(
				'table' => 'users',
				'alias' => 'User',
				'type' => 'LEFT',
				'conditions' => array('Event.user_id = User.id')
			)
		);
		$params["conditions"]["OR"][] = array("Event.id" => -1);
		foreach($events as $eventId) {
			$params["conditions"]["OR"][] = array("Event.id" => $eventId["Event"]["id"]);
		}
    	return $params;
    }
    
    
    function getIndexPaginationParams($calendarId, $params, $fbsort=false) {
    	$conditions = array();
		$conditions["CalendarsEvent.start_date_time <="] = date("Y-m-d", strtotime("today +7 days")) . " 23:59:59";
		$conditions["CalendarsEvent.end_date_time >="] = date("Y-m-d") . " 00:00:00";
		$conditions["CalendarsEvent.calendar_id"] = $calendarId;
		$conditions["CalendarsEvent.active"] = 1;
		foreach($params as $key => $value) {
			if ($key != "category") { $noCategory[$key] = $value; }
			else if ($value != 0) { $conditions["Event.category_id"] = $value; }
			if ($key != "start" && $key != "end" ) { $noDate[$key] = $value; }
			else {
				if ($key == "end") {$conditions["CalendarsEvent.start_date_time <="] = $value . " 23:59:59"; }
				else {$conditions["CalendarsEvent.end_date_time >="] = $value . " 00:00:00"; }
			}
			if ($key != "type") { $noType[$key] = $value; }
			else {
				if ($value == 1) {
					$conditions["Event.type"][] = 1;
					$conditions["Event.type"][] = 2;
				} else {
					$conditions["Event.type"] = $value;
				}
					
			}
			if ($key != "view") { $noView[$key] = $value; }
			else {$viewType = $value;}
		}
		
		//default sorting for events
		$sort_order = array('CalendarsEvent.start_date_time' => 'ASC');
		if (isset($params["fblike"])){
			$sort_order = array('Event.fblike' => 'DESC');
		}
		
		
		$params = array(
			'recursive' => 0,
			'order' => $sort_order,
			'conditions' => $conditions,
			'fields' => array('Event.*', 'CalendarsEvent.*'),
			'joins' => array(
				array(
					'table' => 'calendars_events',
					'alias' => 'CalendarsEvent',
					'type' => 'LEFT',
					'conditions' => array('Event.id = CalendarsEvent.event_id')
				)
			)
		);
		
		
		return $params;
    }
    
    function denyPending($pendingData) {
    	foreach($pendingData as $key => $value) {
			if ($value == 1) {
				$this->CalendarsEvent->id = $key;
				$tmp = $this->CalendarsEvent->read();
				$calendarId = $tmp["CalendarsEvent"]["calendar_id"];
				$eventId = $tmp["CalendarsEvent"]["event_id"];
				$cES = $this->CalendarsEvent->find("all", array(
					"conditions" => array(
						"event_id" => $eventId,
						"calendar_id" => $calendarId
					)
				));
				foreach ($cES as $value) {
					$this->CalendarsEvent->delete($value["CalendarsEvent"]["id"], false);
				}
			}
		}
    }
    
	function getEventIdsFromCalendars($calendars) {
		
		$params = array();
		$params["fields"] = array("DISTINCT Event.id");
		$params["recursive"] = -1;
		$params["conditions"]["CalendarsEvent.active"] = 0;

		$params["joins"] = array(
			array(
					'table' => 'calendars_events',
					'alias' => 'CalendarsEvent',
					'type' => 'LEFT',
					'conditions' => array('Event.id = CalendarsEvent.event_id')
			)
		);
		foreach($calendars as $value) {
			$params["conditions"]["OR"][] = array("calendar_id" => $value["Calendar"]["id"]);
		}
		
		$eventIds = $this->find("all", $params);
		return $eventIds;
	}
    
    function approvePending($pendingData) {
    	foreach($pendingData as $key => $value) {
			if ($value == 1) {						
				$this->CalendarsEvent->id = $key;
				$tmp = $this->CalendarsEvent->read();
				$calendarId = $tmp["CalendarsEvent"]["calendar_id"];
				$eventId = $tmp["CalendarsEvent"]["event_id"];
				$cES = $this->CalendarsEvent->find("all", array(
					"conditions" => array(
						"event_id" => $eventId,
						"calendar_id" => $calendarId
					)
				));
				foreach ($cES as $value) {
					$this->CalendarsEvent->create();
					$this->CalendarsEvent->id = $value["CalendarsEvent"]["id"];
					$this->CalendarsEvent->set('active', 1);
					$this->CalendarsEvent->save();
				}						
			}
		}
    }
    
    function getMyEventsParams($userId) {
    	
    	$events = $this->getMyEvents($userId);
    	
   		$params = array(
			"recursive" => -1,			
			"fields" => array("id", "title", "description", "location", "contact", "type", "created", "modified")			
		);
		
		foreach ($events as $id) {
			$params["conditions"]["OR"][] = array("Event.id" => $id["Event"]["id"]);
		}
		return $params;
    }
    
    function getMyEvents($userId) {
    	$params = array(
			"recursive" => -1,
			"joins" => array(
				array(
						'table' => 'calendars_events',
						'alias' => 'CalendarsEvent',
						'type' => 'LEFT',
						'conditions' => array('Event.id = CalendarsEvent.event_id')
				)
			),
			"conditions" => array( 
				"Event.user_id" => $userId,
				"CalendarsEvent.end_date_time >=" => date("Y-m-d") . " 00:00:00",
			),
			"fields" => array("DISTINCT Event.id"),
		);				
		$events = $this->find("all", $params);
		return $events;
    }
    
    function getFeaturedEventsParams($calendarId, $filter = "", $endDate = "", $startDate = "") {
   		$params = array();
		$params["fields"] = array("Event.*", "Calendar.title", "CalendarsEvent.id", "CalendarsEvent.featured", "CalendarsEvent.start_date_time", "CalendarsEvent.end_date_time");
		$params["recursive"] = -1;
		$params["conditions"]["Calendar.id"] = $calendarId;
		$params["conditions"]["CalendarsEvent.end_date_time >="] = date("Y-m-d");
		$params["conditions"]["flyer NOT"] = "NULL";
		$params["conditions"]["flyer !="] = "";
		if ($filter != "") {
			$params["conditions"]["Event.title LIKE"] = $filter . "%";
		}
		$params["conditions"]["CalendarsEvent.active"] = 1;
		if ($endDate != "" && $startDate != "") {
			$params["conditions"]["CalendarsEvent.end_date_time <="] = date("Y-m-d", strtotime($endDate));
			$params["conditions"]["CalendarsEvent.start_date_time >="] = date("Y-m-d", strtotime($startDate));			
		}
		
		$params["joins"] = array(
			array(
				'table' => 'calendars_events',
				'alias' => 'CalendarsEvent',
				'type' => 'LEFT',
				'conditions' => array('Event.id = CalendarsEvent.event_id')
			),
			array(
				'table' => 'calendars',
				'alias' => 'Calendar',
				'type' => 'LEFT',
				'conditions' => array('Calendar.id = CalendarsEvent.calendar_id')
			)
		);
		return $params;
    }
    
    function massInput($upload) {
    	if ($upload["Event"]["Events"]["error"] == 0) {
			$eventArray = array();
			$row = 0;
			$errors = "";
			$userId = $_SESSION["Auth"]["User"]["id"];
			if (($handle = fopen($upload["Event"]["Events"]["tmp_name"], "r")) !== FALSE) {
				while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
					if ($row != 0) {
						foreach($data as $key => $value) {
							$eventArray["Tags"][$key]["Tag"] = "";
							switch($key) {
								// title
								case 0: $eventArray["Event"][$row]["title"] = $data[$key]; break;
								// description
								case 1: $eventArray["Event"][$row]["description"] = $data[$key]; break;
								// location
								case 2: $eventArray["Event"][$row]["location"] = $data[$key]; break;
								// contact
								case 3: $eventArray["Event"][$row]["contact"] = $data[$key]; break;
								// email
								case 4: $eventArray["Event"][$row]["email"] = $data[$key]; break;
								// phone
								case 5: $eventArray["Event"][$row]["phone"] = $data[$key]; break;
								// url
								case 6: $eventArray["Event"][$row]["url"] = $data[$key]; break;
								// type
								case 7:
									switch(strtolower($data[$key])) {
										case "normal": 	$eventArray["Event"][$row]["type"] = 1; break;
										case "ongoing": $eventArray["Event"][$row]["type"] = 2; break;
										case "deadline":$eventArray["Event"][$row]["type"] = 3; break;
										default: $errors .= "I do not recognize \"" . $data[$key] . "\" for Type on line " . ($row +1) . "<br/>"; 
									}
									break;
									// category
								case 8:
									switch(strtolower($data[$key])) {
										case "academic": 	$eventArray["Event"][$row]["category_id"] = 1; break;
										case "alumni": 		$eventArray["Event"][$row]["category_id"] = 2; break;
										case "art": 		$eventArray["Event"][$row]["category_id"] = 3; break;
										case "athletic": 	$eventArray["Event"][$row]["category_id"] = 4; break;
										case "lecture":		$eventArray["Event"][$row]["category_id"] = 5; break;
										case "student": 	$eventArray["Event"][$row]["category_id"] = 6; break;
										case "faculty": 	$eventArray["Event"][$row]["category_id"] = 7; break;
										default: $errors .= "I do not recognize \"" . $data[$key] . "\" for Category on line " . ($row +1) . "<br/>";
									}
									break;
									// start_date_time
								case 9: $eventArray["CalendarsEvent"][$row]["start_date_time"] = date("Y-m-d G:i:s", strtotime($data[$key])); break;
								// end_date_time
								case 10: $eventArray["CalendarsEvent"][$row]["end_date_time"] = date("Y-m-d G:i:s", strtotime($data[$key])); break;
								// tags
								case 11: $eventArray["Tags"][$row]["Tag"] = $data[$key]; break;
							}
							$eventArray["Event"][$row]["user_id"] = $userId;
						}
					}
					$row++;
				}
				fclose($handle);
			}
			
			if( !$this->saveAll($eventArray["Event"], array("validate" => "only")) || $errors != "" ) {
				$errorMessage = "Sorry, one or more of the fields you provided had the following errors:<br>";
				$errorMessage .= $errors;
				$errors = $this->invalidFields();
				foreach ($errors as $row => $array) {
					foreach ($array as $key => $value) {
						$errorMessage .= "On row ". ($row+1). ": $value <br/>";
					}
				}
				return array('valid' => false, 'message' => $errorMessage);				
			} else {
				$calendars = $this->calendarsToSave($upload["Calendar"]["Calendar"]);
				foreach($eventArray["Event"] as $key => $value) {
					$event = array();
					$event["Event"] = $value;
					$this->create();
					$eventSaved = parent::save($event);
					$eventId = $this->id;
	
					if ($eventSaved) {
						$repeat = array();
						$repeat["event_id"] = $eventId;
						$startMark = strtotime($eventArray["CalendarsEvent"][$key]["start_date_time"]);
						$endMark = strtotime($eventArray["CalendarsEvent"][$key]["end_date_time"]);
						switch($event["Event"]["type"]) {
							// normal
							case 1:
								$repeat["type"] = 1;
								$repeat["end_date"] = $repeat["start_date"] = date("Y-m-d", $startMark);
								$repeat["start_time"] = date("H:i:s", $startMark);
								$repeat["end_time"] = date("H:i:s", $endMark);
								break;
								// ongoing
							case 2:
								$repeat["type"] = 5;
								$repeat["start_date"] = date("Y-m-d", $startMark);
								$repeat["end_date"] = date("Y-m-d", $endMark);
								$repeat["start_time"] = $repeat["end_time"] = "00:00:00";
								break;
								// deadline
							case 3:
								$repeat["type"] = 6;
								$repeat["start_date"] = $repeat["end_date"] = date("Y-m-d", $startMark);
								$repeat["start_time"] = $repeat["end_time"] = date("H:i:s", $startMark);
								break;
						}
						$repeat["variable"] = 0;
						$repeats = array();
						$repeats[] = $repeat;
	
						$events_tags = $this->eventTagsToSave($eventId, $eventArray["Tags"][$key]["Tag"]);
						$calendars_events = $this->calendarEventsToSave($eventId, $event["Event"]["type"], $calendars, $repeats);
						$validEventsTags = $this->EventsTag->saveAll($events_tags, array("validate" => "only"));
						$validRepeats = $this->Repeat->saveAll($repeats, array("validate" => "only"));
						$validCalendarsEvents = $this->CalendarsEvent->saveAll($calendars_events, array("validate" => "only"));
					} 
					if ($validEventsTags && $validRepeats && $validCalendarsEvents && $eventSaved) {
						$eventsTagsSaved = $this->EventsTag->saveAll($events_tags);
						$repeatsSaved = $this->Repeat->saveAll($repeats);
						$calendarsEventsSaved = $this->CalendarsEvent->saveAll($calendars_events);
					} else {
						$this->Event->delete($eventId, true);
						return array('valid' => false, 'message' => "The Events could not be saved. Please, try again.");						$this->Session->setFlash(__('', true));
					}
				}
				return array('valid' => true, 'message' => "The Events have been saved");
			}
	
		}
    }
    
    function formFormat($id) {
    	$this->id = $id;
		$this->recursive = 1;
		$data = $this->read();
		
		$return["Event"] = $data["Event"];		
		$repeats = $data["Repeat"];
		
		$return["Event"]["repeats"] = count($repeats);
		
		$startTimeStamp = strtotime("{$data["Repeat"][0]["start_date"]} {$data["Repeat"][0]["start_time"]}");
		$endTimeStamp = strtotime("{$data["Repeat"][0]["end_date"]} {$data["Repeat"][0]["end_time"]}");
		
		$return["Event"]["start"]["year"] = date("Y", $startTimeStamp);
		$return["Event"]["start"]["month"] = date("m", $startTimeStamp);
		$return["Event"]["start"]["day"] = date("d", $startTimeStamp);
		
		$return["Event"]["start"]["hour"] = date("h", $startTimeStamp);
		$return["Event"]["start"]["minute"] = date("i", $startTimeStamp);
		$return["Event"]["start"]["meridian"] = date("a", $startTimeStamp);
		
		$return["Event"]["end"]["year"] = date("Y", $endTimeStamp);
		$return["Event"]["end"]["month"] = date("m", $endTimeStamp);
		$return["Event"]["end"]["day"] = date("d", $endTimeStamp);
		
		$return["Event"]["end"]["hour"] = date("h", $endTimeStamp);
		$return["Event"]["end"]["minute"] = date("i", $endTimeStamp);
		$return["Event"]["end"]["meridian"] = date("a", $endTimeStamp);
		
		foreach ($repeats as $key => $value) {
			$return["Event"]["dateParameter_1_" . ($key + 1)] = $value["variable"];
			$return["Event"]["EventRepeatsType".($key + 1)] = $value["type"] - 1;
			
			$gd_a = getdate( strtotime($value["start_date"]) );
			$gd_b = getdate( strtotime($value["end_date"]) );
			
			$a_new = mktime( 12, 0, 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year'] );
			$b_new = mktime( 12, 0, 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year'] );
			
			$days = round( abs( $a_new - $b_new ) / 86400 );
			
			switch ($value["type"]) {
				// once
				case 1:
					$return["Event"]["dateParameter_2_".($key + 1)] = 0;
					break;
				// daily
				case 2:
					$return["Event"]["dateParameter_2_".($key + 1)] = $days;
					break;
				// weekly
				case 3:
					$weekArray = explode( ".",  $value["variable"]);

					$return["Event"]["Monday"][$key+1] = $weekArray[0];
					$return["Event"]["Tuesday"][$key+1] = $weekArray[1];
					$return["Event"]["Wednesday"][$key+1] = $weekArray[2];
					$return["Event"]["Thursday"][$key+1] = $weekArray[3];
					$return["Event"]["Friday"][$key+1] = $weekArray[4];
					$return["Event"]["Saturday"][$key+1] = $weekArray[5];
					$return["Event"]["Sunday"][$key+1] = $weekArray[6];
					
					$return["Event"]["dateParameter_2_".($key + 1)] = round($days / 7);
					break;
				// monthly
				case 4:
					$return["Event"]["dateParameter_2_".($key + 1)] = round($days / 30);
					break;
				// yearly
				case 5:
					$return["Event"]["dateParameter_2_".($key + 1)] = round($days / 365);
					break;
			}
		}
		
		// currently tagged calendars with this event

		$calendarIds = $this->CalendarsEvent->find("all", array(
			"fields" => array("DISTINCT calendar_id"),
			"conditions" => array("event_id" => $id),
			"recursive" => 0
		));
		foreach($calendarIds as $value) {
			$return["Calendar"]["Calendar"][] = $value["CalendarsEvent"]["calendar_id"];
		}
		
		$tagsArray = $this->EventsTag->find('all', array(
			'conditions' => array('EventsTag.event_id' => $id),
			'fields' => array('Tag.title'),
			"recursive" => -1,
			'joins' => array(
				array(
					'table' => 'tags',
					'alias' => 'Tag',
					'type' => 'LEFT',
					'conditions' => array('EventsTag.tag_id = Tag.id')
				)
			)
		));
		$tags = "";
		$first = true;
		foreach ($tagsArray as $key => $tag) {
			if ($first == false) { $tags .= ", ";}
			$first = false;
			$tags .= $tag["Tag"]["title"];
		}
		$return["Tag"]["Tag"] = $tags;
		return $return;
    }
    
    function updateIcalEvents() {
    	
    	// erase old ical events
    	$icalEventsId = $this->find("all", array("recursive" => -1, "fields" => array("id"), "conditions" => array("is_ical" => 1)));    	
    	foreach ($icalEventsId as $id) {
    		$this->del($id["Event"]["id"]);
    		$this->CalendarsEvent->deleteAll(array("event_id" => $id["Event"]["id"]));
    		$this->Repeat->deleteAll(array("event_id" => $id["Event"]["id"]));
    	}
    	// Add the updated ones in.
    	$calendarIcals = $this->Calendar->CalendarsIcal->find("all", array("recursive" => -1));
    	
    	foreach ($calendarIcals as $icals) {
    		$calendarId = $icals["CalendarsIcal"]["calendar_id"];
    		
    		$icalUrl = $icals["CalendarsIcal"]["url"];
    		$icalOutput = $this->iCalDecoder($icalUrl, $calendarId);
    	}
    		
    }
   
	function iCalDecoder($url, $calendarId) {
		App::import('Vendor', 'iCalcreator', array("file" => "iCalParser/iCalcreator.php"));
		//require_once("../vendors/iCalParser/iCalcreator.php");
		$config = array( 'unique_id' => 'kigkonsult.se' );
		// set Your unique id, required if any component UID is missing
		$v = new vcalendar( $config );
		$v->setConfig( 'url', $url );
		// iCalcreator also support remote files
		$v->parse();
		$v->sort();
		//$v->returnCalendar();
		
		$output = array();
		while( $vevent = $v->getComponent( 'vevent' )) {
			
			$title = $vevent->getProperty('SUMMARY');
			if ($title == "") { continue; }
			$description = $vevent->getProperty('DESCRIPTION');
			if ($description == "") $description = $title;
			$location = $vevent->getProperty('LOCATION');
			if ($location == "") $location = $title;
			$contact = $vevent->getProperty('ORGANIZER');
			if ($contact == "") $contact = $title;				
			
			$event = array(
    			"Event" => array(
    				"title" => $title,
    				"description" => $description,
    				"location" => $location,
	    			"contact" => $contact,
	    			"type" => 1,
	    			"user_id" => 1,
	    			"category_id" => 1,
					"is_ical" => 1	
    			)
    		);
    		
    		$this->create();
    		parent::save($event);
    		
    		$eventId = $this->id;
    		$sdt = $vevent->getProperty('DTSTART');
    		$edt = $vevent->getProperty('DTEND');   

    		if (!isset($edt["hour"])) { $edt["hour"] = "00"; }
			if (!isset($edt["min"])) { $edt["min"] = "00"; }
			if (!isset($edt["sec"])) { $edt["sec"] = "00"; }
			
			if (!isset($sdt["hour"])) { $sdt["hour"] = "00"; }
			if (!isset($sdt["min"])) { $sdt["min"] = "00"; }
			if (!isset($sdt["sec"])) { $sdt["sec"] = "00"; }
    		$calendarsEvent = array(
    			"CalendarsEvent" => array(
    				"start_date_time" => "{$sdt["year"]}-{$sdt["month"]}-{$sdt["day"]} {$sdt["hour"]}:{$sdt["min"]}:{$sdt["sec"]}",
    				"end_date_time" => "{$edt["year"]}-{$edt["month"]}-{$edt["day"]} {$edt["hour"]}:{$edt["min"]}:{$edt["sec"]}",
    				"calendar_id" => $calendarId,
    				"event_id" => $eventId,
    				"featured" => 0,
    				"active" => 1
    			)
    		);  
    		$this->CalendarsEvent->create();
    		$this->CalendarsEvent->save($calendarsEvent);  		
    		
    		$repeat = array(
    			"Repeat" => array(
    				"event_id" => $eventId,
    				"type" => 1,
    				"variable" => 0,
    				"start_date" => "{$sdt["year"]}-{$sdt["month"]}-{$sdt["day"]}",
    				"end_date" => "{$edt["year"]}-{$edt["month"]}-{$edt["day"]}",
    				"start_time" => "{$sdt["hour"]}:{$sdt["min"]}:{$sdt["sec"]}",
    				"end_time" => "{$edt["hour"]}:{$edt["min"]}:{$edt["sec"]}"
    			)
    		); 
    		$this->Repeat->create();
    		$this->Repeat->save($repeat); 
		}
		
	}
    
}
?>
