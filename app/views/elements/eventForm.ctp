<?php $javascript->link('eventForm-newJs', false); ?>
<?php $javascript->link('jquery-ui-1.8.16.custom.min', false); ?>
<?php $javascript->link('slidingForm', false);  ?>
<?php $javascript->link('jquery.simplemodal', false);?>
<?php echo $html->css('/css/ui-lightness/jquery-ui-1.8.16.custom.css'); ?>

    <style>
        span.reference{
            position:fixed;
            left:5px;
            top:5px;
            font-size:10px;
            text-shadow:1px 1px 1px #fff;
        }
        span.reference a{
            color:#555;
            text-decoration:none;
			text-transform:uppercase;
        }
        span.reference a:hover{
            color:#000;
            
        }

    </style>
    
    <style>
 

	p.label_checkbox_pair {
	clear: both;
	float: none;
	}
	p.label_checkbox_pair input {
	clear: left;
	float: left;
	margin-left: 80px;
	}
	p.label_checkbox_pair label {
	clear: left;
	display: block;
	float: left;
	margin-left: 10px;
	width: 200px;
	}
	</style>
	
	<style>
	
	fieldset.ends label{
	color:red;
	display:block;
	float:left;
	margin-left:10px;
	width:50px;
	
	}
	
	.ends input.radio{
	float:left;
	clear:left;
	}
	
	.ends input.leftinput{
	float: left;
	}
	
	.ends input.occurrences{
	width: 25px;
	margin-right: 10px;
	}
	
	#steps input.occurrences{
	width: 15px;
	margin-right: 5px;
	margin-left: 10px;
	}

	
	.repeat-content-type{
	display:none;
	margin-top:0 20px 0 20px;
	}
	
	.dayBoxes div{
	float: left;
	}
	
	.dayBoxes div input{
	float: left;
	margin-right: 5px;
	}
	
	.dayBoxes label{
	float:left;
	margin-right: 10px;
 

	}
	
.ef_label{ 
 	width: 70px;
    color: black;
    float: left;
    margin-top: 0px;
    margin-bottom: 0px;
    line-height: 40px;
}	
 
</style>
 

 
<?php 

//move some data from php to js for event view edit prepopulation

if ($this->params['action'] == 'edit'){
      $jdata = array();
      $jdata['editView'] = true;
      $jdata['type']      =  $this->data['Event']['type'];
      $jdata['EventRepeatsType']  =  $this->data['Event']['EventRepeatsType1']  + 1;
      $jdata['dateParameter_1_1'] =  $this->data['Event']['dateParameter_1_1'];   
	  $jdata_enc = json_encode($jdata);
}	else {
		$jdata['edit'] = false;
		$jdata_enc = json_encode($jdata);
	}
	echo "<script> var jdata=".htmlspecialchars($jdata_enc, ENT_NOQUOTES, 'utf-8')."</script>";  
     
  
