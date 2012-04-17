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

<div class="clear"></div>
<div class="grid_2 omega vMarginTop_0">
<?php
$pos = strpos($_SERVER["HTTP_REFERER"], $_SERVER["HTTP_HOST"]);
if($pos !== false) {
	echo "<a class='backBtn button' href='{$_SERVER["HTTP_REFERER"]}'>Back</a>";
}
?>
</div>
<div class="clear"></div>

<div class="calendars form grid_8 vMarginTop_2">

<h2 class="titles backendTitles vPaddingTop_1 vPaddingBottom_0"><?php __('Edit Calendar');?></h2>

<?php echo $form->create('Calendar', array("onsubmit" => "return alertICal()"));?>
	<fieldset>
 		<legend><?php __('Calendar Settings');?></legend>
		<?= $form->input('title', 
			array(
				'class'=>'inputTitle',
				'label'=> array(
					'text'=>'Title',
					'class'=>'notNeeded'
				),
				'div'=> array(
					'class' => 'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 grid_8'
				)
			)
		); ?>
		<div class='clear'></div>
		<?= $form->input('url',array('class'=>'inputURL','label'=>array('text'=>'URL','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 grid_8')));?>
		<div class='clear'></div>
		<?= (isset($calendars)) ? $form->input('parent_id', array('div'=>array('class'=>'grid_6  vPaddingTop_1 vPaddingBottom_1 vMarginBottom_1 '),'class'=>'', "options" => $chooser->arrayOutput($calendars))) : "" ?>
		<?= $form->input('private', array("type" => "checkbox", 'label'=>array('text'=>'Private','class'=>'remember'),'div'=>array('class'=>'grid_3 vPaddingTop_1 vPaddingBottom_1 vMarginBottom_1 smallCheckbox'))); ?>
		<div class='clear'></div>
		<?= $form->input('description',array( 'cols'=>'43','rows'=>'5','class'=>'inputDesc','label'=>array('text'=>'Description','class'=>'notNeeded',),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 grid_8'))); ?>
		<div class='clear'></div>
		<?= $form->input('ical',array("between" => "Please note that when creating a sub-calendar, events pulled from an iCal feed are not submitted to its parent calendar. Please contact the administrator of your parent calendar if you wish for them to subscribe to it. Please also make sure that the iCal contains at minimum the following fields: title, description, location, and contact.",'class'=>'inputURL','label'=>array('text'=>'iCal Feed','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 grid_8')));?>
		<div class='clear'></div>		
	</fieldset>

<?php echo $form->submit('Submit',array('div'=>array('class'=>'grid_8 vMarginTop_1'),'class'=>'btn'));?>
</div>
