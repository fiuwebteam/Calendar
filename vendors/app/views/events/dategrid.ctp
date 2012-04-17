<?php
$normalTable = "";
$ongoingTable = "";
$deadlineTable = "";
foreach($events as $event) {
	if ($event["Event"]["type"] == 1) { 
		$normalTable .= "
		<tr class='normal_" . $event["Event"]["category_id"] . "_" . date("Y_m_d", strtotime($event["CalendarsEvent"]["start_date_time"])) . "'>
			<td>{$event["Event"]["title"]}</td>
			<td>{$event["Event"]["description"]}</td>
			<td>{$event["Event"]["location"]}</td>
			<td>{$event["Event"]["contact"]}</td>
			<td>{$event["Event"]["email"] }</td>
			<td>{$event["Event"]["phone"] }</td>
			<td>{$event["Event"]["url"] }</td>
			<td>" . date("Y-m-d", strtotime($event["CalendarsEvent"]["start_date_time"])) . "</td>
			<td>" . date("g:i a", strtotime($event["CalendarsEvent"]["start_date_time"])). "</td>
			<td>" . date("g:i a", strtotime($event["CalendarsEvent"]["end_date_time"])) . "</td>
		</tr>";
	} else if ($event["Event"]["type"] == 2) {
		$ongoingTable .= "
		<tr class='ongoing_{$event["Event"]["id"]}'>
			<td>{$event["Event"]["title"]}</td>
			<td>{$event["Event"]["description"]}</td>
			<td>{$event["Event"]["location"]}</td>
			<td>{$event["Event"]["contact"]}</td>
			<td>{$event["Event"]["email"]}</td>
			<td>{$event["Event"]["phone"]}</td>
			<td>{$event["Event"]["url"]}</td>
			<td>" . date("Y-m-d", strtotime($event["CalendarsEvent"]["start_date_time"])) . "</td>
			<td>" . date("Y-m-d", strtotime($event["CalendarsEvent"]["end_date_time"])) . "</td>
		</tr>";
	} else if ($event["Event"]["type"] == 3) { 
		$deadlineTable .= "
		<tr class='deadline_{$event["Event"]["category_id"]}_" . date("Y_m_d", strtotime($event["CalendarsEvent"]["start_date_time"])) . "'>
			<td>{$event["Event"]["title"]}</td>
			<td>{$event["Event"]["description"] }</td>
			<td>{$event["Event"]["location"] }</td>
			<td>{$event["Event"]["contact"] }</td>
			<td>{$event["Event"]["email"] }</td>
			<td>{$event["Event"]["phone"] }</td>
			<td>{$event["Event"]["url"] }</td>
			<td>" . date("Y-m-d", strtotime($event["CalendarsEvent"]["start_date_time"])) . "</td>
			<td>" . date("g:i a", strtotime($event["CalendarsEvent"]["start_date_time"])). "</td>
		</tr>";
	}
} 
?>


<script type='text/javascript' language='javascript'>
$(document).ready(function(){
	$("#normalTable").hide();	
	$("#ongoingTable").hide();
	$("#deadlineTable").hide();

	$("#normalTable tr").hide();	
	$("#ongoingTable tr").hide();
	$("#deadlineTable tr").hide();

	$("#lastMonth").click(function() {
		$("#gridDiv").html('<?php echo $html->image('ajax-loader.gif', array('alt' => 'ajax loader'))?>' );
		$("#gridDiv").load("http://localhost/calv2/events/dategrid/calendar:<?= $calendar; ?>/start:<?= date("Y-m-", strtotime("$start -1 month")) . "01"; ?>");
	});
	$("#nextMonth").click(function() {
		$("#gridDiv").html('<?php echo $html->image('ajax-loader.gif', array('alt' => 'ajax loader'))?>' );
		$("#gridDiv").load("http://localhost/calv2/events/dategrid/calendar:<?= $calendar; ?>/start:<?= date("Y-m-", strtotime("$start +1 month")) . "01"; ?>");
	});

	$(".clickme").mouseover(function() {$(".clickme").css("cursor", "pointer");});
});

