<div class="vMarginTop_2 users form grid_16">

<h1>Login for FIU's University Calendar</h1>
<?php echo $form->create('User', array('action' => 'login'));?>
	<fieldset>
 		<legend><?php __('Login');?></legend>
<?php	
	
		
		echo $form->input('username',array('tabindex'=>'1','class'=>'inputUserName','label'=>array('text'=>'Username','class'=>'notNeeded'),'div'=>array('class'=>'grid_8 alpha vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1')));
		
		echo $form->input('password',array('tabindex'=>'2','class'=>'inputPassword','label'=>array('text'=>'Password','class'=>'notNeeded'),'div'=>array('class'=>'grid_8 alpha vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 required')));
	
		echo '<div class="clear"></div>';
		echo $form->input('remember', array('tabindex'=>'3',"type" => "checkbox", 'label'=>array('text'=>'Remember Me','class'=>'remember'),'div'=>array('class'=>'grid_3 alpha vPaddingTop_1 vPaddingBottom_1 vMarginBottom_1 smallCheckbox')));
	

?>
</fieldset>
    
    	<?php echo $form->submit('Submit',array('tabindex'=>'4', 'div'=>array('class'=>'grid_8 vMarginTop_1'),'class'=>'button submitBtn'));?>
<?php echo $form->end();?>
    </div>

