function minical(today, date, divId, currentMonth ) {
	var x, y, output, link, tmp, lastMonthMonth, lastMonthYear, nextMonthMonth, nextMonthYear;
	if (date == null) {date = today;}
	var dateArray = explode('-', date);
	var todayArray = explode('-', today);
	today = (new Date(todayArray[0], ( todayArray[1] - 1), todayArray[2]));
	var hasEvent = false;
	var hasIndex = false;
	
	var htmlArray = explode("/", document.URL);
	link = "http://";
	for(x=2; x<htmlArray.length; x++) {
		tmp = explode(":", htmlArray[x]);
		if (!(tmp[0] == "start" || tmp[0] == "end" || tmp[0] == "" )) { link+=htmlArray[x] + "/";}	
		if (htmlArray[x] == "events") {hasEvent = true;}
		if (htmlArray[x] == "index") {hasIndex = true;}
	}
	if (!hasEvent) {link+="events/";}
	if (!hasIndex) {link+="index/";}
	
	var firstDayOfTheMonth = new Date(dateArray[0], ( dateArray[1] - 1), 1);
	var firstWeekdayOfTheMonth =  firstDayOfTheMonth.getDay();
	var daysInTheMonth = ( 32 - new Date(dateArray[0], ( dateArray[1] - 1), 32).getDate() );
	
	if (dateArray[1] == 1) { 
		lastMonthMonth = 12; 
		lastMonthYear = dateArray[0] - 1; 
		nextMonthMonth = parseInt(dateArray[1], 10) + 1;
		nextMonthYear = dateArray[0];
	} else if (dateArray[1] == 12) {
		lastMonthMonth = dateArray[1] - 1; 
		lastMonthYear = dateArray[0]; 
		nextMonthMonth = 1;
		nextMonthYear = parseInt(dateArray[0], 10) + 1;
	} else { 
		lastMonthMonth = dateArray[1] - 1; 
		lastMonthYear = dateArray[0];
		nextMonthMonth = parseInt(dateArray[1], 10) + 1;
		nextMonthYear = dateArray[0];
	}
	
	var daysInTheLastMonth = ( 32 - new Date(lastMonthYear, ( lastMonthMonth - 1), 32).getDate() );	
	var monthNames = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	
	var startThisMonth = new Date(dateArray[0], ( dateArray[1] - 1), 1).format("yyyy-mm-dd");
	var endThisMonth = new Date(dateArray[0], ( dateArray[1] - 1), daysInTheMonth).format("yyyy-mm-dd");
	
	var startThisYear = new Date(dateArray[0], 0, 1).format("yyyy-mm-dd");
	var endThisYear = new Date(dateArray[0], 11, 31).format("yyyy-mm-dd");
	
	output = "<table id='" + divId + "Table' >" +
			"<tr id='" + divId + "MonthRow'>" +
				"<th colspan='7' ><span id='" + divId + "LeftMonthCell' > << </span>" +
				"<span id='" + divId + "MonthTitleCell' > " + "<a href='" + link + "start:" + startThisMonth +
				"/end:" + endThisMonth + "'>" + monthNames[parseInt(dateArray[1], 10) - 1] + "</a> " +
				"<a href='" + link + "start:" + startThisYear + "/end:" + endThisYear + "'>" + 
				dateArray[0] + "</a> </span>" +
				"<span id='" + divId + "RightMonthCell' > >> </span></th>" +
			"</tr>" +
			"<tr id='" + divId + "WeekdayRow' >" +
				"<th id='" + divId + "SundayCell' >Sun</th>" +
				"<th id='" + divId + "MondayCell' >Mon</th>" +
				"<th id='" + divId + "TuesdayCell' >Tue</th>" +
				"<th id='" + divId + "WednesdayCell' >Wed</th>" +
				"<th id='" + divId + "ThursdayCell' >Thu</th>" +
				"<th id='" + divId + "FridayCell' >Fri</th>" +
				"<th id='" + divId + "SaturdayCell' >Sat</th>" +
			"</tr>";
	var day = daysInTheLastMonth - firstWeekdayOfTheMonth + 1;
	for (y = 0; y < 6; y++ ) {
		output+="<tr id='" + divId + "Row" + y + "' >";
		for (x = 0; x < 7; x++) {
			if ( (y == 0 && day > daysInTheLastMonth) || (y != 0 && day > daysInTheMonth) ) {day = 1;}
			output+="<td id='" + divId + "Cell" + x + "_" + y + "' class='";
			if (y == 0 && day > 7) {output+="previousMonth";}
			else if (y >= 4 && day < 14) {output+="nextMonth";}
			else {output+="currentMonth";}
			output+="' >";
			var currentDay = new Date(dateArray[0], ( dateArray[1] - 1), day);
			var currentDayString = currentDay.format("yyyy-mm-dd"); 
			var previousMonthDayString = new Date(lastMonthYear, (lastMonthMonth - 1), day).format("yyyy-mm-dd");
			var nextMonthDayString = new Date(nextMonthYear, (nextMonthMonth - 1), day).format("yyyy-mm-dd");
			output+="<a href='" + link + "start:";
			if (y == 0 && day > 7) {output+=previousMonthDayString;}
			else if (y >= 4 && day < 14) {output+=nextMonthDayString;}
			else {output+=currentDayString;}			
			output+="/end:";
			if (y == 0 && day > 7) {output+=previousMonthDayString;}
			else if (y >= 4 && day < 14) {output+=nextMonthDayString;}
			else {output+=currentDayString;}
			output+="' id='" + divId + "Link" + x + "_" + y + "' class='";			
			if (today.toString() == currentDay.toString()) { output+= divId + "Today"; }			
			output+="' >" + (day++) + "</a>";						
			output+="</td>";			
		}
		output+="</tr>";
	}
	output+="</table>";
	
	
	$("#" + divId).html(output);
	$("#" + divId + "LeftMonthCell").mouseover(function() {$("#" + divId + "LeftMonthCell").css("cursor", "pointer");});	
	$("#" + divId + "RightMonthCell").mouseover(function() {$("#" + divId + "RightMonthCell").css("cursor", "pointer");});
	
	$("#" + divId + "LeftMonthCell").click(function() {
		var lastMonthDate = "";
		if (parseInt(dateArray[1], 10) == 1) {
			lastMonthDate+=(parseInt(dateArray[0], 10)-1)+"-12-01";
		} else {
			lastMonthDate+=dateArray[0]+"-" + (parseInt(dateArray[1], 10) -1)  + "-01";
		}
		minical(today.format("yyyy-mm-dd"), lastMonthDate, divId, false);
	});
	
	$("#" + divId + "RightMonthCell").click(function() {
		var nextMonthDate = "";
		if (dateArray[1] == "12") {
			nextMonthDate+=(parseInt(dateArray[0], 10)+1)+"-01-01";
		} else {
			nextMonthDate+=dateArray[0]+"-" + (parseInt(dateArray[1], 10) + 1)  + "-01";
		}
		minical(today.format("yyyy-mm-dd"), nextMonthDate, divId, false);
	});
}