function showEvents(table, row) {

	$("#normalTable").hide();	
	$("#ongoingTable").hide();
	$("#deadlineTable").hide();

	$("#normalTable tr").hide();	
	$("#ongoingTable tr").hide();
	$("#deadlineTable tr").hide();

	
	$("#" + table + "Table").show();
	$("#" + table + "Table .titleRow").show();
	$("." + row).show();
}
</script>

<table id='normalTable'>
	<tr class='titleRow'>
		<th>Title</th>
		<th>Description</th>
		<th>Location</th>
		<th>Contact</th>
		<th>Email</th>
		<th>Phone</th>
		<th>URL</th>
		<th>Date</th>
		<th>Start Time</th>
		<th>End Time</th>
	</tr>
	<?= $normalTable; ?>
</table>

<table id='ongoingTable'>
	<tr class='titleRow'>
		<th>Title</th>
		<th>Description</th>
		<th>Location</th>
		<th>Contact</th>
		<th>Email</th>
		<th>Phone</th>
		<th>URL</th>
		<th>Start Date</th>
		<th>End Date</th>
	</tr>
	<?= $ongoingTable; ?>
</table>

<table id='deadlineTable'>
	<tr class='titleRow'>
		<th>Title</th>
		<th>Description</th>
		<th>Location</th>
		<th>Contact</th>
		<th>Email</th>
		<th>Phone</th>
		<th>URL</th>
		<th>Due Date</th>
		<th>Due Time</th>
	</tr>
	<?= $deadlineTable; ?>
</table>

<table>
	<tr class="month"><th colspan='7'>
	<span id='lastMonth' class='clickme'> &#60;&#60; </span>
	<span id='currentMonth'> <?= date("F Y", $startTimeStamp) ?> </span>
	<span id='nextMonth' class='clickme'> &#62;&#62; </span>
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
	$day = date("Y-m-d", strtotime("$lastMonthYear-$lastMonthMonth-" . ($daysInTheLastMonth - $firstWeekdayOfTheMonth) . " +1 day" ));
	for($y = 0; $y < 6; $y++) {
		echo "<tr>";
		for($x = 0; $x < 7; $x++) {
			echo "<td class='";
			if ($y == 0 && $day > 7) {echo "previousMonth";}
			else if ($y >= 4 && $day < 14) {echo "nextMonth";}
			else {echo "currentMonth";}
			if ($day == $today) { echo " today"; }
			echo "'><div>";
			echo date("d", strtotime($day));
			echo "</div>";
			if (isset($ongoingPops[$day])) {
				foreach ($ongoingMarkers as $key => $mark) {
					echo "<div class='space_holder'>";

					if (isset($ongoingPops[$day][$key])) {
						echo "<div class='ongoing_$mark clickme' onclick=\"showEvents('ongoing', 'ongoing_$key')\">";
						echo $ongoingPops[$day][$key]["title"];
						echo "</div>";
					}					
					echo "</div>";
				}
			}
			if (isset($normalPops[$day])) {
				echo "<div>";
				foreach($normalPops[$day] as $key => $value) {
					echo "<span class='normal_$key clickme' onclick=\"showEvents('normal', 'normal_$key" . "_" . date("Y_m_d", strtotime($day)) . "')\" >";
					echo count($normalPops[$day][$key]);
					echo "</span>";
				}
				echo "</div>";
			}
			
			if (isset($deadlinesPops[$day])) {				
				echo "<div>";
				foreach($deadlinesPops[$day] as $key => $value) {
					echo "<span class='deadline_$key clickme' onclick=\"showEvents('deadline', 'deadline_$key" . "_" . date("Y_m_d", strtotime($day)) . "')\" >";
					echo count($deadlinesPops[$day][$key]);
					echo "</span>";
				}
				echo "</div>";
			}
			echo "</td>";
			$day = date("Y-m-d", strtotime("$day +1 day"));
		}
		echo "</tr>";
	}  
	?>
</table>

