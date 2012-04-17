<div class="grid_2 omega vMarginTop_0">
	<?php
		$pos = strpos($_SERVER["HTTP_REFERER"], $_SERVER["HTTP_HOST"]);
			if($pos !== false) {
					echo "<a class='btn' href='{$_SERVER["HTTP_REFERER"]}'>&#9668; Back</a>";
			}
	?>
</div>
<h1 class="titles vPaddingTop_1 vPaddingBottom_0"><?php __('Import Multiple Events');?></h1>

<hr class="dashed"/>
<div class="clear"></div>

<div class="vMarginTop_1 vMarginBottom_1" style="margin:10px;">Create a CSV (Comma Seperated Value) spreadsheet with the following columns.</div>
<div style='width:600px; overflow:auto;margin: 10px;'>
<?php echo $html->image("table.jpg"); ?>
</div>
<div class="vMarginTop_1 vMarginBottom_1" style="margin: 10px;">
You can download a sample <?php echo $html->link("here", "/output.csv"); ?>.
<?php echo $form->create('Event', array('enctype' => 'multipart/form-data', "url" => $html->url(null, true)) );?>
<?php echo $form->input('Calendar', array( "type" => "select", "options" => $chooser->arrayOutput($calendars))); ?>
<?php echo $form->input('Events', array( "type" => "file" )); ?>
 
<?php echo $form->submit("Submit", array("class" => "btn grid_2 vMarginTop_2 vMarginBottom_2")); ?>

<?php echo $form->end();?>
</div>
