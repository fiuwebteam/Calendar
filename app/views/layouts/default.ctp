<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=7; IE=8" />
	<?php echo $html->charset(); ?>
	<title>
		<?php if ($this->params["controller"] == "events" && $this->params["action"] == "view" ) { echo $event['Event']['title'] . "-"; }?>
		University Calendar - Florida International University
	</title>
	<meta name="language" content="en"/>
	
	<meta name="description" content="Florida International University Calendar provides information for the student body, faculty and staff, and community. This information relates to either events or deadlines. Events or deadlines are categorized by academics, alumni and community, arts and entertainment, athletics, lectures, student life, and for faculty and staff."/>
	<meta name="keywords" content="florida, international, business, miami, university, universities, college, colleges, higher education, academics, school, worlds ahead, calendar, events, deadlines, academics, alumni,community, arts, entertainment, athletics, lectures, student life, faculty, staff"/>
<!-- facebook meta tags -->
<?php
    if(!isset($meta_title)) {
        $meta_title = "FIU Events Calendar";
    }
    if(!isset($meta_location)) {
        $meta_location = null;
    }
    if(!isset($meta_image)) {
        $meta_image = "http://". $_SERVER['HTTP_HOST']."/img/fiu-fb.jpg";
    }
    if(!isset($meta_type)) {
        $meta_type = null;      
    }
   
    if(!isset($meta_url)) {
    	$meta_url="http://fiudevspace.com/calv2/events/view/8958";
    }
    
    if(!isset($meta_description)) {
        $meta_description = "FIU Calendar";
        
    }
    ?>

    <meta property="og:site_name" content="FIU Calendar" />
    <meta property="og:title" content="<?= $meta_title ?>"/>
    <meta property="og:type" content="<?= $meta_type ?>"/>
    <meta property="og:url" content="<?= $meta_url ?>"/>
    <meta property="og:image" content="<?= $meta_image ?>"/>
    <meta property="og:description" content="<?= $meta_description ?>"/>
    <meta property="fb:app_id" content="279171232143928"/>
    <meta property="fb:admins" content="1218897672"/>


	<?php
		echo $html->meta('icon') . "\n";
		
		$cssJSLink = "";
		foreach(explode("/", $_SERVER["PHP_SELF"]) as $key => $value) {
			if ($value == "index.php" || $key == 0) { continue; }
			$cssJSLink .= "$value/";
		}
		
		echo $html->css("/min/?b=$cssJSLink"."css&f=core/reset.css,core/text.css,core/960.css,front-general.css,vr.css,datepicker.css,modal-basic.css,add-event-form.css", NULL, array('media' => 'all'));
		echo $html->css('print', NULL, array('media' => 'print')) . "\n";
		
		//echo $html->css('core/reset', NULL, array('media' => 'all')) . "\n";
		//echo $html->css('core/text', NULL, array('media' => 'all')) . "\n";
		//echo $html->css('core/960', NULL, array('media' => 'all')) . "\n";
		//echo $html->css('front-general', NULL, array('media' => 'all')) . "\n";
		//echo $html->css('vr', NULL, array('media' => 'all')) . "\n";
		
		//echo $html->css("datepicker", null, array()) . "\n";
		//echo $html->css("modal-basic.css", null, array()) . "\n";
		//echo $html->css("modal-basic_ie.css", null, array()) . "\n";
	    //echo $html->css('add-event-form', null,  array('media' => 'all')) . "\n";
		
		echo '<!--[if IE]>';
			echo $html->css('ie-front-general');		
		echo '<![endif]-->';
		
		echo '<!--[if IE 6]>';
		echo $javascript->link('modernizer-1.6.min');
		echo $html->css('ie6-front-general');
		echo '<![endif]-->';
	?>


</head>
<body>
<!-- flash message-->
<?php 
	$session->flash();
	$session->flash('auth'); 
?>

<!--<div class="topBar"><p>empty black void</p></div>
<div class="topBar2"><p>the shadow</p></div>-->

    
    <div id="headerWrapper">
 
	<div class="header" style="margin: 0 auto; width:960px;">
	