$error = isset($error)?"true":"false";
// TODO: Kunle, make sure to stick this code in the eventForm JS 
// when you're done playing with it. The only thing that needs to 
// be here is the error catching code.
$js = <<<EOF
$(document).ready(function(e){

    
$("#repeatDetails2").children().hide(); 
$("#repeatDetails").hide();
    $("#EventRepeatsType").change(function(){
    	 if( $(this).val()   == 1 ){  
    	 	$("#repeatDetails2").children().hide(); 
    	 	$("#repeatDetails").hide(); 
    	 }
    	 else if( $(this).val()   == 2 )
    		 { $("#repeatDetails2").children().hide(); 
    		   $("#repeatDetails").show(); 
    	 	   $("#repeat-1").show(); 
    	 	  }
    	 	  else if ( $(this).val()   == 3 ) 
    		  	{ $("#repeatDetails2").children().hide();
    			  $("#repeatDetails").show();  
    	 	  	  $("#repeat-2").show(); 
    	 	  	}
    	 	  else if ( $(this).val()   == 4 )
    			{ $("#repeatDetails2").children().hide(); 
    			  $("#repeatDetails").show(); 
    	 	  	  $("#repeat-3").show(); 
    	 	  	}
    	if( $(this).val() != 1 ){  
    	 	$("#endDate").css("visibility", "hidden");
    	 	$("#endDateLabel").css("visibility", "hidden");
    	 } else {
    	 	$("#endDate").css("visibility", "visible");
    	 	$("#endDateLabel").css("visibility", "visible");
    	 }
 });
    
    
    
    
 
	
	
	$("#EventTimeOptions3").click(function(e){ //all day
		 		
	 
       		 disableStartEndTime();
       		 $("#startTime").hide();
       		 $("#startTimeLabel").hide();
       		 $("#endTime").hide();   
       		 $("#endTimeLabel").hide();    
	});
	
	
	$("#EventTimeOptions2").click(function(e){ //no end time
		 		
	 
       		 disableEndTime();
       	 	 $("#endTime").hide();
       	 	 $("#endTimeLabel").hide(); 
       	 	 enableStartTime();  
       	 	 $("#startTime").show(); 
       	 	 $("#startTimeLabel").show();
       	 	
       	 	     
	});
	
	
    $("#EventTimeOptions1").click(function(e){ //normal
		 		
	 
       		 enableStartEndTime();
       		 $("#startTime").show();
       		 $("#startTimeLabel").show();
       		 $("#endTime").show();
       		 $("#endTimeLabel").show(); 
       		        
	});
	
	

	
	    function disableStartEndTime(){
        	$("#EventStartHour").attr('disabled','disabled');
        	$("#EventStartMin").attr('disabled','disabled');
        	$("#EventStartMeridian").attr('disabled','disabled');
        
      		$("#EventEndHour").attr('disabled','disabled');
        	$("#EventEndMin").attr('disabled','disabled');
        	$("#EventEndMeridian").attr('disabled','disabled');
        	
        	return true;    
        
 		}
 		
 		function enableStartEndTime(){
 			$("#EventStartHour").removeAttr('disabled');
        	$("#EventStartMin").removeAttr('disabled');
       	 	$("#EventStartMeridian").removeAttr('disabled');
        
        	$("#EventEndHour").removeAttr('disabled');
        	$("#EventEndMin").removeAttr('disabled');
        	$("#EventEndMeridian").removeAttr('disabled');
        	
        	return true;
        }
        
        
         function disableEndTime(){
       
      		$("#EventEndHour").attr('disabled','disabled');
        	$("#EventEndMin").attr('disabled','disabled');
        	$("#EventEndMeridian").attr('disabled','disabled');
        	
        	return true;    
        
 		}

        function enableEndTime(){
 	        
        	$("#EventEndHour").removeAttr('disabled');
        	$("#EventEndMin").removeAttr('disabled');
        	$("#EventEndMeridian").removeAttr('disabled');
        	
        	return true;
        }

        
        function enableStartTime(){
 			$("#EventStartHour").removeAttr('disabled');
        	$("#EventStartMin").removeAttr('disabled');
       	 	$("#EventStartMeridian").removeAttr('disabled');
        	
        	return true;
        }
        
        
        
	
	    function disableEndDate(){
        	$("#EventEndYear").attr("disabled","disabled");
        	$("#EventEndMonth").attr("disabled","disabled");
        	$("#EventEndDay").attr("disabled","disabled");
        	return true;
       	 }

        
        
      function enableEndDate(){
       		$("#EventEndYear").removeAttr("disabled");
        	$("#EventEndMonth").removeAttr("disabled");
       		$("#EventEndDay").removeAttr("disabled");
       		return true;
       }

	
	
 
  //submit 
       $("#addEventButton").click(function(e){
          	if ( $("#EventUrl").val() == 'http://' )
     		 	$("#EventUrl").val('');
     	
      if( $('#formElem').data('errors') ){
			alert('Please correct the errors in the Form');
     		return false;
     		}
     		          	
         	$('#formElem').submit();
         	return true;
         });
       
  	    //end submit
   
      
          

   	$(function() {
		
 		$( "#dailyStartDate" ).datepicker();
 		$( "#monFriStartDate" ).datepicker();
 		$( "#specificStartDate" ).datepicker();
 		$( "#weeklyStartDate" ).datepicker();
 		$( "#monthlyStartDate" ).datepicker();
        $( "#yearlyStartDate" ).datepicker();

	});
 
 
	

	
//StartDate JqueryUI datepicker 
$('#selectedDatepicker').datepicker({
    changeMonth: true,
	changeYear: true,
    beforeShow: readSelected, onSelect: updateSelected, 
    minDate: new Date(2001, 1 - 1, 1), maxDate: new Date(2013, 12 - 1, 31), 
    showOn: 'button', buttonImageOnly: true, buttonImage: '/img/little-cal.png'}); 
     
// Prepare to show a date picker linked to three select controls 
function readSelected() { 
    $('#selectedDatepicker').val($('#EventStartMonth').val() + '/' + 
        $('#EventStartDay').val() + '/' + $('#EventStartYear').val()); 
    return {}; 
} 
 
// Update three select controls to match a date picker selection 
function updateSelected(date) { 
    $('#EventStartMonth').val(date.substring(0, 2)); 
    $('#EventStartDay').val(date.substring(3, 5)); 
    $('#EventStartYear').val(date.substring(6, 10)); 
}


//EndDate JqueryUI datepicker 
$('#EndDatePicker').datepicker({
    changeMonth: true,
	changeYear: true,
    beforeShow: readSelectedE, onSelect: updateSelectedE, 
    minDate: new Date(2001, 1 - 1, 1), maxDate: new Date(2013, 12 - 1, 31), 
    showOn: 'button', buttonImageOnly: true, buttonImage: '/img/little-cal.png'}); 
     
// Prepare to show a date picker linked to three select controls 
function readSelectedE() { 
    $('#EndDatePicker').val($('#EventEndMonth').val() + '/' + 
        $('#EventEndDay').val() + '/' + $('#EventEndYear').val()); 
    return {}; 
} 
 
// Update three select controls to match a date picker selection 
function updateSelectedE(date) { 
    $('#EventEndMonth').val(date.substring(0, 2)); 
    $('#EventEndDay').val(date.substring(3, 5)); 
    $('#EventEndYear').val(date.substring(6, 10)); 
}

 
 if (jdata["editView"]){
	       
      if ( jdata['type']  > 1 ){
        stime= $("#EventStartHour").val()+$("#EventStartMin").val()+$("#EventStartMeridian").val();

       	  if (stime != "120am"){
       	 	jdata['type'] = 2
       	 	}
       	 	 else jdata['type'] = 3;   
       }
      	$("#EventTimeOptions"+jdata['type']).click();  
		  
		$("#EventRepeatsType").val(jdata['EventRepeatsType']);
		
		$("#EventRepeatsType").change();
		
		$("#EventRepeatVariable").val(jdata['dateParameter_1_1']);
		$("#EventRepeatVariable").change();
	  }

 
}); 

