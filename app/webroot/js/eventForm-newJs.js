
$(document).ready(function(){

	//lets make programmatic val changes fire onchange
	//Store the old val function
	$.fn.custom_oldVal = $.fn.val;
	
	//Update the val function to fire the change event where appropriate:
	$.fn.val = function(value) {
	    if(value == null || value == undefined){
	        return this.custom_oldVal();
	    } else {
	        //Only call onchange if the value has changed
	        if(value == this.custom_oldVal()){
	            return this;//Return this object for chain processing
	        } else {
	            return this.custom_oldVal(value).change();
	        }
	    }
	}

	
	 var startDate; var endDate; var startTime; var endTime; 
	 $.calGlobal = {};
	 $.calGlobal.eventType = 0;
	  
	//$("#EventStartMonth, #EventStartDay, #EventStartYear, #EventEndMonth,   #EventEndDay, #EventEndYear, #EventStartHour, #StartMin, #StartMeridian, #EventEndHour, #EndMin, #EndMeridian")
	$("select").change( function() {
		
		updateDates();    
		updateTimes();
		//typeNormalCheck();
		//if eventype is not normal, hide repeat option
		/*if ($.calGlobal.eventType==0){
   			$('#repeatToggleDiv').hide();
   				} else 	$('#repeatToggleDiv').show();*/
   
		 
		
	});
	


function updateDates() {
	
	 startDate =  $("#EventStartYear").val() +  $("#EventStartMonth").val() +  $("#EventStartDay").val();
	 endDate =  $("#EventEndYear").val() + $("#EventEndMonth").val() + $("#EventEndDay").val();
	if (startDate > endDate) {	
		if ($("#EventStartYear").val() >= $("#EventEndYear").val()) {
			$("#EventEndYear").val($("#EventStartYear").val());
			if ($("#EventStartMonth").val() >= $("#EventEndMonth").val()) {
				$("#EventEndMonth").val($("#EventStartMonth").val());
				if ($("#EventStartDay").val() > $("#EventEndDay").val()) {
					$("#EventEndDay").val($("#EventStartDay").val());
				}
			}
		}
		datePickerController.setDateFromInput("#EventEndYear");
	}
	 
}



function typeNormalCheck(){

	if ( (startDate == endDate) && (startTime != endTime) ){
    	$.calGlobal.eventType = 1; //normal 
    	 	}
	else $.calGlobal.eventType = 0;
}




function updateTimes() {
	 startTime =  $("#EventStartHour").val();
	if ($("#EventStartMeridian").val() == "pm") {
		startTime =  parseInt(startTime, 10) + 12;
	}
	startTime += $("#EventStartMin").val();

	 endTime =  $("#EventEndHour").val();
	if ($("#EventEndMeridian").val() == "pm") {
		endTime =  parseInt(endTime, 10) + 12;
	}
	endTime += $("#EventEndMin").val();

	if (startTime > endTime && endTime != "120") {		
		if (
			$("#EventEndMeridian").val() == "am" && 
			$("#EventStartMeridian").val() == "pm"
			) {
			$("#EventEndMeridian").val("pm");				
		} else if ($("#EventEndMeridian").val() == $("#EventStartMeridian").val()) {
			if ($("#EventStartHour").val() >= $("#EventEndHour").val()
				&& $("#EventStartHour").val() != "12"
				) {
				$("#EventEndHour").val($("#EventStartHour").val());					
			}
			if ($("#EventStartMin").val() >= $("#EventEndMin").val()
				&& ($("#EventStartHour").val() == $("#EventEndHour").val()) ) {
				$("#EventEndMin").val($("#EventStartMin").val());
			}
		}
	}
	 
}

});