<?php
class CalendarsIcal extends AppModel {

	var $name = 'CalendarsIcal';
	var $validate = array(
		'id' => array('numeric'),
		'calendar_id' => array('notempty'),		
		'url' => array(
			'rule' => array('urlRule'),
			'message' => 'This needs to be a url. Ex: "http://www.google.com"'
		 )
	);
	
	var $belongsTo = array(
		'Calendar' => array(
			'className' => 'Calendar',
			'foreignKey' => 'calendar_id'
		)
	);

	function urlRule( $field=array()) {
    	if ($field["url"] == "") {
    		return true;
    	} 
    	return preg_match("((https?|ftp|gopher|telnet|file|notes|ms-help):((//)|(\\\\))+[\w\d:#@%/;$()~_?\+-=\\\.&]*)", $field["url"]);
    }
    
    

}
?>