EOF;
$javascript->codeBlock( $js, array('inline'=>false));
?>



<div class="grid_2 vMarginTop_1">
<?php
$pos = strpos($_SERVER["HTTP_REFERER"], $_SERVER["HTTP_HOST"]);
if($pos !== false) {
	echo "<a class='btn' href='{$_SERVER["HTTP_REFERER"]}'>&#9668; Back</a>";
}
?>
</div>

  <div class="clear"></div>
  <h1 class="titles" >Create an Event</h1>

<?php echo $form->create('Event', array( "enctype" => "multipart/form-data", "url" => $html->url(null, true), 'id'=>'formElem','name'=>'formElem') );?>

        <div id="addEventContent">
         
            <div id="addEventWrapper">
             <div id="addEventNavigation" style="display:none;">
                    <ul>
                        <li class="selected">
                            <a href="#">1. Choose Calendar</a>
                        </li>
                        <li>
                            <a href="#">2. Select Category</a>
                        </li>
                        <li>
                            <a href="#">3. Enter Date &amp; Time</a>
                        </li>
                        <li>
                            <a href="#">4. Enter Details</a>
                        </li>
						<li>
                            <a href="#">5. Confirm</a>
                        </li>
                    </ul>
                </div>
                <div id="steps">
                     
                        <fieldset class="step">
                            <legend>1. Choose Calendar</legend>
                            <div class="addEventFieldWrapper">
                             
                                <?php  echo $form->input('Calendar', array("tabindex"=>"1", "type" => "select", "options" => $chooser->arrayOutput($calendars, true),'class'=>'calselect','label'=>false) ); ?>

                      
                            </div>
                            <p class="addEventInformation"><strong>Choose Calendar</strong><br /> Once you have finished creating this event, it will be shown in each of it's parents all the way up to the Main Calendar.</p>
                            
                        </fieldset>
                        <fieldset class="step">
                            <legend>2. Select Category</legend>
                            <div class="addEventFieldWrapper">
                            	<?php echo $form->input('category_id', array(  "class"=>"form-width","label"=>false)); ?>
							 </div>
                                 
 	  <p class="addEventInformation"><strong>Select Category</strong><br /> 
 	 users can effectively filter items based on the type of category. So choose wisely.</p>

                     
                        </fieldset>
                <!--start enter date and time -->        
                        <fieldset class="step">
                            <legend>3. Enter Date &amp; Time</legend>
                                                 
                           <div class="addEventFieldWrapper" style="width:720px;">
                           
  
   <div id="options" style="color: black;">
 
     <div class="grid_7 alpha omega vMarginBottom_1"> 
     Time Options:
    <?php
    $options=array('1'=>'Normal','2'=>'No End Time', '3'=>'All Day');
    $attributes=array('legend'=>false,'class'=>'occurrences', 'default' => "1");
    echo $form->radio('timeOptions',$options,$attributes);
    ?>
	</div>
     <div class="clear"></div>
    <div class="grid_5 alpha omega" id="repeatToggleDiv" style="color: black;">
   Repeat Options:    
     <?php 
      $numbers=array(); for ($x=1;$x<=10;$x++) {$numbers[$x]=$x;} 
     echo $form->select("repeatsType", array('1'=>'One-time Event','2'=>'Every day','3'=>'Weekly','4'=>'Monthly') ,null, array('class' => ""), false); ?>
     
	</div> 
	
	<?php
	/*
		$weekdays = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
	    for($y = 0; $y <= 6; $y++) {
	    	echo $form->input(($weekdays[$y] . "_1" ), 
	    		array(
	    			"type" => "hidden", 
	    			"name" => "data[Event][{$weekdays[$y]}][1]", 
	    			"id"=>"weekly-".$weekdays[$y], 
	    			'default'=>'1' 
	    		)
	    	);
	    }
	*/	 		
	?>
	
	
	<div id ="repeatDetails" class="grid_2 alpha omega">for  
	<?php echo $form->select("repeatVariable", $numbers ,null, 
		array(
			'class' => "",
			"style"=>"width:50px;"
		), false
	); ?> <div>
	

	
	</div> 
 
