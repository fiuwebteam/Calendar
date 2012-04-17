<?php $javascript->link('eventFormJs', false); ?>

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
<div class="grid_3 alpha sideForm">
  
  <div id="indicator"></div>
  
  
   
  <div class='clear'></div>
  <ul>
  	<li id='option1'><a class="selected" href="#">Calendar</a></li>
  	<li id='option2'><a href="#">Event Type</a></li>
  	<li id='option3'><a href="#">Category</a></li>
  	<li id='option4'><a href="#">Date and Time</a></li>
  	<li id='option5'><a href="#">Repetition</a></li>
  	<li id='option6' class="lastItem"><a href="#">Details</a></li>
  </ul>
</div>


<div class="grid_12 form addForm">

<!--1. CALENDAR -->
 <div id="calendarForm" class="grid_8">
 	<div class="alpha info grid_10 content-container vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1">
 		<p><strong>Choose a calendar</strong> for this event.</p>
 		<blockquote>Once you have finished creating this event, it will be shown in each of it's parents all the way up to the <em>Main Calendar</em>.</blockquote>
 	</div>
 	<div class="clear"></div>
 	<?php echo $form->input('Calendar', array("tabindex"=>"1", "type" => "select", "options" => $chooser->arrayOutput($calendars, true), 'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1') ) ); ?>
 </div>
 
<!--2. TYPE --> 
 
 <div id="typeForm" class="grid_3">
 	<div class="alpha info grid_10 content-container vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1">
 	<p>The <strong>type of event</strong> can be the following choices:</p>
 	
 	<blockquote>
 		<ul class="vMarginTop_0">
 			<li><strong>Normal</strong> - A normal event is one in which there is a start and end time. This is the most common type</li> 
 			<li><strong>Ongoing</strong> - Ongoing events don't have a specific start time as they can run for multiple days. Example: Spring Break</li>
 			<li><strong>Deadline</strong> - Deadlines are events that have a sole time entry. Paying a fee for example.</li>
 		</ul>
 		</blockquote>
 	
 	</div>
 	
 	<div class="clear"></div>
 	<?php echo $form->input('type', array("tabindex"=>"2", 'label' => 'Event Type: ', 'options' => array( '', 'normal', 'ongoing', 'deadline'), 'div'=>array('id' => 'typeChooserDiv', 'class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1'))); ?>
 </div>
 
 <!--3. CATEGORY -->
 <div id="catForm" class="grid_4">
 	<div class="alpha info grid_10 content-container vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1">
 		<p><strong>Choose a category.</strong></p>
 		<blockquote>Whether the item is an event or a deadline, users can effectively filter items based on the type of category. So choose wisely.</blockquote>
 	</div>
 	<div class="clear"></div>
 	
 	<?php echo $form->input('category_id', array("tabindex"=>"3", 'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1'))); ?>
 </div>