<div class="feedback"><a title="FIU Website Feedback" onclick="window.open('http://fiu.wufoo.com/forms/fiu-university-calendar-20-feedback/', null, 'height=593, width=680, toolbar=0, location=0, status=1, scrollbars=1,resizable=1'); return false" href="#">Feedback form - Please send us Feedback</a></div>

		<div class="logo grid_5 alpha omega vMarginBottom_4">
			
					<?php $image = $html->image('fiulogowhite.gif', array("alt" => "Florida International University"));
			 echo $html->link($image, array("controller" => "events", "action" => "index"), array('rel'=>"Go To Florida International University Calendar",'escape' => false));?>


			
			<h1 class="cal_title"><?= $html->link("University Calendar", array("controller" => "events", "action" => "index") ,array('rel'=>'Go to FIU University Calendar')) ?></h1>	

		</div>
		
		
		
	
			
<div id="itemWrapper" class="right" style="margin-right:10px;">	



		
		<div class="signinBox grid_5 ">
	 
				<?php echo $html->link("Register", array("controller" => "users", "action" => "register" ),array('rel'=>'Click to Register', 'id' => 'registerLink', 'class'=>'registerLink','target'=>'_blank')); ?>  

			<?php $separator =''.($isLogged ?'' :"<span id='separater'>|</span>");  
			echo $separator; ?> 
					
				<?= $html->link("FAQ", array("controller" => "events", "action" => "faq" ),array( 'rel'=>'The FAQ','class'=>'registerLink')) ?>
				|
				<?= $html->link("My Settings", array("controller" => "users", "action" => "edit", $session->read('Auth.User.id') ),array( 'style' =>(  ($priviledge > 5) ? "display:none" : "" ) , 'id' => 'settingsLink', 'rel'=>'Change your settings','class'=>'registerLink')) ?>
	 						<a class="signin button vMarginTop_0" rel="Click to log in" tabindex="3" href="#" id='signinbutton'><span>Sign in</span></a>
			<fieldset class="common-form standard-form" id="signin_menu">
   				 <form action="" id="signin" method="post">
   				 	<div id='loginMessage'></div>
  					 <p class="textbox">
 					     <label for="username">Username</label>
    					 <input type="text" tabindex="10" title="username" value="" name="session[username_or_email]" id="username" />
  					 </p>

  					 <p class="textbox">
    					<label for="password">Password</label>
    					<input type="password" tabindex="11" title="password" value="" name="session[password]" id="password" />
  					</p>

  					<p class="remember">
   						 <input type="submit" class="button" tabindex="7" value="Sign in" id="c" />
    					 <input type="checkbox" tabindex="13" value="1" name="remember_me" id="remember" />
    					 <label for="remember">Remember me</label>
 					 </p>

  					<p class="forgot">
  						<?php echo $html->link("Forgot password?", array("controller" => "users", "action" => "resetpswd"),array('tabindex'=>'14')); ?>    					
  					</p>

  					<!-- <p class="forgot-username">
    					<a title="If you remember your password, try logging in with your email" id="forgot_username_link" href="">Forgot username?</a>
  					</p>-->
   
  
  					<input type="hidden" value="" id="signin_q" name="q" />
  				</form>
			</fieldset>
		</div><!-- end signinBox -->








	
		
		

		
</div>	<!--end itemwrapper -->
	
	
	
	
	
	
	
		
		
		<div id="itemWrapper2">				
				
				
			

				
				
	<div id ="searchWrapper" class="grid_5 right omega" style="display:inline;">
				
	
				
			<div class="search  vMarginTop_1 ">
			
				<?= $form->create(null, array( "url" => $html->url(array("controller" => "events", "action" => "search"), true), "type" => "get")); ?>
					 <fieldset >
						 
						<input alt="Type To Search" type="text" id="search" name="search" class="searchBox" <?= isset($this->params["named"]["search"])? "value='".$this->params["named"]["search"] ."'" :""; ?>  /><input alt="Submit to Search" id="submitSearch" type="submit" value="Search" class="button submitBtn" />

						<a href='#' onclick='$("#searchText").toggle();' style="color:white;">(?)</a>
						<div id='searchText' style='display:none; position:absolute; left:718px; top:65px; padding:10px; width:200px; height:120px; background-color:white; border-style:solid; border-width:2px;'>
						 You can also add start and end date parameters.<br/>
						 For example: "search for something s:2011-01-01 e:2011-12-31" will search for something starting January 1st, 2011 and ending December 31st, 2011.
						</div>
											</fieldset>				
				<?= "</form>"?>
			</div>
		</div>
		


 
