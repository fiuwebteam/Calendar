<div class="topBar"><p>empty space</p></div>
<div id ="container" class="container_16 clearfix">
	<div class="header grid_16">
		<div class="logo grid_5 alpha">
			<?php echo $html->image('fiulogowhite.gif')?>
			<h1 class="cal_title vPaddingTop_0">University Calendar</h1>
		</div>
		<div class="searchbox push_1 grid_6">
			<span class="highlight_desc">Search for an event</span>
			<div class="search vMarginTop_0">
				<form action="" method="get">
					<fieldset>
						<input type="text" id="searchbar" />
						<input type="submit" value="Search" id="searchbtn" class="button submitBtn" />
					</fieldset>
				</form>

			
			
			</div>
		</div>
		<div class="signinBox grid_3 push_2 omega">
			<span class="highlight_desc">Need to register?</span>
				<a class="signin button vMarginTop_0" tabindex="3" href=""><span>Sign in</span></a>
		
			<fieldset class="common-form standard-form" id="signin_menu" style="display: block;">
   				 <form action="" id="signin" method="post">
  					<input type="hidden" value="" name="authenticity_token" id="authenticity_token">  <input type="hidden" value="false" name="return_to_ssl" 		
  						id="return_to_ssl">
 					 <p class="textbox">
 					     <label for="username">Username</label>
    					 <input type="text" tabindex="4" title="username" value="" name="session[username_or_email]" id="username">
  					 </p>

  					 <p class="textbox">
    					<label for="password">Password</label>
    					<input type="password" tabindex="5" title="password" value="" name="session[password]" id="password">
  					</p>

  					<p class="remember">
   						 <input type="submit" class="button" tabindex="7" value="Sign in" id="signin_submit">
    					 <input type="checkbox" tabindex="6" value="1" name="remember_me" id="remember">
    					 <label for="remember">Remember me</label>
 					 </p>

  					<p class="forgot">
    					<a id="resend_password_link" href="">Forgot password?</a>
  					</p>

  					<p class="forgot-username">
    					<a title="If you remember your password, try logging in with your email" id="forgot_username_link" href="">Forgot username?</a>
  					</p>
   
  
  					<input type="hidden" value="" id="signin_q" name="q">
  				</form>
			</fieldset>
		</div><!-- end signinBox -->
		
	</div><!-- end header-->
	<hr class="blackBar grid_16"/>
	
		
	<div class="catFace grid_16 clearfix vMarginTop_3">
		
		<div class="directions vPaddingTop_0 vPaddingBottom_0">
		<?php echo $html->image('point-finger.png', array('class'=>'finger'))?>
		
		<span class="info vPaddingTop_0 vPaddingBottom_0">To find an event, use the following filters below</span></div>
		<!-- <div class="catSelect grid_3">
			<a id="select_off" class="button">Deselect All</a>
			<a id="select_on" class="button">Select All</a>
		</div>
		-->
		<div class="catBox grid_16 vMarginTop_0 alpha">	
			
			<div class="clearfix heading_row alpha">
					<h4 class="catTitle">Category:</h4>
			</div>
	
			
			<div class="catContentBox clearfix alpha">
				<!-- <p>Please select a category of the event you are searching</p>-->
				<!-- <hr class="catLine"/>-->
				
				<!-- <hr class="catLine"/>-->
				<ul class="catList catSpecial clearfix">
					<li class=""><a id="academics_on" class="boxes">Academics</a></li>
					<li class=""><a id="alumni_on" class="boxes">Alumni &amp; Community</a></li>
					<li class=""><a id="arts_on" class="boxes">Arts &amp; Entertainment</a></li>
					<li class=""><a id="athletics_on" class="boxes">Athletics</a></li>
					<li class=""><a id="lectures_on" class="boxes">Lectures &amp; Conferences</a></li>
					<li class=""><a id="student_on" class="boxes">Student Life</a></li>
					<li class=""><a id="faculty_on" class="boxes noRightBorder">Faculty &amp; Staff Life</a></li>	
				</ul>
			</div><!--end catContentBox-->
		</div><!-- end catBox -->
		
		
		<div class="catBreak">
			<hr/>
		</div>
		
		
		<div class="clear"></div>
		<div class="catBox grid_8  alpha">	
			
			<div class="clearfix heading_row grid_2 alpha">
			 	<h4 class="catTitle">Date:</h4>
			</div>
			
			
			<div class="catContentBox clearfix">
				<!-- <p>Please select a date(s) of the event you are searching</p> -->
				<!--<hr class="catLine"/>-->
				<ul class="catList catSpecial clearfix">
					<li class=""><a id="" class="calButton date">This Week</a></li>
					<li class=""><a id="calButton_on" class="calButton">This Month</a></li>
					<li class=""><a class="calButton">This Year</a></li>
					<li class=""><a class="calButton noRightBorder">Custom</a></li>
				</ul>
			</div>			
		</div><!--end catBox-->
		<div class="customBox grid_4 vPaddingTop_1 vPaddingBottom_2">
			<span id="customC" class="customConnector"></span>
			<p>Popular Dates to Use</p>
			<ul class="catList clearfix vPaddingBottom_1">
				<li><a href="">Fall 2010</a></li>
				<li><a href="">Spring 2011</a></li>
			</ul>
			<p>Specify Start/End Date:</p>
		</div>
		
		<div class="catBreak2">
			<hr/>
		</div>
		
		<div class="clear"></div>
		
		<div class="catBox eventBox grid_4 alpha">
			<div class="clearfix heading_row grid_2 alpha">
			 	<h4 class="catTitle">Event:</h4>
			</div>
			
			<div class="catContentBox clearfix">
				
				
				<ul class="catList catSpecial">
					<li class=""><a id="calButton_on" class="calButton">All</a></li>
					<li class=""><a class="calButton noRightBorder">Events</a></li>
				</ul>
				
				
			</div>		
		</div><!--end catBox-->
		
			
	</div> <!--end catface -->
	<hr class="blackBar2 vMarginTop_3 grid_16"/>
</div><!--end container-->
<div class="clear"></div>
