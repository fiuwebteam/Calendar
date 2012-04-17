
<?php 
$js = <<<EOF
function alertICal() {
	if ($("#CalendarIcal").val() != "") {
		alert("Note: iCal inputted events are not submitted to parent calendars. Please contact the administrator of your parent calendar if you wish for them to subscribe to it. Please also make sure that the iCal contains at minimum the following fields: title, description, location, and contact.");		
	}
}
EOF;
$javascript->codeBlock( $js, array('inline'=>false));
?>

<div class="vMarginTop_2 grid_2 users index">

	<?php
		$pos = strpos($_SERVER["HTTP_REFERER"], $_SERVER["HTTP_HOST"]);
			if($pos !== false) {
					echo "<a class='btn href='{$_SERVER["HTTP_REFERER"]}'>&#9668; Back</a>";
			}
	?>
</div>

<div class="clear"></div>

<div class="calendars form vMarginTop_2"> 

<h1 class="titles vPaddingTop_1 vPaddingBottom_0"><?php __('Add a Calendar');?></h1>
<hr class="dashed">
<?php echo $form->create('Calendar', array("onsubmit" => "return alertICal()"));?>
	<fieldset>
 		<legend><?php __('Add a Calendar');?></legend>
	<?php
		
		echo $form->input('title',array('tabindex'=>'1','class'=>'inputTitle','label'=>array('text'=>'Title','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 grid_8 ')));
		
		echo "<div class='clear'></div>";
		
		echo $form->input('url',array('tabindex'=>'2','class'=>'inputURL','label'=>array('text'=>'URL','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 grid_8 ')));
		
		echo "<div class='clear'></div>";
		
		echo $form->input('parent_id', array("style" => "width:150px;", 'tabindex'=>'3','div'=>array('class'=>'grid_3   vPaddingTop_1 vPaddingBottom_1 vMarginBottom_1'),'class'=>'', "options" => $calendars));
		
		echo "<div class='clear'></div>";
		
		echo "<div class='grid_3   vPaddingTop_1 vPaddingBottom_1 vMarginBottom_1' >";
		
		//echo $form->input('private', array('tabindex'=>'4',"type" => "checkbox", 'label'=>false));
		
		echo $form->input('private', array("type" => "checkbox", 'label'=>array('text'=>'Private','class'=>'remember'),'div'=>array('class'=>'grid_3 vPaddingTop_1 vPaddingBottom_1 vMarginBottom_1 smallCheckbox')));
		
		
		echo $form->input('description',array( 'cols'=>'43','rows'=>'5','class'=>'inputDesc','label'=>array('tabindex'=>'5','text'=>'Description','class'=>'notNeeded',),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 grid_8')));

		echo "<div class='clear'></div>";

		echo $form->input('ical',array("between" => "Please note that when creating a sub-calendar, events pulled from an iCal feed are not submitted to its parent calendar. Please contact the administrator of your parent calendar if you wish for them to subscribe to it. Please also make sure that the iCal contains at minimum the following fields: title, description, location, and contact.", 'tabindex'=>'6','class'=>'inputURL','label'=>array('text'=>'iCal Feed','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 grid_8 ')));
		
		
	?>
	</fieldset>
	<div class='clear'></div>	
	

<?php echo $form->submit('Submit',array('div'=>array('class'=>'grid_8 vMarginTop_1 vMarginBottom_2'),'class'=>'btn'));?>
</div>