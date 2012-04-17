<?php

class ChooserHelper extends AppHelper {
	//-----------------------------------------------------------------------
	
	//-----------------------------------------------------------------------
	function selectOutput($calendarsList, $showAll = false) {
		$calendarOptions = array();
		$highParent = $calendarsList[0]["Calendar"]["parent_id"];
		foreach($calendarsList as $value) {
			if ($value["Calendar"]["parent_id"] < $highParent) {
				$highParent = $value["Calendar"]["parent_id"];
			}
		}
		
		$this->_listChildren($highParent, $calendarsList, $calendarOptions, "", $showAll);
		
		$calendarName = isset($this->params["named"]["calendar"]) ? $this->params["named"]["calendar"] : "main";
		
		$output = "<select id='calendarChooser' size='20'>
		";
		foreach($calendarOptions as $key => $calendar) {
			$output .= "<option value='$key'";
			if ($calendarName == $key) {
				$output .= " selected='selected' ";
			}
			$output .= ">";
			$output .= substr(str_replace(" & ", " &amp; ", $calendar), 0, 25);
			if (strlen($calendar) > 25) {
				$output .= "...";
			}
			$output .= "</option>
			";
		}
		$output .= "</select>";
		
		return $output;
	}
	//-----------------------------------------------------------------------
	function arrayOutput($calendarsList, $showAll = false) {
		$calendarOptions = array();		
		$highParent = $calendarsList[0]["Calendar"]["parent_id"];
		foreach($calendarsList as $value) {
			if ($value["Calendar"]["parent_id"] < $highParent) {
				$highParent = $value["Calendar"]["parent_id"];
			}
		}
		$this->_listChildrenWithId($highParent, $calendarsList, $calendarOptions, "", $showAll);
		return $calendarOptions;
	}
	//-----------------------------------------------------------------------
	function _listChildrenWithId($parentId, &$calendarList, &$output, $pre = "", $showAll = false) {
		for($x = 0; $x < count($calendarList); $x++) {
			if (
				($calendarList[$x]["Calendar"]["parent_id"] == $parentId) &&
				(!($calendarList[$x]["Calendar"]["private"]) || $showAll)
				) {
				$output[$calendarList[$x]["Calendar"]["id"]] = ( $pre . $calendarList[$x]["Calendar"]["title"]);
				$this->_listChildrenWithId($calendarList[$x]["Calendar"]["id"], $calendarList, $output, ($pre . "- "), $showAll);
			}
		}
	}
	
	//-----------------------------------------------------------------------
	function _listChildren($parentId, &$calendarList, &$output, $pre = "", $showAll = false) {
		for($x = 0; $x < count($calendarList); $x++) {
			if (
				($calendarList[$x]["Calendar"]["parent_id"] == $parentId) &&
				(!($calendarList[$x]["Calendar"]["private"]) || $showAll)
				) {
				$output[$calendarList[$x]["Calendar"]["url"]] = ( $pre . $calendarList[$x]["Calendar"]["title"]);
				$this->_listChildren($calendarList[$x]["Calendar"]["id"], $calendarList, $output, ($pre . "- "), $showAll);
			}
		}
	}
	//-----------------------------------------------------------------------
} 

?>