<div class="vMarginTop_2 users form grid_16">
<h1>Reset Your Password</h1>


<?php echo $form->create('User', array('action' => 'resetpswd'));?>

	<fieldset>
 		<legend><?php __('Reset Password');?></legend>
	<?php
			echo $form->input('username',array('tabindex'=>'1','class'=>'inputUserName','label'=>array('text'=>'Username','class'=>'notNeeded'),'div'=>array('class'=>'grid_8 alpha vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1')));
		echo $form->input('email',array('tabindex'=>'2','class'=>'inputEmail','label'=>array('text'=>'E-Mail','class'=>'notNeeded'),'div'=>array('class'=>'grid_8 alpha vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1')));
	
	?>
	</fieldset>
    	<?php echo $form->submit('Submit',array('tabindex'=>'3','div'=>array('class'=>'grid_8 vMarginTop_1'),'class'=>'button submitBtn'));?>
<?php echo $form->end();?>
    </div>
