<div class="grid_2 omega vMarginTop_0">
	<?php
		$pos = strpos($_SERVER["HTTP_REFERER"], $_SERVER["HTTP_HOST"]);
			if($pos !== false) {
					echo "<a class='backBtn button' href='{$_SERVER["HTTP_REFERER"]}'>Back</a>";
			}
	?>
</div>


<div class="clear"></div>

<div id ='plugForm' class="vMarginTop_2 form grid_16">


	 <h2 class="titles" id='EmbedTitle'>Embed the <?= $calendarTitle ?></h2>
		<?php 
			echo $form->create("Plug", array('url' => $html->url(array('calendar' => $this->params["named"]["calendar"]), true)));
		?>
		
		<?php
			echo $form->input('date', array('tabindex'=>'1','div'=>array('class'=>'grid_4  alpha vPaddingTop_1 vPaddingBottom_1 vMarginBottom_1 required'),'label' => 'Do you want the date included?', 'options' => array('n'=> 'No', 'y' => 'Yes')));
		?>
		
		
		<div class="clear"></div>

		<?php
			echo $form->input('count', array('tabindex'=>'2','label' => 'Enter the number of items to be displayed <br />(enter 0 to show all available).', 'value' => '0','div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 grid_7 required alpha')));
		?>
		
		
		<div class="clear"></div>
		
		<?php
			echo $form->input('desc', array('tabindex'=>'3','label' => 'Show/Hide item descriptions <br /> (0=no descriptions; 1=show full description text; <br />n>1 = display first n characters of description) ?' , 'value' => '0','div'=>array('class'=>' alpha vPaddingTop_1 vPaddingBottom_1 vMarginBottom_1 required grid_7')));
		?>
		
		
		<div class="clear"></div>
		
		<?php
			echo $form->input('target', array('tabindex'=>'4', 'div'=>array('class'=>'grid_4  vPaddingTop_1 vPaddingBottom_1 vMarginBottom_1 required alpha'),'label' => 'Target links in the new window?', 'options' => array('n'=> 'No', 'y' => 'Yes')));
			
			
			echo '<div class="clear"></div>';
			
			
			echo $form->input('link', array('tabindex'=>'5','div'=>array('class'=>'grid_4  alpha vPaddingTop_1 vPaddingBottom_1 vMarginBottom_1 required'),'label' => 'Would you like the embedded events to actively link to the URL that was provided?', 'options' => array('n'=> 'No', 'y' => 'Yes')));
			
			echo '<div class="clear"></div>';
			
			echo $form->input("calendar", array("type" => "hidden", "value" => $this->params["named"]["calendar"]));
			echo '</fieldset>';
			
			
			echo $form->submit('Submit',array('div'=>array('class'=>'grid_8 vMarginTop_1'),'class'=>'button submitBtn'));
			echo "</form>";
			?>
		<?php if ($output != "") {?>
		<div class="clear"></div>
		
		<div class="grid_16 embedCode vMarginTop_2">
		
		
		<hr class="dashed vPaddingTop_2"/>
		<p>Copy and Paste this into your website:</p>
			<textarea class="inputDesc" tabindex="5" rows="3" cols="104">
				&#60; script type="text/javascript" src="<?= $html->url(array("controller" => "events", "action" => "play", $output), true); ?>" &#62; &#60; /script &#62;
			</textarea>
		</div>
		<?php } ?>
 
</div>