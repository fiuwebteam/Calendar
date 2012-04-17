<div class="clear"></div>

	<div class="grid_8"> 
	<h1 class="titles vPaddingTop_1 vPaddingBottom_0"><?php __('Search');?></h1>
</div>
<div class="clear"></div>



<?php $paginator->options(array('url' => $this->passedArgs));?>
<div class="users index search content-container">
 <div class="grid_8"><h5 class="titles">Search for "<?= $this->params["named"]["search"] ?>"</h5></div>
<hr class="dashed" />

<table class="eventsTable" cellpadding="0" cellspacing="0">
<tr class="tableHeadings">
	<th class="eTitle">Title</th>
	<th class="eDesc">Description</th>
	<th class="eLocation">Location</th>
	<th class="eContact">Time</th>
	<th class="eType">Type</th>
	<th class="eCat">Category</th>
</tr>
<?php
$i = 0;
foreach ($events as $event):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $html->link($event['Event']['title'], array('action' => 'view', $event['Event']['id'])); ?>
		</td>
		<td>
			<?php echo $text->truncate($event['Event']['description'], 100, array('ending'=>'...', 'exact'=> false)); ?>

		</td>
		<td>
			<?php echo $event['Event']['location']; ?>
		</td>
		<td>
<?php 
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
					$dateOutput .= "Every " . floor($repeat["variable"]) . "th day pf the month starting on ";
					$dateOutput .= date("F j, Y", strtotime($repeat["start_date"]));
					$dateOutput .= " from " . date("g:i a", strtotime($repeat["start_time"]));
					$dateOutput .= " to " . date("g:i a", strtotime($repeat["end_time"])) . ". Ending on ";
					$dateOutput .= date( "F j, Y", strtotime($repeat["end_date"]));
					break;
					// yearly
				case 5:
					$dateOutput .= "Every " . floor($repeat["variable"]) . "th day pf the year starting on ";
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
echo $dateOutput;
?>
		</td>
		<td>
			<?php switch ($event['Event']['type']) {
				case 1: echo "Normal"; break;
				case 2: echo "Ongoing"; break;
				case 3: echo "Deadline"; break;
			} ?>
		</td>
		<td>
			<?php echo $html->link($categories[$event["Event"]['category_id']], array("controller" => "events", "action" => "index", "calendar:main/category:{$event["Event"]['category_id']}"),array("class"=>"eCatBoxes categoryOff_".$event["Event"]["category_id"] ));?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
	<div class="grid_7 vMarginTop_1">
				<p class="pageInfo "><?php echo $paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)));
			?>
				</p>
			</div>
			<div class="paging grid_7 push_1 vMarginTop_1 omega vMarginBottom_1">
				<?php echo $paginator->prev(__('Previous', true), array('class'=>'boxes prev'));?>  
				  <span class='numbers'><?php echo $paginator->numbers(array('modulus' => 5));?></span> 
				  <?php if ($paginator->numbers(array('modulus' => 5)) != "") { ?>
				  <span>|</span>
				  <?php } ?>
		
		
				<?php echo $paginator->next(__('Next', true), array('class' => 'boxes next'));?>
			</div>
			<div class="clear"></div>
</div><!-- end content-container-->

