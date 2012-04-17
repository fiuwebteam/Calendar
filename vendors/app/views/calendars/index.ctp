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
<div class="grid_8"> 
	<h1 class="titles vPaddingTop_1 vPaddingBottom_0"><?php __('My Calendars');?></h1>
</div>


<div class="clear"></div>
<div class="users index">

<h5 class="titles grid_7">These are your calendars:</h5>


<hr class="dashed"/>


<div class="paging grid_7 push_8 vMarginTop_1 omega vMarginBottom_1">
	<?php echo $paginator->prev(__('Previous', true), array('class'=>'boxes prev'));?>  
	  <span class='numbers'><?php echo $paginator->numbers(array('modulus' => 5));?></span> 
	  <?php if ($paginator->numbers(array('modulus' => 5)) != "") { ?>
	  <span>|</span>
	  <?php } ?>
	<?php echo $paginator->next(__('Next', true), array('class' => 'boxes next'));?>
</div>




<table class="eventsTable" cellpadding="0" cellspacing="0">
<tr class="tableHeadings">
	<th>
		<?php echo $paginator->sort("Title", 'Calendar.title');?>
		<?php
			if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "Calendar.title") {
				if ($this->params["named"]["direction"] == "asc" ) {
					echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				} else {
					echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				}
			}
		?>
	</th>
	<th>
		<?php echo $paginator->sort("Url", 'Calendar.url');?>
		<?php
			if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "Calendar.url") {
				if ($this->params["named"]["direction"] == "asc" ) {
					echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				} else {
					echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				}
			}
		?>
	</th>
	<th>
		<?php echo $paginator->sort("Description", 'Calendar.description');?>
		<?php
			if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "Calendar.description") {
				if ($this->params["named"]["direction"] == "asc" ) {
					echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				} else {
					echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				}
			}
		?>
	</th>
	<th>
		<?php echo $paginator->sort("Private", 'Calendar.private');?>
		<?php
			if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "Calendar.private") {
				if ($this->params["named"]["direction"] == "asc" ) {
					echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				} else {
					echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				}
			}
		?>
	</th>
	<th class="eCreated">
		<?php echo $paginator->sort("Created", 'Calendar.created');?>
		<?php
			if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "Calendar.created") {
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
foreach ($calendars as $calendar):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $calendar['Calendar']['title']; ?>
		</td>
		<td>
			<?php echo $calendar['Calendar']['url']; ?>
		</td>
		<td>
			<?php echo $text->truncate($calendar['Calendar']['description'], 50, array('ending'=>'...', 'exact'=> false)); ?>
		</td>
		<td>
			<?php echo $calendar['Calendar']['private'] ? "Yes" : "No"; ?>
		</td>
		<td>
			<?php  echo date("M j, Y <b\\r/> g:i a", strtotime($calendar['Calendar']['created'])) ; ?>
		</td>
		<td class="actions">
			<?php echo $html->link("Edit", array("action" => "edit", $calendar['Calendar']['id'] )); ?>
			<br/>
			<?php
				if ($calendar['Calendar']['id'] != 1) { 
					echo $html->link("Drop", array("action" => "delete", $calendar['Calendar']['id'] ));
				} 
			?>
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

</div>