</div>      


	<div id="repeatDetails2" class="grid_3 alpha omega">	 
		<div id="repeat-1" style="float:left">days</div>
		<span id="repeat-2" style="float:left">weeks</span>
		<span id="repeat-3" style="float:left">months</span>
	</div>
	
	                  		                          	
   <div id="inputTopSection" class="grid_11 vMarginTop_1">
   		<div id="startItems">
          <div class="ef_label">Start Date:</div>	
          
       
          <div class="grid_5 alpha omega vMarginTop_0" id="startDate">
	 		
	<?php 
	 	echo '<LABEL FOR="EventStartMonth"></LABEL>';
	 	echo $form->month("EventStart", ( isset($this->data["Event"]["start"]["month"]) ? $this->data["Event"]["start"]["month"] : date("m") ) , array("tabindex"=>"4", 'name' => 'data[Event][start][month]', "class"=>"addEventDatTime"), false);
	 	
	 	echo '<LABEL FOR="EventStartDay"></LABEL>';
	 	echo $form->day("EventStart", ( isset($this->data["Event"]["start"]["day"]) ? $this->data["Event"]["start"]["day"] : date("d") ), array("tabindex"=>"5",'name' => 'data[Event][start][day]',"class"=>"addEventDatTime"), false);
	 	
	 	echo '<LABEL FOR="EventStartYear"></LABEL>';
	 	echo $form->year("EventStart", (date("Y") - 10), (date("Y") + 25), ( isset($this->data["Event"]["start"]["year"]) ? $this->data["Event"]["start"]["year"] : date("Y") ), array("tabindex"=>"6",'name' => 'data[Event][start][year]',"class"=>"addEventDatTime"), false);
	 	?>
	 	
	 	<input type="hidden" disabled="disabled" id="selectedDatepicker"  size="1">
	 	</div> 
	 	
	 	
 	<div class="ef_label" id="startTimeLabel">Start Time:</div>
	 	<div class="grid_4 alpha omega  vMarginTop_1" id='startTime'>
    
	 	<?php 
	 	echo $form->hour("EventStart", false, ( isset($this->data["Event"]["start"]["hour"]) ? $this->data["Event"]["start"]["hour"] : date("h") ), array("tabindex"=>"7",'name' => 'data[Event][start][hour]',"class"=>"addEventDatTime"), false);
	 	echo $form->minute("EventStart", (isset($this->data["Event"]["start"]["minute"]) ? $this->data["Event"]["start"]["minute"] : date("i") ), array("tabindex"=>"8",'name' => 'data[Event][start][minute]', "interval" => 5,"class"=>"addEventDatTime"), false);
	 	echo $form->meridian("EventStart", (isset($this->data["Event"]["start"]["meridian"]) ? $this->data["Event"]["start"]["meridian"] : date("a") ), array("tabindex"=>"9",'name' => 'data[Event][start][meridian]',"class"=>"addEventDatTime"), false);
	 	?>
	 	</div>
	 	
	</div>	 <!--end start items  -->	
 
	 	
	 	<div class="clear"></div>
 
 <div id="endItems" style="overflow:auto;margin-top:5px;">	
	 	
	 	<div class="ef_label" id='endDateLabel'>End Date:</div>	
	 	<div class="grid_5 alpha omega" id ="no-end-date" style="display:none">NO END DATE</div>
	 	<div class="grid_5 alpha omega" id="endDate">
        
	 	 
		
		<?php
			echo '<LABEL FOR="EventStartMonth"></LABEL>';
		echo $form->month("EventEnd", ( isset($this->data["Event"]["end"]["month"]) ? $this->data["Event"]["end"]["month"] : date("m") ), array("tabindex"=>"10",'name' => 'data[Event][end][month]',"class"=>"addEventDatTime"), false);
			echo '<LABEL FOR="EventStartDay"></LABEL>';
	 	echo $form->day("EventEnd", ( isset($this->data["Event"]["end"]["day"]) ? $this->data["Event"]["end"]["day"] : date("d") ), array("tabindex"=>"11",'name' => 'data[Event][end][day]',"class"=>"addEventDatTime"), false);
	 		echo '<LABEL FOR="EventStartYear"></LABEL>';
	 	echo $form->year("EventEnd", (date("Y") - 10), (date("Y") + 25), ( isset($this->data["Event"]["end"]["year"]) ? $this->data["Event"]["end"]["year"] : date("Y") ), array("tabindex"=>"12",'name' => 'data[Event][end][year]',"class"=>"addEventDatTime"), false);
	 	?>
	 		<input type="hidden" disabled="disabled" id="EndDatePicker" size="10">
	 	</div>
 
		<div class="grid_3 alpha omega" id ="no-end-time" style="display:none">NO END TIME</div>
	 	
	 	<div class="ef_label" id="endTimeLabel">End Time:</div>
	 	<div class="grid_4 alpha omega vMarginTop_1" id='endTime'>
		<?php 
	 	echo $form->hour("EventEnd", false, ( isset($this->data["Event"]["end"]["hour"]) ? $this->data["Event"]["end"]["hour"] : date("h") ), array("tabindex"=>"13",'name' => 'data[Event][end][hour]',"class"=>"addEventDatTime"), false);
	 	echo $form->minute("EventEnd", (isset($this->data["Event"]["end"]["minute"]) ? $this->data["Event"]["end"]["minute"] : date("i") ), array("tabindex"=>"14",'name' => 'data[Event][end][minute]', "interval" => 5,"class"=>"addEventDatTime", 'id'=>'EventEndMin'), false);
	 	echo $form->meridian("EventEnd", (isset($this->data["Event"]["end"]["meridian"]) ? $this->data["Event"]["end"]["meridian"] : date("a") ), array("tabindex"=>"15",'name' => 'data[Event][end][meridian]',"class"=>"addEventDatTime",'id'=>"EventEndMeridian"), false);
	?>
	 	</div>
	 	                                 
	 	</div> <!--end end items -->
	  
	  
	</div> 	<!--end top section -->
	
	 	  
 
   <div class="clear"></div>
   


	
 </div> 	

	 <div class="clear"></div>

 
     
 <!--repeat count -->
  	<?php /* echo $form->input("repeats", array("tabindex"=>"16","type" => "hidden", "default" => 1, 'id'=>"repeatsCount")); */?>


  	<?php /* echo $form->input("dateParameter_1_1", array("tabindex"=>"16","type" => "hidden", "default" => 1, 'id'=>"dateParameter1")); */?>

  
 