<!--4. DATE AND TIME -->
 <div id="dateTimeForm" class="grid_14 formDiv_general vPaddingBottom_1 vPaddingTop_1 vMarginBottom_1">
 	
 	
 	<div class="clear"></div>
 	<div class="vPaddingTop_1 grid_14   vMarginBottom_1" id="dateTimeFields">
	 	<div class="grid_7 alpha" id="startDate">
	 		<span class="titles">Start Date:</span>
	 	
	 	<?php 
	 	echo '<LABEL FOR="EventStartMonth">Month</LABEL>';
	 	echo $form->month("EventStart", ( isset($this->data["Event"]["start"]["month"]) ? $this->data["Event"]["start"]["month"] : date("m") ) , array("tabindex"=>"4", 'name' => 'data[Event][start][month]'), false);
	 	
	 	echo '<LABEL FOR="EventStartDay">Day</LABEL>';
	 	echo $form->day("EventStart", ( isset($this->data["Event"]["start"]["day"]) ? $this->data["Event"]["start"]["day"] : date("d") ), array("tabindex"=>"5",'name' => 'data[Event][start][day]'), false);
	 	
	 	echo '<LABEL FOR="EventStartYear">Year</LABEL>';
	 	echo $form->year("EventStart", (date("Y") - 10), (date("Y") + 25), ( isset($this->data["Event"]["start"]["year"]) ? $this->data["Event"]["start"]["year"] : date("Y") ), array("tabindex"=>"6",'name' => 'data[Event][start][year]'), false);
	 	?>
	 	</div>
	 	
	 	<div class="grid_7 alpha omega" id="endDate">

	 		<span class="titles" id="endDate">End Date:</span>
		
		<?php
			echo '<LABEL FOR="EventStartMonth">Month</LABEL>';
		echo $form->month("EventEnd", ( isset($this->data["Event"]["end"]["month"]) ? $this->data["Event"]["end"]["month"] : date("m") ), array("tabindex"=>"10",'name' => 'data[Event][end][month]'), false);
			echo '<LABEL FOR="EventStartDay">Day</LABEL>';
	 	echo $form->day("EventEnd", ( isset($this->data["Event"]["end"]["day"]) ? $this->data["Event"]["end"]["day"] : date("d") ), array("tabindex"=>"11",'name' => 'data[Event][end][day]'), false);
	 		echo '<LABEL FOR="EventStartYear">Year</LABEL>';
	 	echo $form->year("EventEnd", (date("Y") - 10), (date("Y") + 25), ( isset($this->data["Event"]["end"]["year"]) ? $this->data["Event"]["end"]["year"] : date("Y") ), array("tabindex"=>"12",'name' => 'data[Event][end][year]'), false);
	 	?>
	 	</div>
	 	
	 	<div class="clear"></div>
	 	
	 	
	 	
	 	<div class="grid_5 alpha vMarginTop_4" id='startTime'>
	 	<span class="titles" id = 'startTimeLabel' >Start Time </span>
	 	<?php 
	 	echo $form->hour("EventStart", false, ( isset($this->data["Event"]["start"]["hour"]) ? $this->data["Event"]["start"]["hour"] : date("h") ), array("tabindex"=>"7",'name' => 'data[Event][start][hour]'), false);
	 	echo $form->minute("Start", (isset($this->data["Event"]["start"]["minute"]) ? $this->data["Event"]["start"]["minute"] : date("i") ), array("tabindex"=>"8",'name' => 'data[Event][start][minute]', "interval" => 5), false);
	 	echo $form->meridian("Start", (isset($this->data["Event"]["start"]["meridian"]) ? $this->data["Event"]["start"]["meridian"] : date("a") ), array("tabindex"=>"9",'name' => 'data[Event][start][meridian]'), false);
	 	?>
	 	</div>
	 	
	 	
	 	<div class="grid_5 omega vMarginTop_4" id='endTime'>
	 	<span class="titles" id = 'endTimeLabel' >End Time </span>
		<?php 
	 	echo $form->hour("EventEnd", false, ( isset($this->data["Event"]["end"]["hour"]) ? $this->data["Event"]["end"]["hour"] : date("h") ), array("tabindex"=>"13",'name' => 'data[Event][end][hour]'), false);
	 	echo $form->minute("End", (isset($this->data["Event"]["end"]["minute"]) ? $this->data["Event"]["end"]["minute"] : date("i") ), array("tabindex"=>"14",'name' => 'data[Event][end][minute]', "interval" => 5), false);
	 	echo $form->meridian("End", (isset($this->data["Event"]["end"]["meridian"]) ? $this->data["Event"]["end"]["meridian"] : date("a") ), array("tabindex"=>"15",'name' => 'data[Event][end][meridian]'), false);
	 	
	 	echo $javascript->codeBlock('
		var opts = {                            
			formElements:{"EventStartYear":"y","EventStartMonth":"m","EventStartDay":"d"},
			highlightDays:[0,0,0,0,0,0,0]      
		};
		datePickerController.createDatePicker(opts);
		timePicker("StartMeridian", "EventStartHour", "StartMin", "StartMeridian");
		
		var opts = {                            
			formElements:{"EventEndYear":"y","EventEndMonth":"m","EventEndDay":"d"},
			highlightDays:[0,0,0,0,0,0,0]      
		};
		datePickerController.createDatePicker(opts);
		timePicker("EndMeridian", "EventEndHour", "EndMin", "EndMeridian");
		');
	 	
	 	?>
	 	</div>
 	</div> 	
 	
 </div>
 	
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
 </div>
 
 
 <!--5. CALENDAR -->
 
 <div id="detailsForm" class="grid_10">
 	<?php echo $form->input('title',array("tabindex"=>"17",'class'=>'inputTitle','label'=>array('text'=>'Title &#42;','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 required'))); ?>
	<div class='clear'></div>
	<?php echo $form->input('description',array( "tabindex"=>"18",'cols'=>'57','rows'=>'20','class'=>'inputDesc','label'=>array('text'=>'Description (If featured, only the first 300 characters will display) &#42;','class'=>'notNeeded',),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 required'))); ?>
	<div class='clear'></div>
	<?php echo $form->input('location',array("tabindex"=>"19",'class'=>'inputLocation','label'=>array('text'=>'Location &#42;','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 required'))); ?>
	<div class='clear'></div>
	<?php echo $form->input('contact',array("tabindex"=>"20",'class'=>'inputContact','label'=>array('text'=>'Contact &#42;','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1 required'))); ?>
	<div class='clear'></div>
	<?php echo $form->input('email',array("tabindex"=>"21",'class'=>'inputEmail','label'=>array('text'=>'E-mail','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1'))); ?>
	<div class='clear'></div>
	<?php echo $form->input('phone',array("tabindex"=>"22",'class'=>'inputPhone','label'=>array('text'=>'Phone','class'=>'notNeeded'),'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1'))); ?>
	<div class='clear'></div>
	<?php echo $form->input('url', array( "tabindex"=>"23",'class'=>'inputURL', 'label'=> array('text'=>'URL','class'=>'notNeeded'), 'div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1'))); ?>
	<div class='clear'></div>
	<?php echo $form->input('Tag', array("tabindex"=>"24",'class'=>'inputTag','type' => 'text','label' => 'Tags (seperate each tag with a comma)','div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1'))); ?>
	<div class='clear'></div>
	
	<div class="alpha info grid_10 content-container vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1">
 		<p>
 			To be considered for a featured event you must include a flyer image for your listing.<br/>
 			<span style='font-size:14px; font-style:italic'>Need to edit an image but don't have photoshop? Use <a href='Http://www.picnik.com'>Picnik</a></span>
 		</p> 		
 	</div>
 	<div class="clear"></div>

	<?php echo $form->input('flyer', array("tabindex"=>"25",'class'=>'inputTag','type' => 'file','label' => 'Flyer Image  (330px X 136 px, no more than 40KB)','div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1'))); ?>
	
	<?php if (isset($this->data["Event"]["flyer"])) { echo $html->image('flyers/' . $this->data['Event']['flyer'], array("width" => 33, "height" => 13) );}?>
	
	<div class='clear'></div>
	
	<?php echo $form->input('thumbnail', array("tabindex"=>"26",'class'=>'inputTag','type' => 'file','label' => 'Thumbnail Image  (69x69, no more than 15KB, for embeds only)','div'=>array('class'=>'formDiv_general vPaddingTop_1 vPaddingBottom_3 vMarginBottom_1'))); ?>
	
	<?php if (isset($this->data["Event"]["thumbnail"])) { echo $html->image('thumbnails/' . $this->data['Event']['thumbnail'], array("width" => 15, "height" => 15) );}?>
	
	<?php echo $form->submit('Submit',array('div'=>array('class'=>'grid_8 vMarginTop_1'),"tabindex"=>"27", 'class'=>'button submitBtn'));?>
 </div>
 
<div class="clear"></div>
 <div class="sideFormArrows formDiv_general grid_3 vMarginTop_2 vPaddingTop_2 vPaddingBottom_2">
  <a href="#" id="featurePrev" class="boxes prev grid_1" rel="Previous Feature Event">Prev</a>
  <a href="#" id="featureNext" class="boxes next grid_1" rel="Next Feature Event">Next</a>
  </div>
  <div class="clear"></div>
</div>

<div class="clear"></div>




</div>

<div class="clear"></div>
