<?php $javascript->codeBlock( "
$(document).ready(function(){
	datePickerController.createDatePicker({
	  formElements:{'dateStart':'M-sp-j-cc-sp-y'}
	});
	datePickerController.createDatePicker({
	  formElements:{'dateEnd':'M-sp-j-cc-sp-y'}
	});
	
	$('#submitDate').click(function() {
		var hasEvent = false;
		var hasIndex = false;
		var htmlArray = explode('/', document.URL);
		link = 'http://';
		for(x=2; x<htmlArray.length; x++) {
			tmp = explode(':', htmlArray[x]);
			if (!(tmp[0] == 'startDate' || tmp[0] == 'endDate' || tmp[0] == '' )) { link+=htmlArray[x] + '/';}	
			if (htmlArray[x] == 'events') {hasEvent = true;}
			if (htmlArray[x] == 'feature') {hasIndex = true;}
		}
		if (!hasEvent) {link+='events/';}
		if (!hasIndex) {link+='feature/';}
		window.location = link + 'startDate:' + $('#dateStart').val() + '/endDate:' + $('#dateEnd').val();
	});
	
	$('#calendarChooser').change(function() {
		var hasEvent = false;
		var hasIndex = false;
		var htmlArray = explode('/', document.URL);
		link = 'http://';
		for(x=2; x<htmlArray.length; x++) {
			tmp = explode(':', htmlArray[x]);
			if (!(tmp[0] == 'calendar' || tmp[0] == '' )) { link+=htmlArray[x] + '/';}	
			if (htmlArray[x] == 'events') {hasEvent = true;}
			if (htmlArray[x] == 'feature') {hasIndex = true;}
		}
		if (!hasEvent) {link+='events/';}
		if (!hasIndex) {link+='feature/';}
		window.location = link + 'calendar:' + this.value;
	});
});
" , array('inline'=>false)); 

$paginator->options(array('url' => $this->passedArgs));
$noLimit = "";
foreach($this->passedArgs as $key => $value) {
	if ($key != "limit") {
		$noLimit .= "/$key:$value"; 
	}
}

?>


<div class="clear"></div>

<h1 class="titles vPaddingTop_1 vPaddingBottom_0"><?php __('Feature Events');?></h1>
<hr class="dashed"/>
<div class="filterCal grid_12 vPaddingTop_2">
	<div class="grid_3 alpha"><span><strong>Filter by Calendar</strong></span></div>
	<div class="grid_5 omega"><?= $chooser->selectOutput($calendarsList); ?></div>
</div>

<div class="filterTitle grid_12 vPaddingTop_2">
	<?php
$noFilterUrl = "";
foreach($this->params["named"] as $key => $value ) {
	if ($key != "filter") {	$noFilterUrl .= "$key:$value/";	}	
}
echo '<strong>Filter by Title</strong>';
echo '<ul class="hActionsList usersList vMarginTop_0">';
for ($x = 65; $x <= 90; $x++) {
	echo '<li>' . $html->link(chr($x), array("controller" => "events", "action" => "feature", $noFilterUrl . "/filter:" . chr($x)  )) . '</li>';
}
echo '</ul>'
?>

</div>
<div class="clear"></div>
<div class="filterDate grid_16 vPaddingTop_2 vPaddingBottom_2">
	<div class="grid_2 alpha"><span><strong>Filter by Date</strong></span></div>
	<div class="grid_4 omega">
		<input alt="Start Date" class="cDate" type='text' name='start' id='dateStart' readonly="readonly" value='<?php 
		if (isset($this->params["named"]["startDate"])) {echo $this->params["named"]["startDate"]; }
		else { echo date("M j, Y");}
		?>'></input>
	</div>
	<div class="grid_4 omega">
		<input alt="End Date" class="cDate" type='text' name='end' id='dateEnd' readonly="readonly" value='<?php 
		if (isset($this->params["named"]["endDate"])) {echo $this->params["named"]["endDate"]; }
		else { echo date("M j, Y");}
		?>'></input>
	</div>
<input type='button' class="btn" value='Submit' id='submitDate' name='submitDate'/>

</div>

<div class="clear"></div>
<hr class="dashed"/>

<?php echo $form->create("Pending", array( "url"=> "feature")); ?>
<table class="eventsTable" cellpadding="0" cellspacing="0">
<tr>
	
	
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
	<?php echo $paginator->sort("Featured", 'CalendarsEvent.featured');?>
	<?php
		if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "CalendarsEvent.featured") {
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
	<?php echo $paginator->sort("Type", 'Event.type');?>
	<?php
		if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "Event.type") {
			if ($this->params["named"]["direction"] == "asc" ) {
				echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
			} else {
				echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
			}
		}
	?>
	</th>
	<th>
	<?php echo $paginator->sort("Start Date", 'CalendarsEvent.start_date_time');?>
	<?php
		if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "CalendarsEvent.start_date_time") {
			if ($this->params["named"]["direction"] == "asc" ) {
				echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
			} else {
				echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
			}
		}
	?>
	</th>
	<th>
	<?php echo $paginator->sort("End Date", 'CalendarsEvent.end_date_time');?>
	<?php
		if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "CalendarsEvent.end_date_time") {
			if ($this->params["named"]["direction"] == "asc" ) {
				echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
			} else {
				echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
			}
		}
	?>
	</th>
	<th class="actions"><?php __('Actions');?></th>
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
			<?php echo $html->link($event['Event']['title'], array("controller" => "events", "action" => "view", $event['Event']['id'] )) ; ?>
		</td>
		
		<td>
			<?php echo ($event['CalendarsEvent']['featured'])? "Yes" : "No"; ?>
		</td>
		
		<td>
			<?php echo substr($event['Event']['description'], 0, 100); ?>
		</td>
		<td>
			<?php echo $event['Event']['location']; ?>
		</td>
		<td>
			<?php echo $event['Event']['contact']; ?>
		</td>
		<td>
			<?php switch ($event['Event']['type']) {
				case 1: echo "Normal"; break;
				case 2: echo "Ongoing"; break;
				case 3: echo "Deadline"; break;
			} ?>
		</td>
		<td>
			<?php echo $event['CalendarsEvent']['start_date_time']; ?>
		</td>
		<td>
			<?php echo $event['CalendarsEvent']['end_date_time']; ?>
		</td>
		<!-- <td> -->
			<?php /*echo $event['Event']['created'];*/ ?>
		<!-- </td> -->
		<!-- <td>-->
			<?php /*echo $event['Event']['modified'];*/ ?>
		<!-- </td> -->
		<td class="actions">
			<?php echo $form->checkbox("action", 
				array( "label" => "empty", "type" => "checkbox", "name" => "data[Feature][{$event['CalendarsEvent']['id']}]", "id"  => "data[Pending][{$event['CalendarsEvent']['id']}]" )); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<div class="grid_7 vMarginTop_1">
				
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
<div class="grid_15 vPaddingBottom_2 alpha omega">
	<div class="grid_5 vMarginTop_2">
	<?php echo $form->submit("Featured", array("name" => "data[featured]", "class" => "btn grid_2","div"=>array('class'=>'approve'))); ?>
<?php echo $form->submit("Not Featured", array("name" => "data[no_feature]", "class" => "btn grid_2","div"=>array('class'=>'deny'))); ?>
<?php echo $form->end(); ?></div>
	<div class="grid_8 push_2 paging vMarginTop_2">
				<?php echo $paginator->prev(__('Previous', true), array('class'=>'boxes prev'));?>  
				  <span class='numbers'><?php echo $paginator->numbers(array('modulus' => 5));?></span> 
				  <?php if ($paginator->numbers(array('modulus' => 5)) != "") { ?>
				  <span>|</span>
				  <?php } ?>
		
		
				<?php echo $paginator->next(__('Next', true), array('class' => 'boxes next'));?>
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
			

</div>
<div class="clear"></div>