<!---end enter date & time --> 


                                               
                        </fieldset>
                        
                        <fieldset class="step">
                            <legend>4. Enter Details</legend>
       <div class="addEventFieldWrapper" style="width:720px;" >
                             
 <div id="detailsForm" style="width:720px;">
 
      <div style="float:left;width:auto;">      
 	<?php echo $form->input('title',array("tabindex"=>"17",'class'=>'','label'=>array('text'=>'Title &#42;<br/>','class'=>'eventFormLabel'),'div'=>array('class'=>'formDiv_general vMarginBottom_4 required inputTitle'))); ?>
	<div class='clear'></div>
		
	<?php echo $form->input('email',array("tabindex"=>"19",'class'=>'','label'=>array('text'=>'E-mail<br />','class'=>'eventFormLabel'),'div'=>array('class'=>'formDiv_general vMarginBottom_1 inputEmail'))); ?>
		<div class='clear'></div>
	 
	<?php echo $form->input('contact',array("tabindex"=>"21",'class'=>'','label'=>array('text'=>'Contact &#42;','class'=>'eventFormLabel'),'div'=>array('class'=>'formDiv_general vMarginBottom_4 vMarginTop_2 required inputContact'))); ?>
 </div>
		 

	<div style="float:left;width:250px;padding-left:10px;">
 
 	<?php echo $form->input('location',array("tabindex"=>"18",'class'=>'','label'=>array('text'=>'Location &#42;','class'=>'eventFormLabel'),'div'=>array('class'=>'formDiv_general   vMarginBottom_4 required inputLocation'))); ?>	
 
 <div class='clear'></div>
 
 <?php echo $form->input('phone',array("tabindex"=>"20",'class'=>'','label'=>array('text'=>'Phone<br/>','class'=>'eventFormLabel'),'div'=>array('class'=>'formDiv_general  vMarginBottom_1    inputPhone',))); ?>	
		<div class='clear'></div>
