<?php
$js = "
$(document).ready(function(){
	$('#featureList').cycle({ 
	    fx:    'scrollHorz', 
	    timeout: 10000,
	    next:   '#featureNext', 
    	prev:   '#featurePrev'
	});
	$(\"#gridDiv\").load(\"". $html->url(array("controller" => "events", "action" => "dategrid"), true) ."/calendar:$calendar/start:".(isset($this->params["named"]["start"]) ? $this->params["named"]["start"] : date("Y-m-d"))."\");

$('#customButton').click(function(e) {
	$('#categoryDropDown').hide('fast');
	$('#typeContentBox').hide('fast');
	$('#calendarsChoice').hide('fast');
     var catbtn = $(this);
	 $('#popularDates').css({
     	position: 'absolute',
        top: catbtn.offset().top + catbtn.outerHeight() + 10,
        left: catbtn.offset().left
	  }).toggle('fast'); 
	e.preventDefault();
});
	
$('#close').click(function(e) {
	$('#popularDates').hide('fast');
		e.preventDefault();
});
	
$('#closecat').click(function(e) {
	$('#categoryDropDown').hide('fast');
	e.preventDefault();
});


$('#closecal').click(function(e) {
	$('#calendarsChoice').hide('fast');
	e.preventDefault();
});

		
$('#categoryButton').click(function(e) {
	$('#popularDates').hide('fast');
	$('#typeContentBox').hide('fast');
	$('#calendarsChoice').hide('fast');
	var catbtn = $(this);
	$('#categoryDropDown').css({
        position: 'absolute',
        top: catbtn.offset().top + catbtn.outerHeight() + 10,
        left: catbtn.offset().left
    }).toggle('fast');
	e.preventDefault();
	
	
});

	$('#typeButton').hover(function(e) {
	$('#popularDates').hide('fast');
	$('#categoryDropDown').hide('fast');
	$('#calendarsChoice').hide('fast');
	 var typebtn = $(this);
	 $('#typeContentBox').css({
     	position: 'absolute',
        top: typebtn.offset().top + typebtn.outerHeight() + 10,
        left: typebtn.offset().left
	  }).show('fast');
	 	e.preventDefault();
});
	
	$('#calendarsButton').click(function(e) {
	$('#popularDates').hide('fast');
	$('#categoryDropDown').hide('fast');
	$('#typeContentBox').hide('fast');
	 var calendarsbtn = $(this);
	 $('#calendarsChoice').css({
     	position: 'absolute',
        top: calendarsbtn.offset().top + calendarsbtn.outerHeight() + 10,
        left: calendarsbtn.offset().left
	  }).toggle('fast');
	 	e.preventDefault();
});

 
    $('#monthViewButton').hover(function(e){
   		var catbtn = $(this);
		$('#monthViewDropDown').css({
		    position: 'absolute',
		    top: catbtn.offset().top + catbtn.outerHeight() + 10,
		    left: catbtn.offset().left
		}).show('fast');
		e.preventDefault();

    }); 

	datePickerController.createDatePicker({
	  formElements:{'dateStart':'M-sp-j-cc-sp-y'}
	});
	datePickerController.createDatePicker({
	  formElements:{'dateEnd':'M-sp-j-cc-sp-y'}
	});
	
	
    $('#dateEnd').click(function() {
    	$('#fd-but-dateEnd').click();
    });
    
    $('#dateStart').click(function() {
    	$('#fd-but-dateStart').click();
    });
	
	$('#calendarChooser').change(function() {
		var hasEvent = false;
		var hasIndex = false;
		var htmlArray = explode('/', document.URL);
		link = 'http://';
		for(x=2; x<htmlArray.length; x++) {
			tmp = explode(':', htmlArray[x]);
			if (!(tmp[0] == 'calendar' || tmp[0] == '' )) { link+=htmlArray[x] + '/';}	
			if (htmlArray[x] == 'events') {hasEvent = true;}
			if (htmlArray[x] == 'index') {hasIndex = true;}
		}
		if (!hasEvent) {link+='events/';}
		if (!hasIndex) {link+='index/';}
		link = link.replace('\/fblike:yes\/','/');
		window.location = link + 'calendar:' + this.value;
	});";
 

	// this week
	if(
		( isset($this->params["named"]["start"]) && $this->params["named"]["start"] == (date("Y-m-d")) &&
		 isset($this->params["named"]["end"]) && $this->params["named"]["end"] == (date("Y-m-d", strtotime("today +7 days")))) ||
		( !isset($this->params["named"]["start"]) )
	) {
		$js .= "
	$('.customConnector').css('left', '27px');
		";
	}
	// this month	
	else if(
		isset($this->params["named"]["start"]) && $this->params["named"]["start"] == (date("Y-m-d")) &&
		isset($this->params["named"]["end"]) && $this->params["named"]["end"] == (date("Y-m-d", strtotime("today +30 days")))
	) {
		$js .= "
	$('.customConnector').css('left', '75px');
		";
	} 	
	// this year
	else if(
		isset($this->params["named"]["start"]) && $this->params["named"]["start"] == (date("Y-m-d")) &&
		isset($this->params["named"]["end"]) && $this->params["named"]["end"] == (date("Y-m-d", strtotime("today +365 days")))
	) {
		$js .= "
	$('.customConnector').css('left', '126px');
		";
	} else {
		$js .= "
	$('.customConnector').css('display', 'none');
		";
	}



$js .= "});";


$javascript->codeBlock( $js , array('inline'=>false));
?>