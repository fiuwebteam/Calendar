var optionLevel = 1;

function eventChange() {
	switch($("#EventType").val()) {
	case "0":
		$("#dateTimeForm").removeClass("grid_5");
		$("#dateTimeForm").addClass("grid_10");
		$("#option4").hide("slow");
		$("#option5").hide("slow");
		$("#option6").hide("slow");
		break;
	case "1":
		$("#dateTimeForm").removeClass("grid_5");
		$("#dateTimeForm").addClass("grid_10");
		$("#option4").show("slow");
		$("#option5").show("slow");
		$("#option6").show("slow");
		$("#endDate").hide();
		$("#startDateLabel").html("Date");
		$("#endTime").show();			
		$("#startTime").show();			
		$("#startTimeLabel").html("Start Time");
		break;
	case "2":
		$("#dateTimeForm").removeClass("grid_5");
		$("#dateTimeForm").addClass("grid_10");
		$("#option4").show("slow");
		$("#option5").hide("slow");
		$("#option6").show("slow");
		$("#endDate").show();
		$("#startDateLabel").html("Start Date");
		$("#startTime").hide();
		$("#endTime").hide();
		break;
	case "3":
		$("#dateTimeForm").removeClass("grid_10");
		$("#dateTimeForm").addClass("grid_5");
		$("#option4").show("slow");
		$("#option5").hide("slow");
		$("#option6").show("slow");
		$("#endDate").hide();
		$("#startDateLabel").html("Date");
		$("#startTime").show();
		$("#endTime").hide();
		$("#startTimeLabel").html("Time");
		break;
	}	
}


function updateRepeats(params) {
	var repeatOptions = parseInt($("#EventRepeats").val(), 10);
	if (params == 0) {
		if (repeatOptions > 1) {
			$("#recursive_" + repeatOptions).hide("slow");
			repeatOptions = repeatOptions - 1;
		}
	} else {
		if (repeatOptions < 5) {
			repeatOptions += 1;
			$("#recursive_" + repeatOptions).show("slow");
		}
	}
	$("#EventRepeats").val(repeatOptions);
}

function hideAll() {
	$("#calendarForm").hide();
	$("#typeForm").hide();
	$("#catForm").hide();
	$("#dateTimeForm").hide();
	$("#repeatForm").hide();
	$("#detailsForm").hide();
	if ($("#EventType").val() !=  1) {
		$("#option5").hide();
	}
	if ($("#EventType").val() ==  0) {
		$("#option4").hide();
		$("#option6").hide();
	}
	
	
}

function removeSelected() {
	$("#option1 a").removeClass("selected");
	$("#option2 a").removeClass("selected");
	$("#option3 a").removeClass("selected");
	$("#option4 a").removeClass("selected");
	$("#option5 a").removeClass("selected");
	$("#option6 a").removeClass("selected");
}

function selectedButtons(object, x) {
	
	$("#noneButton_"+x).removeClass('calButton_on').addClass('calButton_off');
	$("#dayButton_"+x).removeClass('calButton_on').addClass('calButton_off');
	$("#weekButton_"+x).removeClass('calButton_on').addClass('calButton_off');
	$("#monthButton_"+x).removeClass('calButton_on').addClass('calButton_off');
	$("#yearButton_"+x).removeClass('calButton_on').addClass('calButton_off');
	$(object).removeClass('calButton_off').addClass('calButton_on');
}


function yearButton(object, x) {
	$("#EventRepeatsType"+x).val("4");
	
	selectedButtons(object, x);
	
	$("#EventDateParameter1"+x).show("slow");
	$("#EventDateParameter2"+x).show("slow");
	
	$("#dateParameter_1_"+x).show("slow");
	$("#dateParameter_2_"+x).show("slow");
	$("#dateParameter_3_"+x).show("slow");
	$("#dateParameter_4_"+x).show("slow");
	
	$("#dateParameter_2_"+x).html("year(s).");		
	$("#dateParameter_4_"+x).html("years(s).");

	$("#weekFields_"+x).hide("slow");
}

function monthButton(object, x) {
	$("#EventRepeatsType"+x).val("3");
	
	selectedButtons(object, x);
	
	$("#EventDateParameter1"+x).show("slow");
	$("#EventDateParameter2"+x).show("slow");
	
	$("#dateParameter_1_"+x).show("slow");
	$("#dateParameter_2_"+x).show("slow");
	$("#dateParameter_3_"+x).show("slow");
	$("#dateParameter_4_"+x).show("slow");
	
	$("#dateParameter_2_"+x).html("month(s).");		
	$("#dateParameter_4_"+x).html("month(s).");

	$("#weekFields_"+x).hide("slow");
	
}

