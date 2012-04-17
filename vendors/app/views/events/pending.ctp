<?php 
$paginator->options(array('url' => $this->passedArgs));
$noLimit = "";
foreach($this->passedArgs as $key => $value) {
	if ($key != "limit") {
		$noLimit .= "/$key:$value"; 
	}
}
?>

<div class="clear"></div>
<div class="grid_2 omega vMarginTop_0">
<?php
$pos = strpos($_SERVER["HTTP_REFERER"], $_SERVER["HTTP_HOST"]);
if($pos !== false) {
	echo "<a class='btn' href='{$_SERVER["HTTP_REFERER"]}'>&#9668; Back</a>";
}
?>
</div>
 

<div class="grid_8">
	<h1 class="titles vPaddingTop_1 vPaddingBottom_0"><?php __('My Pending Events');?></h1>
</div>
<div class="clear"></div>
<div class="grid_7"><h5 class="titles">These are your pending events</h5></div>
<hr class="dashed" />



<?php if (!empty($events)) {?>
<div class="clear"></div>
<div class="paging grid_7 push_8 vMarginTop_1 omega vMarginBottom_1">
	<?php echo $paginator->prev(__('◄ Previous', true), array('class'=>'btn'));?>  
	  <span class='numbers'><?php echo $paginator->numbers(array('modulus' => 5));?></span> 
	  <?php if ($paginator->numbers(array('modulus' => 5)) != "") { ?>
	  <span>|</span>
	  <?php } ?>
	<?php echo $paginator->next(__('Next ►', true), array('class' => 'btn'));?>
</div>

<div class="clear"></div>
<?php } ?>
	
			
<?php echo $form->create("Pending", array( 'class'=>"pendingForm","url"=> "pending")); ?>
<div class="grid_5 push_12 vPaddingTop_2 vPaddingBottom_2">
	<?php echo $form->submit("Approve", array('div'=>array('class'=>'grid_2 alpha omega approve'), "id"=>"approve2", 'class'=>'btn',"name" => "data[approve]")); ?>
	
	<?php echo $form->submit("Deny", array('div'=>array('class'=>'grid_2 alpha omega deny'), "id"=>"deny2", 'class'=>'btn',"name" => "data[deny]")); ?>
</div>
<table cellpadding="0" cellspacing="0" class="eventsTable">
<tr class="tableHeadings">
	<th>
		<?php echo $paginator->sort("Title", 'Event.title');?>
		<?php
			if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "Event.title") {
				if ($this->params["named"]["direction"] == "asc" ) {
					echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				} else {
					echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				}
			}
		?>
	</th>
	<th>
		<?php echo $paginator->sort("Description", 'Event.description');?>
		<?php
			if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "Event.description") {
				if ($this->params["named"]["direction"] == "asc" ) {
					echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				} else {
					echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				}
			}
		?>
	</th>
	<th>
		<?php echo $paginator->sort("Location", 'Event.location');?>
		<?php
			if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "Event.location") {
				if ($this->params["named"]["direction"] == "asc" ) {
					echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				} else {
					echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				}
			}
		?>
	</th>
	<th>
		<?php echo $paginator->sort("Contact", 'Event.contact');?>
		<?php
			if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "Event.contact") {
				if ($this->params["named"]["direction"] == "asc" ) {
					echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				} else {
					echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				}
			}
		?>
	</th>
	<th>
		<?php echo $paginator->sort("Author", 'User.username');?>
	</th>
	<th>
	Date
	</th>
	<!-- <th>
		<?php /* echo $paginator->sort("Created", 'Event.created');?>
		<?php
			if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "Event.created") {
				if ($this->params["named"]["direction"] == "asc" ) {
					echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				} else {
					echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				}
			}
		*/?>
	</th>-->
	
	
	<th><?php __('Actions');?></th>
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
			<?php echo $event['Event']['title']; ?>
		</td>
		<td>
			<?php echo $text->truncate($event['Event']['description'], 50, array('ending'=>'...', 'exact'=> false)); ?>
		</td>
		<td>
			<?php echo $event['Event']['location']; ?>
		</td>
		<td>
			<?php echo $event['Event']['contact']; ?>
		</td>
		
		<td><?php echo $html->link($event['User']['username'], array("controller" => "users", "action" => "view", $event['User']["id"] )) ; ?></td>
		<!-- <td>
			<?php /* echo date("M j, Y <b\\r/> g:i a", strtotime($event['Event']['created'])) ; */ ?>
		</td>-->
		
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
		
		<td >
			<ul>
				<li><?php echo $html->link(__('Edit', true), array('action' => 'edit', $event['Event']['id'])); ?></li>
				<li><?php echo $html->link(__('View', true), array('action' => 'view', $event['Event']['id'])); ?></li>
			</ul>
		</td>	
		<td>
			<ul>
					<?php
					$tmp = array();
					foreach($event["CalendarsEvent"] as $ca) {
						if (!in_array($ca["Calendar_title"], $tmp)) {
							$tmp[] = $ca["Calendar_title"];
							echo '<li>' . $form->input($ca["Calendar_title"] . '</li>', 
							array( "type" => "checkbox", "name" => "data[Pending][{$ca["id"]}]", "id"  => "data[Pending][{$ca["id"]}]" ));
						}										
					} 
					?>
			</ul>
		</td>	
	</tr>
<?php endforeach; ?>
</table>
	<div class="grid_10 vMarginTop_1">
				
				<?php
				  
					if ($paginator->counter(array('format' => __('%page%',true))) >= 1) {
						echo  '<p class="pageInfo ">';
						echo $paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total', true)));
						echo '</p>';
					}
					else{
						echo '<p class="pageInfo"> There are no entries. </p>';
					}
				
				?>

			</div>
			<div class="grid_5 push_2 vPaddingTop_2 vPaddingBottom_2">
	<?php echo $form->submit("Approve", array('div'=>array('class'=>'grid_2 alpha omega approve'), "id"=>"approve", 'class'=>'btn',"name" => "data[approve]")); ?>
	
	<?php echo $form->submit("Deny", array('div'=>array('class'=>'grid_2 alpha omega deny'), "id"=>"deny", 'class'=>'btn',"name" => "data[deny]")); ?>
	

<?php echo $form->end(); ?>
</div>
			<div class="clear"></div>
			<div class="paging grid_7 push_8 vMarginTop_1 omega vMarginBottom_1">
				<?php echo $paginator->prev(__('◄ Previous', true), array('class'=>'btn'));?>  
				  <span class='numbers'><?php echo $paginator->numbers(array('modulus' => 5));?></span> 
				  <?php if ($paginator->numbers(array('modulus' => 5)) != "") { ?>
				  <span>|</span>
				  <?php } ?>
		
		
				<?php echo $paginator->next(__('Next ►', true), array('class' => 'btn'));?>
			</div>
						<div class="clear"></div>
			<div class="paging grid_7 push_8 vMarginTop_1 omega vMarginBottom_1">
			<?php
			echo $html->link("20", array($noLimit."/limit:20"));
			echo " | ";
			echo $html->link("50", array($noLimit."/limit:50"));
			echo " | ";
			echo $html->link("100", array($noLimit."/limit:100"));
			echo " | ";
			echo $html->link("500", array($noLimit."/limit:500"));	
			?> per page
			</div>
			<div class="clear"></div>
