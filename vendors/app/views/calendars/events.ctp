<?php
$javascript->codeBlock('
$(document).ready(function(){
	
	$("#calendarChooser").change(function() {
		var htmlArray = explode("/", document.URL);
		link = "http://";
		for(x=2; x < htmlArray.length; x++) {
			tmp = explode(":", htmlArray[x]);
			if (tmp[0] != "calendars") { link+=htmlArray[x] + "/";}
			else { break; }
		}
		link += "calendars/events/calendar:" + $(this).val();
		window.location = link;
	
	});
	
});
', array('inline' => false));

$paginator->options(array('url' => $this->passedArgs));
$noLimit = "";
foreach($this->passedArgs as $key => $value) {
	if ($key != "limit") {
		$noLimit .= "/$key:$value"; 
	}
}
?>

	<div class="grid_2 omega vMarginTop_0">
	<?php
		$pos = strpos($_SERVER["HTTP_REFERER"], $_SERVER["HTTP_HOST"]);
			if($pos !== false) {
					echo "<a class='btn' href='{$_SERVER["HTTP_REFERER"]}'>&#9668; Back</a>";
			}
	?>
</div>
<div class="clear"></div>

	<div class="grid_8"> 
	<h1 class="titles vPaddingTop_1 vPaddingBottom_0"><?php __('View Calendar Events');?></h1>
</div>
	
		<div class="clear"></div>

	
	<div class="grid_13 vPaddingTop_1 vPaddingBottom_1">
		<div class="grid_8 alpha vMarginBottom_2">
			 <label class="titles"for="calendarChooser">You are currently viewing the events of calendar </Label>

		 </div>
		<div class="grid_4  omega"><?= $chooser->selectOutput($calendarsList); ?></div>
	</div>

	
	<hr class="dashed" />
	
<div class="paging grid_7 push_8 vMarginTop_1 omega vMarginBottom_1">
	<?php echo $paginator->prev(__('◄ Previous', true), array('class'=>'btn'));?>  
	  <span class='numbers'><?php echo $paginator->numbers(array('modulus' => 5));?></span> 
	  <?php if ($paginator->numbers(array('modulus' => 5)) != "") { ?>
	  <span>|</span>
	  <?php } ?>
	<?php echo $paginator->next(__('Next ►', true), array('class' => 'btn'));?>
</div>

<?php echo $form->create("CalendarsEvent", array( 'class'=>"pendingForm","url"=> "events")); ?>

			<div class="grid_5 push_5 vMarginTop_6 vPaddingBottom_2">
				<?php echo $form->submit("Drop From Calendar", array('div'=>array('class'=>'grid_2 alpha omega deny'), "id"=>"drop2", 'class'=>'btn',"name" => "data[drop]")); ?>
				
				</div>
<table class="eventsTable" cellpadding="0" cellspacing="0">
<tr class="tableHeadings">
	<!-- <th class="eTitle">  -->
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
	<!-- <th class="eDesc">  -->
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
	<!-- <th class="eLocation"> -->
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
		<?php echo $paginator->sort("Author", 'User.username');?>
	</th>
	<!-- <th class="eCreated"> -->
	<th>
		<?php echo $paginator->sort("Created", 'Event.created');  ?>
		<?php
			if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "Event.created") {
				if ($this->params["named"]["direction"] == "asc" ) {
					echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				} else {
					echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				}
			}
		?>
	</th>
	<!-- <th class="actions"> -->
	<th>
		<?php __('Actions');?>
	</th>
	
	
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
			<?php switch ($event['Event']['type']) {
				case 1: echo "Normal"; break;
				case 2: echo "Ongoing"; break;
				case 3: echo "Deadline"; break;
			} ?>
		</td>
		<td>
			<?php echo $html->link($event["User"]["username"], array("controller" => "users", "action" => "view", $event["User"]["id"])) ?>
		</td>
		<td>
			<?php echo date("M j, Y <b\\r/> g:i a", strtotime($event['Event']['created'])) ; ?>
		</td>
		
		<td class="actions">
		 	<ul>
				<li><?php echo $html->link(__('View', true), array( 'controller' => 'events', 'action' => 'view', $event['Event']['id'])); ?></li>
				<li><?php echo $html->link(__('Edit', true), array( 'controller' => 'events', 'action' => 'edit', $event['Event']['id'])); ?></li>
				<li><?php echo $html->link(__('Delete', true), array('controller' => 'events', 'action' => 'delete', $event['Event']['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $event['Event']['title'])); ?></li>
				
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
							array( "type" => "checkbox", "name" => "data[CalendarsEvent][{$ca["calendar_id"]}][{$ca["event_id"]}]", "id"  => "data[CalendarsEvent][{$ca["calendar_id"]}][{$ca["event_id"]}]" ));
						}										
					} 
					?>
			</ul>
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
			
			<div class="grid_5 push_5 vPaddingTop_2 vPaddingBottom_2">
				<?php echo $form->submit("Drop From Calendar", array('div'=>array('class'=>'grid_2 alpha omega deny'), "id"=>"drop", 'class'=>'btn',"name" => "data[drop]")); ?>
				<?php echo $form->end(); ?>
			</div>
			<div class="clear"></div>
			<div class="paging grid_7 push_8 vMarginTop_1 omega vMarginBottom_1">
				<?php echo $paginator->prev(__('Previous', true), array('class'=>'boxes prev'));?>  
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