function weekButton(object, x) {
	$("#EventRepeatsType"+x).val("2");
	
	selectedButtons(object, x);
	
	$("#EventDateParameter1"+x).hide();
	$("#EventDateParameter2"+x).show("slow");
	
	$("#dateParameter_1_"+x).hide();
	$("#dateParameter_2_"+x).hide();
	
	$("#weekFields_"+x).show("slow");
	
	$("#dateParameter_3_"+x).show("slow");
	$("#dateParameter_4_"+x).show("slow");
	
	$("#dateParameter_4_"+x).html("week(s).");
	
}

function dayButton(object, x) {
	$("#EventRepeatsType"+x).val("1");
	
	selectedButtons(object, x);
	
	$("#EventDateParameter1"+x).show("slow");
	$("#EventDateParameter2"+x).show("slow");
	
	$("#dateParameter_1_"+x).show("slow");
	$("#dateParameter_2_"+x).show("slow");
	$("#dateParameter_3_"+x).show("slow");
	$("#dateParameter_4_"+x).show("slow");
	
	$("#dateParameter_2_"+x).html("day(s).");		
	$("#dateParameter_4_"+x).html("day(s).");

	$("#weekFields_"+x).hide("slow");
}

function noneButton(object, x) {
	$("#EventRepeatsType"+x).val("0");		
	selectedButtons(object, x);
	$("#EventDateParameter1"+x).hide("slow");
	$("#EventDateParameter2"+x).hide("slow");
	
	$("#dateParameter_1_"+x).hide("slow");
	$("#dateParameter_2_"+x).hide("slow");
	$("#dateParameter_3_"+x).hide("slow");
	$("#dateParameter_4_"+x).hide("slow");
	$("#weekFields_"+x).hide("slow");
}

$(document).ready(function(){
	
	eventChange();
	
	$("select").change( function() {
		updateDates();
		updateTimes();
	});
	
	// hide everything at start
	hideAll();	
	
	// Set up repeats
	
	for(var x = 1; x <= $("#EventRepeats").val(); x++) {
		$("#recursive_" + x).show();
		switch($("#EventRepeatsType"+x).val()) {
			case "0":
				noneButton(("#noneButton_"+x), x);
				break;
			case "1":
				dayButton(("#noneButton_"+x), x);
				break;
			case "2":
				weekButton(("#noneButton_"+x), x);
				break;
			case "3":
				monthButton(("#noneButton_"+x), x);
				break;
			case "4":
				yearButton(("#noneButton_"+x), x);
				break;
		}
		
	}
	
	
	// show first option
	$("#calendarForm").show();
	
	// Set up click actions
	//----------------------------------------------
	$("#noneButton_1").click(function() {		
		noneButton(this, 1);
	});
	$("#noneButton_2").click(function() {		
		noneButton(this, 2);		
	});
	$("#noneButton_3").click(function() {		
		noneButton(this, 3);		
	});
	$("#noneButton_4").click(function() {		
		noneButton(this, 4);		
	});
	$("#noneButton_5").click(function() {		
		noneButton(this, 5);		
	});
	//**********************************
	$("#dayButton_1").click(function() {
		dayButton(this, 1);
	});
	$("#dayButton_2").click(function() {
		dayButton(this, 2);		
	});
	$("#dayButton_3").click(function() {
		dayButton(this, 3);
	});
	$("#dayButton_4").click(function() {
		dayButton(this, 4);
	});
	$("#dayButton_5").click(function() {
		dayButton(this, 5);
	});
	//**********************************
	$("#weekButton_1").click(function() {
		weekButton(this, 1);
	});
	$("#weekButton_2").click(function() {
		weekButton(this, 2);
	});
	$("#weekButton_3").click(function() {
		weekButton(this, 3);
	});
	$("#weekButton_4").click(function() {
		weekButton(this, 4);
	});
	$("#weekButton_5").click(function() {
		weekButton(this, 5);
	});
	//**********************************
	$("#monthButton_1").click(function() {
		monthButton(this, 1);
	});
	$("#monthButton_2").click(function() {
		monthButton(this, 2);
	});
	$("#monthButton_3").click(function() {
		monthButton(this, 3);
	});
	$("#monthButton_4").click(function() {
		monthButton(this, 4);
	});
	$("#monthButton_5").click(function() {
		monthButton(this, 5);
	});
	//**********************************
	$("#yearButton_1").click(function() {
		yearButton(this, 1);
	});
	$("#yearButton_2").click(function() {
		yearButton(this, 2);
	});
	$("#yearButton_3").click(function() {
		yearButton(this, 3);
	});
	$("#yearButton_4").click(function() {
		yearButton(this, 4);
	});
	$("#yearButton_5").click(function() {
		yearButton(this, 5);
	});
	//**********************************	
	//----------------------------------------------
	$("#addRepeatOption").click(function() {
		updateRepeats(1);
	});
	//----------------------------------------------
	$("#subtractRepeatOption").click(function() {
		updateRepeats(0);
	});
	//----------------------------------------------
	$("#EventType").change( function() {
		eventChange();	
	});
	//----------------------------------------------
	
	$("#featurePrev").click(function() {
		if (optionLevel > 1) { optionLevel--; }
		changeOption();
	});
	
	$("#featureNext").click(function() {
		if (optionLevel < 6) {
			if (!$("#option4").is(":visible") && optionLevel == 3  ) {
				return;
			}
			optionLevel++; 
		}
		changeOption();
	});
	
	
	$("#option1").click(function() {
		optionLevel = 1;
		changeOption();
	});
	$("#option2").click(function() {
		optionLevel = 2;
		changeOption();
	});
	$("#option3").click(function() {
		optionLevel = 3;
		changeOption();
	});
	
	$("#option4").click(function() {
		optionLevel = 4;
		changeOption();
	});
	
	$("#option5").click(function() {
		optionLevel = 5;
		changeOption();
	});
	
	$("#option6").click(function() {
		optionLevel = 6;
		changeOption();
	});
	
});

