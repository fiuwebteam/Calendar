

<div class="grid_2 omega vMarginTop_0">
	<?php
		$pos = strpos($_SERVER["HTTP_REFERER"], $_SERVER["HTTP_HOST"]);
			if($pos !== false) {
					echo "<a class='btn' href='{$_SERVER["HTTP_REFERER"]}'>&#9668; Back</a>";
			}
	?>
</div>
<div class="clear"></div>
<div class="grid_16">
<h1 class="titles vPaddingTop_1 vPaddingBottom_0"><?php __('Add a User');?></h1>
</div>
<hr class="dashed" />
<div class="clear"></div>



<div class="users form  ">
<?php echo $form->create('User');?>
	<fieldset>
 		<legend><?php __('Add User');?></legend>
	<?php
echo $form->input('username',array('tabindex'=>'1','class'=>'inputUserName','label'=>array('text'=>'User Name','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 grid_8')));
		echo "<div class='clear'></div>";
		
		
		echo $form->input('password',array('tabindex'=>'2','class'=>'inputUserName','label'=>array('text'=>'Password','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 required grid_8')));
		echo "<div class='clear'></div>";
				
		echo $form->input('password_confirm',array('tabindex'=>'3','type' => 'password', 'class'=>'inputEmail','label'=>array('text'=>'Confirm Password','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 required grid_8')));
		echo "<div class='clear'></div>";
		
		echo $form->input('email',array('tabindex'=>'4','class'=>'inputEmail','label'=>array('text'=>'E-mail','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 required grid_8')));
		echo "<div class='clear'></div>";
		
	
		echo $form->input('first_name',array('tabindex'=>'5','class'=>'inputFirstName','label'=>array('text'=>'First Name','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 required grid_8')));
		echo "<div class='clear'></div>";
		
		echo $form->input('last_name',array('tabindex'=>'6','class'=>'inputLastName','label'=>array('text'=>'Last Name','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 required grid_8')));
		echo "<div class='clear'></div>";
		
		echo $form->input('receives_email', array('tabindex'=>'7',"type" => "checkbox", 'label'=>array('text'=>'Receives E-Mail','class'=>'remember'),'div'=>array('class'=>'grid_3 alpha vPaddingTop_1 vPaddingBottom_1 vMarginBottom_1 smallCheckbox required ')));
		echo "<div class='clear'></div>";
	
	?>
	</fieldset>
<?php echo $form->submit('Submit',array('tabindex'=>'8', 'div'=>array('class'=>'grid_8 vMarginTop_1 vMarginBottom_2'),'class'=>'btn'));?>
</div>
 
