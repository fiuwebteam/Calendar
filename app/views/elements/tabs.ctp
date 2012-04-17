<ul class="topTabs grid_6 left vMarginTop_1"  id="lowerTabs" style=";">
	<li id='addEventButton' style='<?php echo (!$isLogged) ? "display:none;" : "display:block"; ?>'>
		<?= $html->link("Add Event", array("controller" => "events", "action" => "add"), array('class'=>'boxes', 'id'=>'addEventButton')); ?>
	</li>	
	<li>
		<a href="#" rel='Select a category' id="categoryButton">Category</a>
	</li>
	<li>
		<a rel="type" href="#" class=" popButton" id='calendarsButton'>Calendars</a>
	</li>
</ul>
	