<div id="menuAdmin">
<ul id="dMenu">
	<li>
		<a href="#" class="hh">Events</a>
		<ul class="level2">
		 <li class="it"><?= $html->link("Add an Event", array("controller" => "events", "action" => "add")) ?></li>
		 <li><?= $html->link("My Events", array("controller" => "events", "action" => "my_events")) ?></li>
		 <li class='editor_options' <?php if ($priviledge > 3)  {echo "style='display:none;'"; } ?>><?= $html->link("My Pending Events", array("controller" => "events", "action" => "pending")) ?></li>
<li class='admin_options' <?php if ($priviledge > 2)  {echo "style='display:none;'"; } ?>><?= $html->link("Feature Events", array("controller" => "events", "action" => "feature")) ?></li>
					<li class='editor_options' <?php if ($priviledge > 3)  {echo "style='display:none;'"; } ?>><?= $html->link("Import multiple Events", array("controller" => "events", "action" => "massInput")) ?></li>
		 </ul>
	
	</li>

	<li class='admin_options1' <?php if ($priviledge > 2)  {echo "style='display:none;'"; } ?>>
		<a href="#">Calendar</a>
		<ul class="level2">
	 <li><?= $html->link("Add a Calendar", array("controller" => "calendars", "action" => "add")) ?></li>
	 <li><?= $html->link("My Calendars", array("controller" => "calendars", "action" => "index")) ?></li>
	 <li><?= $html->link("View Calendar Events", array("controller" => "calendars", "action" => "events")) ?></li>
		<li><?= $html->link("View Calendar User", array("controller" => "calendars", "action" => "users")) ?></li>
		</ul>
	</li>
	
	<li class='admin_options' <?php if ($priviledge > 2)  {echo "style='display:none;'"; } ?>>
		<a href="#">Users</a>
        <ul class="level2">
						<li><?= $html->link("Add a User", array("controller" => "users", "action" => "add")) ?></li>
						<li><?= $html->link("My Users", array("controller" => "users", "action" => "index")) ?></li>
		  <li><?= $html->link("Change User Permissions", array("controller" => "users", "action" => "changeTier")) ?></li>	
		 </ul>

	 </li>
	  </ul>


</div>

 
  </div>


			
		
		

	<hr class="thickBar hidden"/>
	<hr class="skinnyBar hidden" />
	<div class="clear"></div>
	</div><!-- end header-->
</div>
	
	
<div id ="container" class="container_16 content-container clearfix vcalendar vMarginBottom_4">	
 
	<?php echo $content_for_layout; ?>
		
</div><!--end container-->
<div class="footer">
<?php if (isset($this->params["named"]["calendar"])) {
	echo $html->link( "Administrators for this calendar", 
		array(
			"controller" => "users",
			"action" => "administrators",
			$this->params["named"]["calendar"] 
		) 
	);
}?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-1218730-27");
pageTracker._trackPageview();
} catch(err) {}</script>

</div>
 

<div class="clear"></div>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript" src="http://use.typekit.com/xof1kob.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php 	
	echo $javascript->link("/min/?b=$cssJSLink"."js&f=explode.js,home.js,datepicker.js,jquery.cycle.lite.min.js,timepicker.js");

	//echo $javascript->link('explode');
	//echo $javascript->link('home');		
	//echo $javascript->link('datepicker');
	//echo $javascript->link('jquery.cycle.lite.min');
	//echo $javascript->link('timepicker'); 
	echo $scripts_for_layout;
?>

<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){	
	if (<?php echo ($isLogged ? "true" : "false") ?>) {
		$("#registerLink").hide();
		$("#signinbutton span").html("Log out"); 
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
		$("#signinbutton").unbind("click").click(function() {
			document.location= rootUrl + 'users/logout/';
   		});
	} else {
		$("#menuAdmin").css("visibility", "hidden");
	}	
		
});
//]]>
</script>


</body>
</html>