function changeOption() {
	hideAll();
	removeSelected();
	switch(optionLevel) {
		case 1:
			$("#calendarForm").fadeIn("slow");
			$("#option1 a").addClass("selected");
			$("#indicator").animate({ 
			    marginTop: "15px"
			  }, 500 );
			break;
		case 2:
			$("#typeForm").fadeIn("slow");
			$("#option2 a").addClass("selected");
			$("#indicator").animate({ 
			    marginTop: "62px"
			  }, 500 );
			break;
		case 3:
			$("#catForm").fadeIn("slow");
			$("#option3 a").addClass("selected");
			$("#indicator").animate({ 
			    marginTop: "108px"
			  }, 500 );
			break;
		case 4:
			$("#dateTimeForm").fadeIn("slow");
			$("#option4 a").addClass("selected");
			$("#indicator").animate({ 
			    marginTop: "158px"
			  }, 500 );	
			break;
		case 5:
			$("#repeatForm").fadeIn("slow");
			$("#option5 a").addClass("selected");
			$("#indicator").animate({ 
			    marginTop: "205px"
			  }, 500 );	
			break;
		case 6:
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
			break;
	}
	
}


function updateDates() {
	
	var startDate =  $("#EventStartYear").val() + $("#EventStartMonth").val() + $("#EventStartDay").val();
	var endDate =  $("#EventEndYear").val() + $("#EventEndMonth").val() + $("#EventEndDay").val();
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

function updateTimes() {
	var startTime =  $("#EventStartHour").val();
	if ($("#StartMeridian").val() == "pm") {
		startTime =  parseInt(startTime, 10) + 12;
	}
	startTime += $("#StartMin").val();

	var endTime =  $("#EventEndHour").val();
	if ($("#EndMeridian").val() == "pm") {
		endTime =  parseInt(endTime, 10) + 12;
	}
	endTime += $("#EndMin").val();

	if (startTime > endTime && endTime != "120") {		
		if (
			$("#EndMeridian").val() == "am" && 
			$("#StartMeridian").val() == "pm"
			) {
			$("#EndMeridian").val("pm");				
		} else if ($("#EndMeridian").val() == $("#StartMeridian").val()) {
			if ($("#EventStartHour").val() >= $("#EventEndHour").val()
				&& $("#EventStartHour").val() != "12"
				) {
				$("#EventEndHour").val($("#EventStartHour").val());					
			}
			if ($("#StartMin").val() >= $("#EndMin").val()
				&& ($("#EventStartHour").val() == $("#EventEndHour").val()) ) {
				$("#EndMin").val($("#StartMin").val());
			}
		}
	}
	
}