<?php echo $form->input('url', array( "tabindex"=>"22",'default'=>"http://",'class'=>'', 'label'=> array('text'=>'URL<br/>','class'=>'eventFormLabel'), 'div'=>array('class'=>'formDiv_general vMarginBottom_1 vMarginTop_2  inputURL'))); ?> 	
 
	
</div>
	<div class='clear'></div>
	
<div style="width:500px;margin-left:60px;">	
<span style="float:left">Description:</span>
	<div class='clear'></div>
 	<?php echo $form->input('description',array( "tabindex"=>"23",'class'=>'','label'=>array('text'=>'','class'=>'eventFormLabel',),'div'=>array('class'=>'vMarginBottom_4 required'))); ?>
 	
</div> 

<div class='clear'></div>					 
 
 <div style="width:220px;">
<span style="margin-left: 3.5em">Comma separated tags</span>
 <?php echo $form->input('Tag', array("tabindex"=>"24",'class'=>'','type' => 'text','label'=>array('text'=>'Tags','class'=>'eventFormLabel'),'div'=>array('class'=>'formDiv_general   vMarginBottom_4'))); ?>		

</div>

<div class='clear'></div>

	<span>To be considered for a featured event you must include a flyer image for your listing.<br />
Need to edit an image but don't have photoshop? Use <a target=_"blank" href="http://pixlr.com/express/">Pixlr</a></span>	
 	<div class='clear'></div>
 <div id="flyerfile" style="float:left;width:249px;margin-right:10px;">	
 
	
 <?php echo $form->input('flyer', array("tabindex"=>"25",'class'=>'','type' => 'file','label' => array('text'=>'Flyer','class'=>'eventFormLabel'),'div'=>array('class'=>'vMarginBottom_1'))); ?>
	
	<?php if (isset($this->data["Event"]["flyer"])) { echo $html->image('flyers/' . $this->data['Event']['flyer'], array("width" => 33, "height" => 13) );}?>
	 
