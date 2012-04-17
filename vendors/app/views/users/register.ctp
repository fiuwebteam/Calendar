<?= $javascript->codeBlock(
<<<EOD

$(document).ready(function(){
	$(".supplement").fadeTo(0, 0.5);
	supplementArray = $(".supplement");
	$("#UserUsername").focus(function(){
		$(".supplement").fadeTo(0, 0.5);
		supplementArray[0].setAttribute("style", "opacity:1");
	});
	$("#UserPassword").focus(function(){
		$(".supplement").fadeTo(0, 0.5);
		supplementArray[1].setAttribute("style", "opacity:1");
	});
	$("#UserPasswordConfirm").focus(function(){
		$(".supplement").fadeTo(0, 0.5);
		supplementArray[2].setAttribute("style", "opacity:1");
	});
	$("#UserEmail").focus(function(){
		$(".supplement").fadeTo(0, 0.5);
		supplementArray[3].setAttribute("style", "opacity:1");
	});
	$("#UserFirstName").focus(function(){
		$(".supplement").fadeTo(0, 0.5);
		supplementArray[4].setAttribute("style", "opacity:1");
	});
	$("#UserLastName").focus(function(){
		$(".supplement").fadeTo(0, 0.5);
		supplementArray[5].setAttribute("style", "opacity:1");
	});
});

EOD
,array('inline'=>false));
?>

<div class="users form grid_16">
<h1>Registration for FIU's University Calendar</h1>
<p>Fill out this one-step form to begin adding events.</p>
<?php echo $form->create('User', array('action' => 'register'));?>
	<fieldset>
 		<legend><?php __('User Registration');?></legend>
	<?php	
		
		echo $form->input('username',array('tabindex'=>'1', 'class'=>'inputUserName','label'=>array('text'=>'Username','class'=>'notNeeded'),'div'=>array('class'=>'grid_8 alpha vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1')));
			
		echo '<p class="grid_7 supplement box-highlight omega">Must be at least 4 characters, lowercase letters and numbers only.
			<span class="error">this is not correct</span>
		</p>';
		
		echo $form->input('password',array('tabindex'=>'2', 'class'=>'inputPassword','label'=>array('text'=>'Password','class'=>'notNeeded'),'div'=>array('class'=>'grid_8 alpha vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 required')));
			
		echo '<p class="grid_7 box-highlight supplement omega "> Use upper and lower case characters, numbers and symbols like !"$%^&amp;" in your password."</p>';
		
		echo $form->input('password_confirm',array('tabindex'=>'3',  'type' => 'password', 'class'=>'inputPassword','label'=>array('text'=>'Confirm Password','class'=>'notNeeded'),'div'=>array('class'=>'grid_8 alpha vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 required')));
		
		echo '<p class="grid_7 box-highlight supplement omega "> Use upper and lower case characters, numbers and symbols like !"$%^&amp;" in your password."</p>';
		
		echo $form->input('email',array('tabindex'=>'4', 'class'=>'inputEmail','label'=>array('text'=>'E-Mail','class'=>'notNeeded'),'div'=>array('class'=>'grid_8 alpha vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1')));
		
		echo '<p class="grid_7 box-highlight  supplement  omega"> We send important administration notices to this address so triple-check it.</p>';
		
		echo $form->input('first_name',array('tabindex'=>'5', 'class'=>'inputFName','label'=>array('text'=>'First Name','class'=>'notNeeded'),'div'=>array('class'=>'grid_8 alpha vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1')));
		echo '<p class="grid_7 box-highlight supplement omega"> First Name Please</p>';
		
		echo $form->input('last_name',array('tabindex'=>'6', 'class'=>'inputLName','label'=>array('text'=>'Last Name','class'=>'notNeeded'),'div'=>array('class'=>'grid_8 alpha vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1')));
		echo '<p class="grid_7 supplement box-highlight omega"> Last Name Please</p>';		
	?>
	
	</fieldset>
	<?php echo $form->submit('Submit',array('tabindex'=>'7', 'div'=>array('class'=>'grid_8 vMarginTop_1'),'class'=>'button submitBtn'));?>
<?php echo $form->end();?>
</div>
