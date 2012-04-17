$(document).ready(function(){
	var rootUrl = "";
	var urlArray = explode("/", document.URL);

	for(var x = 0; x < urlArray.length; x++) {
		if (
			urlArray[x] == "events" ||
			urlArray[x] == "calendars" ||
			urlArray[x] == "users" ||
			urlArray[x] == "tags"
				) { break;}
		rootUrl += urlArray[x] +  "/";
	}

	/*$("#menuStart").click(function(){
		$("#menuOptions").slideToggle();
		$("#menuCommands").hide();
	});*/
	
	
	//$("#menuCommands").hide();
	
	$("#eventCommand").mouseover(function(){
	/*	$("#eventCommand").css("background-color", "#D0AE4A");*/
	/*	$("#calendarCommand").css("background-color", "#113969");*/
	/*	$("#userCommand").css("background-color", "#113969");*/
		$("#menuCommands").show();
		$("#eventTab").show();
		$("#calendarTab").hide();
		$("#userTab").hide();
	});
	
	$("#eventCommand").mouseout(function(){
	$("#menuCommands").hide();
	});

	
	$("#calendarCommand").mouseover(function(){
	/*	$("#eventCommand").css("background-color", "#113969");*/
	/*	$("#calendarCommand").css("background-color", "#D0AE4A");*/
	/*	$("#userCommand").css("background-color", "#113969");*/
		$("#menuCommands").show();
		$("#eventTab").hide();
		$("#calendarTab").show();
		$("#userTab").hide();
	});
	
	$("#calendarCommand").mouseout(function(){
	$("#menuCommands").hide();
	});
	
	$("#userCommand").mouseover(function(){
		/*$("#eventCommand").css("background-color", "#113969");*/
		/*$("#calendarCommand").css("background-color", "#113969");*/
		/*$("#userCommand").css("background-color", "#D0AE4A");*/
		$("#menuCommands").show();
		$("#eventTab").hide();
		$("#calendarTab").hide();
		$("#userTab").show();
	});
	
		$("#userCommand").mouseout(function(){
	$("#menuCommands").hide();
	});

	//$("#user_options").hide();

	$("#signinbutton").click(function() {
		$("#signin_menu").slideToggle();
		//$("#calendarChooser").toggle();
	});

	$("#signin").submit(function() {
		$("#loginMessage").html("Processing...");
		$.post( (rootUrl + "users/ajaxlogin"), { "data[User][username]": $("#username").val(), "data[User][password]": $("#password").val(), "data[User][remember]": $("#remember").val() },  function(data){
			$("#username").attr("disabled", 'disabled');
			$("#password").attr("disabled", 'disabled');
			data = jQuery.trim(data);
			if (data[0] == 6) {
				$("#loginMessage").html("Please try again.");
			   	$("#username").removeAttr('disabled');
				$("#password").removeAttr('disabled');
		   	} else {
			   	if (data[0] <= 3) {
				   	$(".editor_options").show()
				}
				if (data[0] <= 2) {
					$(".admin_options").show()
				}

				var id = data[2];
				var x = 3;
				var mark = data[x];
				while (mark != ":") {
					id = id + "" + mark;
					x++;
					mark = data[x];
				}
				var link = $("#settingsLink").attr("href");
				$("#settingsLink").attr("href", link + "/" + id)

				
		   		$("#loginMessage").html("You are now logged in.");
		   		
		   		$("#signinbutton span").fadeOut("slow", function() {
		   			$("#signinbutton span").html("Log out").fadeIn("slow");   
		   			$("#registerLink").hide();
		   		});		
		   		$("#signin_menu").delay(1000).slideUp(); 
		   		$("#signinbutton").unbind("click").click(function() {
		   			document.location= rootUrl + 'users/logout/';
		   		});
		   		
		   		$("#menuAdmin").css("visibility", "visible");	
		   		
		   		$("#addEventButton").show();	
		   		$("#settingsLink").show();
		   		$('#menuAdmin .admin_options1').show();
		   		
		   		
		   	}
		});
		return false;
	});	
});