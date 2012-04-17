var output = '<ul class="embedUl" ><?php
foreach($events as  $value) {
	echo '<li class="embedLi">';
	
	if (!empty($value['Event']['thumbnail'])) {
		echo $html->image( $html->url( "/img/thumbnails/" . $value['Event']['thumbnail'] , true), array("class" => "embedImage") );
	}
	echo '<span class="embedText">';
	
	echo "<a ";
	echo "href=\"" ;
	
	if ($this->params["named"]["link"] == "y") {
		if ($value["Event"]["url"] == "") {
			echo $html->url(array("controller" => "events", "action" => "view", $value["Event"]["id"]), true) . "\"";
		} else {
			echo $value["Event"]["url"] . '"';
		}
	} else {
		echo $html->url(array("controller" => "events", "action" => "view", $value["Event"]["id"]), true) . "\"";
	}
	
	if ($this->params["named"]["target"] != "y") {
		echo ' target="blank" ';
	}
	echo ' class="embedLink" >';
	
	echo '<span class="embedTitle">' . addslashes($value["Event"]["title"]) . '</span>' ;
	
	echo "</a>";
	
	if ($this->params["named"]["date"] == "y") {
		echo ' <span class="embedDate">';
		echo date("m/d/Y g:i a", strtotime($value['CalendarsEvent']["start_date_time"]));
		echo "</span>";
	}
	
	if ($this->params["named"]["desc"] != 0) {
		echo ' <span class="embedDesc">';
		if ($this->params["named"]["desc"] == 1) {
			echo trim(addslashes(str_replace("\r\n", "<br/>", $value["Event"]["description"] )  ));
		} else {
			echo trim(addslashes(str_replace("\r\n", "<br/>", substr( $value["Event"]["description"] , 0, $this->params["named"]["desc"]))));
		}
		echo ' </span>';
	}
	
	echo '</span>';
	echo "</li>";
} 
?></ul>';

document.write(output);