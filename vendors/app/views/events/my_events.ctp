<?php 
$paginator->options(array('url' => $this->passedArgs));
$noLimit = "";
foreach($this->passedArgs as $key => $value) {
	if ($key != "limit") {
		$noLimit .= "/$key:$value"; 
	}
}
?>

 

	<div class="related grid_8">			
		</div><!-- end related-->
		<div class="clear"></div>


	<div class="grid_8">
		<h1 class="titles vPaddingTop_1 vPaddingBottom_0"><?php __('My Events');?></h1>
	</div>
<div class="clear"></div>
<h5 class="titles grid_5"><?php __('These are your active events');?></h5>
<hr class="dashed" />

<div class="paging grid_7 push_8 vMarginTop_1 omega vMarginBottom_1">	
	<?php echo $paginator->prev(__('Previous', true), array('class'=>'boxes prev'));?>  
	  <span class='numbers'><?php echo $paginator->numbers(array('modulus' => 5));?></span> 
	  <?php if ($paginator->numbers(array('modulus' => 5)) != "") { ?>
	  <span>|</span>
	  <?php } ?>
	<?php echo $paginator->next(__('Next', true), array('class' => 'boxes next'));?>
</div>

<div class="clear"></div>

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
	<th class="eCreated">
		<?php echo $paginator->sort("Created", 'Event.created');?>
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
		<td>
			<?php switch ($event['Event']['type']) {
				case 1: echo "Normal"; break;
				case 2: echo "Ongoing"; break;
				case 3: echo "Deadline"; break;
			} ?>
		</td>
		<td>
			<?php echo date("M j, Y <b\\r/> g:i a", strtotime($event['Event']['created'])) ; ?>
		</td>
		<td class="actions">
			<ul>
				<li><?php echo $html->link(__('View', true), array('action' => 'view', $event['Event']['id'])); ?></li>
				<li><?php echo $html->link(__('Edit', true), array('action' => 'edit', $event['Event']['id'])); ?></li>
<li><?php echo $html->link(__('Delete', true), array('action' => 'delete', $event['Event']['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $event['Event']['title'])); ?></li>
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
			<div class="paging grid_7 push_1 vMarginTop_1 omega vMarginBottom_1">
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


