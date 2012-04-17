<?php $javascript->link('eventForm-newJs', false); ?>

<?php $javascript->codeBlock( '$(document).ready(function(){
	if ('.(isset($error)?"true":"false").') {
		hideAll();
		removeSelected();
		$("#detailsForm").fadeIn("slow");
		$("#option6 a").addClass("selected");
		if ($("#EventType").val() !=  1) {
			$("#indicator").animate({
			    marginTop: "205px"
			  }, 500 );
		} else {
			$("#indicator").animate({
			    marginTop: "252px"
			  }, 500 );
		}
	}
});' , array('inline'=>false)); ?>

<div class="grid_2 omega vMarginTop_0">
<?php
$pos = strpos($_SERVER["HTTP_REFERER"], $_SERVER["HTTP_HOST"]);
if($pos !== false) {
	echo "<a class='backBtn button' href='{$_SERVER["HTTP_REFERER"]}'>Back</a>";
}
?>
</div>

<style>
.timepicker {background-color:white !important; z-index:5;}
</style>


<div class="clear"></div>
<div class="vMarginTop_2">

<h2 class="titles backendTitles vPaddingBottom_0">
<?php
if ($this->params["action"] == "add") { echo "Add an Event";}  
else { echo "Edit Event"; }
?>
<span class='superScript'> (* denotes required) </span>
</h2>


<?php echo $form->create('Event', array( "enctype" => "multipart/form-data", "url" => $html->url(null, true)) );?>
 
  
 
 

 

 

  <!--REPEAT -->	
 <div id="repeatForm" class="grid_10">
 	<div class='formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1' id='dateTimeFields'>
 	<?php
 	echo $form->input("repeats", array("tabindex"=>"16","type" => "hidden", "default" => 1));
 	$values = array();  
 	for ($y = 0; $y < 51; $y++) {$values[] = $y;}
 	$weekdays = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
 	for($x=1; $x <= 5; $x++) {
 		
 		echo "<div id='recursive_$x' class='".($x!=1?"hidden":"")."' >";
 		
 			echo "<hr class='dashed ".($x==1?"hidden":"")."' >";
 		
 			echo $form->input("repeatsType$x", array("type" => "hidden", "value" => "0"));
 			echo "<label class='vPaddingTop_2'>Recursion type:</label>";
 			echo "<ul class='catList catSpecial grid_8'>";
 			echo "<li id='noneButton_$x' class='calButton_".($x==1?"on":"off")."'>None</li> ";
	 		echo "<li id='dayButton_$x' class='calButton_off'>Day</li>  ";
	 		echo "<li id='weekButton_$x' class='calButton_off'>Week</li> ";
	 		echo "<li id='monthButton_$x' class='calButton_off'>Month</li>  ";
	 		echo "<li id='yearButton_$x' class='calButton_off'>Year</li>";
	 		echo "</ul>";
	 		echo "<div class='clear'></div>";	
	 		
	 		
	 		echo "<div class='vPaddingTop_1'>";
	 			echo "<label id='dateParameter_1_$x' class='hidden titles' >Repeat every: </label>";
	 			echo $form->select("dateParameter_1_$x", $values, null, array('class' => "hidden"), false);
	 		
	 			echo "<label id='dateParameter_2_$x' class='hidden titles'> day(s).</label>";
	 		echo "</div>";
	 		
	 		echo "<div id='weekFields_$x' class='hidden'  >";
	 			echo "<label >Repeat every</label>";
	 			echo "<div>";
		 		for($y = 0; $y <= 6; $y++) {
		 			echo $form->input(($weekdays[$y] . "_" . $x), array( "label" => $weekdays[$y],  "type" => "checkbox", "name" => "data[Event][{$weekdays[$y]}][$x]" ));
		 		}
	 			echo "</div>";
		 	echo "</div>";
		 	
		 	echo "<div class='clear'></div>";	
	 		echo "<div class='vPaddingTop_1 vPaddingBottom_2'>";
	 			echo "<label id='dateParameter_3_$x' class='hidden'>For the next </label>";
	 			echo $form->select("dateParameter_2_$x", $values, null, array('class' => "hidden"), false);
	 			echo "<label id='dateParameter_4_$x' class='hidden'> day(s). </label>";
			echo "</div>";	 	
	 		
	 		
 		echo "</div>";
 		
 	} ?> 	
 	</div>

 	<div class='formDiv_general vPaddingTop_1 vPaddingBottom_1 grid_5 alpha'>
 		<ul class="catList ">
	 		<li id='addRepeatOption' ><a class="calButton_on">More Repetition</a></li>
	 		<li id='subtractRepeatOption' ><a class="calButton_on">Less Repetition</a></li>
 		</ul>
 		
 	</div>
 
 
 
 
 

<div class="clear"></div>