</div>	 
<div style="float:left;margin-left:30px;">Must be less than 330px X 136px</div>
	 	
 <div class='clear'></div>
  <div id="thumbnailFile"  style="float:left;width:260x;margin-right:10px;">
	 
	<?php echo $form->input('thumbnail', array("tabindex"=>"26",'class'=>'eventFormLabel','type' => 'file','label' => array('text'=>'Thumbnail','class'=>'eventFormLabel'),'div'=>array('class'=>'vMarginBottom_1 inputTag '))); ?>
	
	<?php if (isset($this->data["Event"]["thumbnail"])) { echo $html->image('thumbnails/' . $this->data['Event']['thumbnail'], array("width" => 15, "height" => 15) );}?>
	 
</div>	
	 
<div style="float:left;margin-left:60px;">Must be less than 69px X 69px</div>
</div>
 
 
 	 </div>
                                             </fieldset>
						<fieldset class="step">
                            <legend>5. Confirm</legend>
							<p class="addEventInformation" style="font-size: 1.2em;width:750px;">
								Make sure everything in the form was correctly filled out before
								submitting.
															</p>
							<div style="margin: 0 auto;width:450px;">
							<div style="width:100px;">
                            <p class="submit" style="float:none;">

                                <button id="addEventButton" class="btn grid_3 push_2" tabindex="27" type="button">Add Event</button>
                            </p>
                            </div>
                            </div>
                        </fieldset>
                    
            
             
           
        </div>

 

<?= $form->end(); ?>
</div>
</div>
 
