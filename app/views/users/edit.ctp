<div class="clear"></div>
<div class="grid_2 omega vMarginTop_0">
<?php
$pos = strpos($_SERVER["HTTP_REFERER"], $_SERVER["HTTP_HOST"]);
if($pos !== false) {
	echo "<a class='btn' href='{$_SERVER["HTTP_REFERER"]}'>&#9668; Back</a>";
}
?>
</div>
<div class="clear"></div>
<div class="users form vMarginTop_2 grid_8">
<h1 class="titles backendTitles vPaddingTop_1 vPaddingBottom_0"><?php __('Edit User');?></h1>
<?php echo $form->create('User');?>
	<fieldset>
 		<legend><?php __('Edit User');?></legend>
	<?php
			echo $form->input('id',array('tabindex'=>'1','class'=>'inputID','label'=>array('text'=>'ID','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 grid_8')));
		echo "<div class='clear'></div>";
	
			echo $form->input('username',array('tabindex'=>'2','class'=>'inputUserName','label'=>array('text'=>'User Name','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 grid_8')));
		echo "<div class='clear'></div>";
	
		
			echo $form->input('password',array('tabindex'=>'3','class'=>'inputEmail','label'=>array('text'=>'Password','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 grid_8 required')));
		echo "<div class='clear'></div>";
		
		echo $form->input('password_confirm',array('tabindex'=>'4','type' => 'password', 'class'=>'inputEmail','label'=>array('text'=>'Confirm Password','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 grid_8 required')));
		echo "<div class='clear'></div>";
		
		//echo $form->input('password_confirm',array( 'type' => 'password', 'class'=>'inputPassword','label'=>array('text'=>'Confirm Password','class'=>'notNeeded'),'div'=>array('class'=>'grid_8 alpha vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 required')));
			echo $form->input('email',array('tabindex'=>'5','class'=>'inputEmail','label'=>array('text'=>'E-mail','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 grid_8')));
		echo "<div class='clear'></div>";
		
			echo $form->input('first_name',array('tabindex'=>'6','class'=>'inputFirstName','label'=>array('text'=>'First Name','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 grid_8')));
		echo "<div class='clear'></div>";
		
			echo $form->input('last_name',array('tabindex'=>'7','class'=>'inputLastName','label'=>array('text'=>'Last Name','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 grid_8')));
		echo "<div class='clear'></div>";
		


		echo $form->input(
			'receives_email', 
			array(
				'tabindex'=>'8',
				"options" => array(
					0 => "Do not receive email",
					1 => "Daily digest",
					2 => "On submission"
				), 
				'label'=> 
					array(
						'text'=>'Receives E-Mail',
						'class'=>'remember'
					),
				'div'=>
					array(
						'class'=>'grid_3 vPaddingTop_1 vPaddingBottom_1 vMarginBottom_1 smallCheckbox'
					)
				)
			);
		echo "<div class='clear'></div>";
		
	
	?>
	</fieldset>
<?php echo $form->submit('Submit',array('tabindex'=>'9','div'=>array('class'=>'grid_8 vMarginTop_1 vMarginBottom_1'),'class'=>'btn'));?>
</div>