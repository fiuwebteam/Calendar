<?php
header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=output.csv");
?>Title, Description, Location, Contact, Email, Phone, Url, Type, Category, Start, End
<?php foreach($events as $event) {

	switch($event["Event"]["type"]) {
		case 2: $type = "Ongoing Event"; break;
		case 3:	$type = "Deadline"; break;
		default: $type = "Normal Event"; break; 
	} 
	switch($event["Event"]["category_id"]) {
		case 1: $category = "Academic";  break;
		case 2: $category = "Alumni and Community"; break;
		case 3: $category = "Arts and Entertainment"; break;
		case 4: $category = "Athelic Events"; break;
		case 5: $category = "Lectures and Conferences"; break;
		case 6: $category = "Student Life"; break;
		case 7: $category = "Faculty and Staff Life"; break;
		default: $category = "Error"; break;
	}
	
	$tmp = "\"" . str_replace('"', "",$event["Event"]["title"]) . "\",\"" . 
	str_replace('"', "",$event["Event"]["description"]) . "\",\"" .
	str_replace('"', "",$event["Event"]["location"]) . "\",\"" .	 
	str_replace('"', "",$event["Event"]["contact"]) . "\",\"" . 
	str_replace('"', "",$event["Event"]["email"]) . "\",\"" .
	$event["Event"]["phone"] . "\",\"" .
	$event["Event"]["url"] . "\",\"" .
	$type . "\",\"" .
	$category . "\",\"" .
	$event["CalendarsEvent"]["start_date_time"] . "\",\"" . 
	$event["CalendarsEvent"]["end_date_time"] . "\"
";
	
	
	echo $tmp;
	 
} ?>