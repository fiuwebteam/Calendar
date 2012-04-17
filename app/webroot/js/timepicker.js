function timePicker(anchor, hourInput, minuteInput, meridianInput) {
	
	var littleclock = anchor + "LittleClock";
	
	var anchor = anchor;
	var hourInput = hourInput;
	var minuteInput = minuteInput;
	var meridianInput = meridianInput;
	
	var anchorElement = anchor + "AnchorTimePicker";
	var hourElement = hourInput + "HourTimePicker";
	var minuteElement = minuteInput + "MinuteTimePicker";
	var meridianElement = meridianInput + "MeridianTimePicker";

	var output="<img alt='little clock clicker' src='http://www.fiudevspace.com/calv2/img/clock.png' id='"+anchor+"LittleClock'/>";

	output+="<div id='"+anchorElement+"' style='display:none;' class='timepicker'>";

	var hourOptions = $("#" + hourInput + " option");
	output+="<div id='"+hourElement+"' style='display:none;position:absolute;' class='timepicker'>";	
	for(var x = 0 ; x < hourOptions.length; x++ ) {
		output+="<span>"+hourOptions[x].text+" </span>";
	}
	output+=" - hr</div>";

	var minuteOptions = $("#" + minuteInput + " option");
	output+="<div id='"+minuteElement+"' style='display:none;position:absolute;' class='timepicker'>";
	for(var x = 0 ; x < minuteOptions.length; x++ ) {
		output+="<span>"+minuteOptions[x].text+" </span>";
	}
	output+=" - min</div>";

	var meridianOptions = $("#" + meridianInput + " option");
	output+="<div id='"+meridianElement+"' style='display:none;position:absolute;' class='timepicker'>";	
	for(var x = 0 ; x < meridianOptions.length; x++ ) {
		output+="<span>"+meridianOptions[x].text+" </span>";
	}
	output+=" - mer</div>";

	output+="</div>";
	
	$("#" + anchor).after(output);


	$("#" + littleclock).click(function() {
		
		$("#" + meridianInput).focus();
		$("#" + anchorElement).toggle();
		
		var pos = $(this).offset();
		var height = $(this).height();
		$("#" + hourElement).css( { "left": (pos.left) + "px", "top": (pos.top + height) + "px" } );
		
		
		$("#" + hourElement).show();
		$("#" + minuteElement).hide();	
		$("#" + meridianElement).hide();
	});
	
	$("#" + meridianInput).blur(function() {
		$("#" + anchorElement).hide();
		$("#" + hourElement).hide();
		$("#" + minuteElement).hide();	
		$("#" + meridianElement).hide();
	});
	
	$("#" + littleclock).mouseover(function() {	$("#" + littleclock).css("cursor", "pointer"); });

	$("#" + hourElement + " span").mouseover(function() {
		$("#" + hourElement).css("cursor", "pointer");
		var pos = $(this).offset();
		var height = $(this).height();
		$("#" + minuteElement).css( { "left": (pos.left) + "px", "top": (pos.top + height) + "px" } );
		$("#" + minuteElement).show();
		$("#" + meridianElement).hide();
		var hourValue = $(this).html(); 
		if (hourValue < 10) {hourValue = "0" + hourValue;}
		$("#" + hourInput).val(jQuery.trim(hourValue));
	});
	
	$("#" + minuteElement + " span").mouseover(function() {
		$("#" + minuteElement).css("cursor", "pointer");
		var pos = $(this).offset();
		var height = $(this).height();
		$("#" + meridianElement).css( { "left": pos.left + "px", "top": (pos.top + height) + "px" } );
		$("#" + meridianElement).show();
		var minValue = $(this).html();
		$("#" + minuteInput).val(jQuery.trim(minValue));
	});
	
	$("#" + meridianElement + " span").mouseover(function() {
		$("#" + meridianElement).css("cursor", "pointer");
		var pos = $(this).offset();
		var height = $(this).height();
		var meridianValue = $(this).html();
		$("#" + meridianInput).val(jQuery.trim(meridianValue));
	});
	/*
	$("#" + hourElement + " span").click(function() {
		$("#" + anchorElement).hide();
		$("#" + hourElement).hide();
		$("#" + minuteElement).hide();	
		$("#" + meridianElement).hide();
	});	
	
	$("#" + minuteElement + " span").click(function() {
		$("#" + anchorElement).hide();
		$("#" + hourElement).hide();
		$("#" + minuteElement).hide();	
		$("#" + meridianElement).hide();
	});	
	$("#" + meridianElement + " span").click(function() {
		$("#" + anchorElement).hide();
		$("#" + hourElement).hide();
		$("#" + minuteElement).hide();	
		$("#" + meridianElement).hide();
	});
	*/	
	
}
