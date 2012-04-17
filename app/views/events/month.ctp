<!--  js for buttons and tabs interaction -->
<?php echo $this->element('interactiveJs'); ?>
	<!--featured event -->
	  <?php echo $this->element('featuredEvent'); ?>
	  
	  	<!--view: week|month|custom|most liked--> 
	<?php echo $this->element('viewButtons'); ?>	
<?php 
	$javascript->link('jquery.simplemodal', false);
	$calendar = $this->params["named"]["calendar"];
	$jsonLink = $html->url(array("action" => "json", "calendar:$calendar"));
	$eventLink = $html->url(array("action" => "view"));
	$js = 
<<<EOF
function showModal(date, modalDate) {
  $.getJSON(("$jsonLink?start="+date+"&end="+date), function(json) {
  		var output = "<div class='eventsModalWindowTitle'>"+modalDate+" Events</div><table class='eventsModalTable'>";
		output += "<thead>";
		output += "<tr>";
		output += "<th class='eventsThTime'>Time</th>";
		output += "<th class='eventsThTitle'>Event Name</th>";
		output += "<th class='eventsThLoc'>Location</th>";
		output += "</tr>";
		output += "</thead>";
		output += "<tbody>";		
		for(var x =0; x < json.length; x++) {
			output += "<tr class='tbodyTr'>";
			var time = json[x].CalendarsEvent.start_date_time;
			time = time.substring(11);
			var am_pm = "am";
			var hour = time.substring(0,2);
			if (hour > 12) {
				hour = hour - 12;
				am_pm = "pm";
			}

			var min = time.substring(3,5);
			
			var category = "";
			
			switch(json[x].Event.category_id) {
				case "1":
					category = "Academics";
					break;
				case "2":
					category = "Alumni & Community";
					break;
				case "3":
					category = "Arts & Entertainment";
					break;
				case "4":
					category = "Athletic Events";
					break;
				case "5":
					category = "Lectures & Conferences";
					break;
				case "6":
					category = "Student Life";
					break;
				case "7":
					category = "Faculty & Staff";
					break;
			}
			
 
			output += "<td class='eventsModalTime'>"+hour+":"+min+" "+am_pm+"</td>";
			output += "<td class='eventsModalTitle'><div class='eTitDiv'><a href='$eventLink/"+json[x].Event.id+"'>"+json[x].Event.title+"</a></div></td>";
			output += "<td class='eventsModalLocation'><div class='eLocDiv'>"+json[x].Event.location+"</div></td>";			
 			output += "</tr>";
		}
		output += "</tbody>";
		output += "</table>"		
		$.modal(output,{
			opacity:20,
			overlayCss: {backgroundColor:"#000"},
			overlayClose:true
		});	
		
		//for ie7-8 
		$('.eventsModalTable tr:nth-child(odd)').addClass('eventsModalTableOdd');
		$('.eventsModalTable tr:nth-child(even)').addClass('eventsModalTableEven');

}); 

} 

EOF;
	
	$javascript->codeBlock( $js, array('inline'=>false));
	
?>
<div id="container" class="container_16 clearfix vcalendar vMarginBottom_4" >
<div class="calBody grid_16 alpha omega">
<div id="monthGridContainer" class="prefix_2">
<div id="testmodal"> </div>

<!--<a href="#" onclick="showModal(042011-11-04);return false;">TEST</a>-->
<table id="monthGridTable">
	<tr class="month">
		<th colspan='7'>
		<span id='lastMonth'> <?= $html->link("<<", array("calendar:$calendar/year:$lastMonthYear/month:$lastMonthMonth")) ?> </span>
		<span id='currentMonth'> <?= date("F Y", strtotime($date)); $currMonth=date("n", strtotime($date)); 
		/* echo "cm: ".$currMonth;$curryear=date("Y", strtotime($date));*/?> </span>
		<span id='nextMonth'> <?= $html->link(">>", array("calendar:$calendar/year:$nextMonthYear/month:$nextMonthMonth")) ?> </span>
	</th></tr>
	<tr class="days">
		<th>Sunday</th>
		<th>Monday</th>
		<th>Tuesday</th>
		<th>Wednesday</th>
		<th>Thursday</th>
		<th>Friday</th>
		<th>Saturday</th>
	</tr>
	<?php 
	//Get dates for each month and print them out
	$thisMonth=0;
	
	$day = $start;
	
	
	
	//echo "lmy: ".$firstWeekdayOfTheMonth;
	//echo "day:". $day;
     
	
	for($y = 0; $y < 6; $y++) {
	 
		echo "<tr>";
		for($x = 0; $x < 7; $x++) {
		
		
	    	$thisMonth = date("n", strtotime($day));
			$isThisMonth = (($currMonth == $thisMonth) ? true : false);
	
			//echo $day; 
			$count = 0;$str='';
			
			foreach ($events as $value) { 
				//count nbr of events for each day and print them 
				if (
					$value["CalendarsEvent"]["start_date_time"] <= ($day . " 23:59:59") && 
					$value["CalendarsEvent"]["end_date_time"] >= ($day . " 00:00:00") ) 
				{
					$count++;
					//display event dots
					if ($isThisMonth){
					    if (($count % 6) == 0){$str.='<br />';} //newline of dots @ 6 events
						$str.="&#149";
						if ($count == 24){$str.='';} //max dots @ 24
					} else $str='';
					//$strarr[$count] = $str;		
				} 
		 
			}
			
	$modalDate=date("F jS", strtotime($day)); //top of modal window	
		
echo "<td><div class='dContain'><a href='#' onclick='showModal(\"$day\", \"$modalDate\");' class='aDay'><div class='eventDots'>".$str."</div><div class='dateNbr'>";
			
							//echo 'd: '.$day.'+'.$curryear;
			if (!$isThisMonth)
				echo '<div class="emptyCell">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>';
			else 
				echo date("d", strtotime($day));
	
			
			
		
			//echo "<div class='dots' style='float:left';>&#149;</div>";
			echo "</div>";
		 
			echo "</a></div></td>";
			$day = date("Y-m-d", strtotime("$day +1 day"));  //iterate days
		}
		echo "</tr>";
	}  
	?>
</table>
</div>
</div>
</div>