

<div class="clear"></div>
<div class="grid_16 omega alpha users index">
<div class="clear"></div>
<div class="grid_2 omega vMarginTop_0">
<?php
$pos = strpos($_SERVER["HTTP_REFERER"], $_SERVER["HTTP_HOST"]);
if($pos !== false) {
	echo "<a class='btn' href='{$_SERVER["HTTP_REFERER"]}'>&#9668; Back</a>";
}
?>
</div>
<div class="users index vMarginTop_1 form">
	<h1 class="titles vPaddingTop_1 vPaddingBottom_0"><?php __('Change User Permissions');?></h1>
<hr class="dashed" />


	<?php echo $form->create(null, array("url" => "/users/changeTier")); ?>
	<?php echo $form->input("User", array(
		"options" => $users, 
		"type" => "select", 
		"name" => "data[User][User]",
		'div'=>array('class'=>'grid_3 formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1'),
		"selected" => (isset($this->params["named"]["user"]) ? $this->params["named"]["user"] : "" )
	))?>
	<?php echo $form->input("Calendar", array(
		"options" => $calendars, 
		"type" => "select",
		'div'=>array('class'=>'grid_3 prefix_1 formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1'),
		"selected" => (isset($this->params["named"]["calendar"]) ? $this->params["named"]["calendar"] : "" )
	))?>
	<?php echo $form->input("Group", array("type" => "select", "options" => array( 2 => "Administrator", 3 => "Editor", 4 => "Author", 5 => "Contributor"),'div'=>array('class'=>'grid_3 formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1')) )?>

	
		<?php echo $form->submit('Change',array('div'=>array('class'=>'grid_8 vMarginTop_1 vMarginBottom_2'),'class'=>'btn'));?>
		</form>
 		
</div>

</div>