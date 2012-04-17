<table class="eventsTable" cellpadding="0" cellspacing="0">
	<tr class="tableHeadings">
		<th class="eDate">
			<?php echo $paginator->sort( 'Date & Time', 'CalendarsEvent.start_date_time');?> 
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
		<th class="eTitle">
			<?php echo $paginator->sort('title');?>
			<?php
				if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "title") {
					if ($this->params["named"]["direction"] == "asc" ) {
						echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
					} else {
						echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
					}
				}
			?>
		</th>
		<th class="eCat">
			<?php echo $paginator->sort('category_id');?> 
			<?php
				if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "category_id") {
					if ($this->params["named"]["direction"] == "asc" ) {
						echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
					} else {
						echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
					}
				}
			?>
		</th>
	</tr>
	<?php
	
		$i = 0;
		foreach ($events as $event):
		$class = null;
		if ($i++ % 2 == 0) { $class = ' class="altrow"'; }
		$FQL='';
		//build FQL query w/ event url and event id
		$uri = "http://".$_SERVER['HTTP_HOST'].".com".$html->url($event['Event']['id'])."/"; 		   
		$FQL = $FQL."\"".$event['Event']['id']."\":\"SELECT like_count FROM link_stat WHERE '".$uri."' IN url  ORDER BY like_count LIMIT 1\",";
	?>
	<tr	<?php echo $class;?>>
		<td class='eventTableDate'>
			<?php if ($event["Event"]["type"] == 2) {
				if (
					date("Y-m-d") >=  date("Y-m-d", strtotime($event['CalendarsEvent']['start_date_time'])) &&
					date("Y-m-d") <=  date("Y-m-d", strtotime($event['CalendarsEvent']['end_date_time']))
				) {
					echo date("F j, Y");
				} else {
					echo date("F j, Y", strtotime($event['CalendarsEvent']['start_date_time']));
				}
			} else {
				echo date("F j, Y", strtotime($event['CalendarsEvent']['start_date_time']));
				echo "<br />";
				if ($event['CalendarsEvent']['start_date_time'] != "00:00:00") { 
					echo date("g:i A" ,strtotime($event['CalendarsEvent']['start_date_time']));
				}
			} ?>						
		</td>
		
		<td><!--the event title.-->
		<?php 
			echo $html->link($event['Event']['title'], array("action" => "view", $event['Event']['id']),array('rel'=>"More details about {$event['Event']['title']}")) ;
			if ($event['Event']['fblike'] > 0) {
		   		$likes = $event['Event']['fblike'];
		   		echo "</br><span class='fblikes'>$likes Likes&nbsp;</span>";
		   	}
		?>
		</td>			
		<td>
			<?= $html->link($categories[$event["Event"]['category_id']], array("controller" => "events", "action" => "index", "calendar:".$this->params["named"]["calendar"]."/category:{$event["Event"]['category_id']}"), array('rel'=>"View all {$categories[$event["Event"]["category_id"]]}" ,"class"=>"eCatBoxes categoryOff_".$event["Event"]["category_id"])); ?>
		</td>
   </tr>
		<?php endforeach; ?>
	<?php $FQL = rtrim($FQL, ','); //remove trailing comma from FQL?>
</table>
<?php Configure::write('FQL',$FQL); //echo $FQL; ?>
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
	<?php echo $paginator->prev(__('Previous', true), array('rel'=>'Go to Previous Page','class'=>'boxes prev'));?>  
	  <span class='numbers'><?php echo $paginator->numbers(array('modulus' => 5));?></span> 
	  <?php if ($paginator->numbers(array('modulus' => 5)) != "") { ?>
	  <span>|</span>
	  <?php } ?>


	<?php echo $paginator->next(__('Next', true), array('rel'=>'Go To Next Page','class' => 'boxes next'));?>
</div>
<div class="clear"></div>
<div class="paging grid_7 push_8 vMarginTop_1 omega vMarginBottom_1">
<?php
$noLimit = "";
foreach($this->passedArgs as $key => $value) {
	if ($key != "limit") {
		$noLimit .= "/$key:$value"; 
	}
}
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
			
<?php if ($viewType != "grid") { ?>
<!-- events-->
<?php } else { ?>
<div id='gridDiv'>
	<?php echo $html->image('ajax-loader.gif', array('alt' => 'ajax loader'))?> 
</div>
<?php } ?>