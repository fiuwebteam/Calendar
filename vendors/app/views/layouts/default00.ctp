<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		University Calendar - Florida International University
	</title>
	
	
	<?php
		echo $html->meta('icon') . "\n";
		echo $html->css('core/reset', NULL, array('media' => 'all')) . "\n";
		echo $html->css('core/text', NULL, array('media' => 'all')) . "\n";
		echo $html->css('core/960', NULL, array('media' => 'all')) . "\n";
		echo $html->css('front-general', NULL, array('media' => 'all')) . "\n";
		echo $html->css('vr', NULL, array('media' => 'all')) . "\n";
		echo $html->css('core/print', NULL, array('media' => 'print')) . "\n";
		
		echo '<!--[if IE]>';
		echo $html->css('ie-only');
		echo '<![endif]-->';
		
		echo '<!--[if IE 6]>';
		echo $html->css('ie6');
		echo '<![endif]-->';

		echo $scripts_for_layout;
	?>
	
</head>
<body>
<div id ="container" class="container_16 clearfix">
	<div class="header grid_16">
		<div class="logo grid_4 alpha">
			<h1>FIU - University Calendar</h1>
		</div>
		<div class="searchbox grid_4">
			<span>Search for an event</span>
			<div class="search">search box</div>
		</div>
		<div class="authbox grid_4 push_4 omega">
			<span>Need to register?</span>
			<div class="auth">
				<span>Sign in</span>
			</div>
		</div>
		
	</div>
	<!-- end header-->
    <div class="dateBox vMarginTop_1 grid_16 clearfix"><h5>Today is June 21st 2010</h5></div>
    <div class="catFace grid_16 clearfix vMarginTop_1">
        <div class="catNameBox grid_6 alpha">    
            <div class="clearfix heading_row vPaddingTop_1 vPaddingBottom_1 alpha">
                    <h4 class="catTitle">Category</h4>
                    <a id="select_off" class="select_off">Deselect All</a>
                    <a id="select_on" class="select_on">Select All</a>
            </div>
             <div class="clear"></div>
            <div class="catNames grid_6 clearfix alpha vPaddingTop_2 vPaddingBottom_2">
                <ul class="catList clearfix">
                    <li class=""><a id="academics_on" class="button">Academics</a></li>
                    <li class=""><a id="alumni_on" class="button">Alumni & Community</a></li>
                    <li class=""><a id="arts_off" class="button">Arts &amp; Entertainment</a></li>
                    <li class=""><a id="athletics_on" class="button">Athletics Events</a></li>
                    <li class=""><a id="lectures_on" class="button">Lectures &amp; Conferences</a></li>
                    <li class=""><a id="student_on" class="button">Student Life</a></li>
                    <li class=""><a id="faculty_off" class="button">Faculty &amp; Staff Life</a></li>    
                 </ul>
            </div>
        </div><!--end catnamebox-->
        <div class="catDateBox grid_6 alpha ">
            <div class="clearfix heading_row vPaddingTop_1 vPaddingBottom_1">
                 <h4 class="catTitle">When is it?</h4>
            </div>
            <div class="clear"></div>
            <div class="catDate vPaddingTop_2 vPaddingBottom_2 clearfix">
                <ul class="catList">
                    <li class=""><a id="calButton_on" class="button date">This Week</a></li>
                    <li class=""><a class="button calButton">This Month</a></li>
                    <li class=""><a class="button calButton">This Year</a></li>
                    <li class=""><a class="button calButton">Custom</a></li>
                 </ul>
             </div>

        </div><!--end catdatebox-->
        
        
        <div class="catTypeBox grid_3 alpha">
            <div class="clearfix heading_row vPaddingTop_1 vPaddingBottom_1 alpha">
                 <h4 class="catTitle">Type of Event</h4>
             </div>
             <div class="clear"></div>

            <div class="catType vPaddingTop_2 vPaddingBottom_2 clearfix">
                <ul class="catList">
                    <li class=""><a id="calButton_on" class="button calButton">All</a></li>
                    <li class=""><a class="button calButton">Events</a></li>
                 </ul>
            </div>
        </div><!--end catdatebox-->
                    
     </div> <!--end catface --></div>
</body>
</html>