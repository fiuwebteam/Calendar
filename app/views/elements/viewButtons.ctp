<!--end category tab -->		
		 
		<!--pop up date -->
				<div class="popDate vPaddingBottom_1" id='popularDates'>
				
				<span style="float: right;margin-right:1em"><a id="close" href="#" style="color: red;text-decoration:none;outline:none";>X</a></span>

					    <!-- @Custom Box -->
    	<div class="customBox1">
				<span class="customConnector"></span>
				
				<?php
				//build url for custom form post action 
				$customAction =  '/'.$this->params['controller'].'/'.$this->params['action'].'/calendar:'.$this->params['named']['calendar'].'/custom:1/';
				?>
				
				<ul class="clearfix vPaddingTop_0 vPaddingBottom_0">
				
					<li class="calChooser">
						<form name='userDefinedDates' action='<?=$customAction?>' method='post'>
							<label for="dateStart">Start Date: </label>
							<input alt="Start Date" class="cDate" type='text' name='start' id='dateStart' readonly="readonly" value='<?php 
							if (isset($this->params["named"]["start"])) { echo date("M j, Y", strtotime($this->params["named"]["start"])); }
							else {echo date("M j, Y");}
							?>'></input>
							
							
							
							<span>to</span>
							<label for="dateEnd">End Date: </label>
							<input alt="End Date" class="cDate" type='text' name='end' id='dateEnd' readonly="readonly" value='<?php
							if (isset($this->params["named"]["end"])) { echo date("M j, Y", strtotime($this->params["named"]["end"])); }
							else {echo date("M j, Y", strtotime("today +7 days"));}
							?>'></input> 
							
						<input class='button submitBtn cDateButton' type='submit' value='Submit'/>
		
					</form>
				</li>
		</ul>
		
<!--popup dates were here-->
		</div><!-- end customBox-->
	
					<p>These dates will populate for you automatically</p>
					<ul class="clearfix vPaddingTop_0">
						<?php
						$custom = $noDate;
					    $custom['custom']='1';
					    
					    $customDates = array();
					    
					    $customDates["Spring"] = array("start" => "-01-01", "end" => "-04-30", "blurb" => "(January to April)" );
					    $customDates["Summer"] = array( "start" => "-05-01",  "end" => "-08-31",
					    	"A" => array( "start" => "-05-01",  "end" => "-06-30", "blurb" => "(May to June)" ),
					    	"B" => array( "start" => "-06-01",  "end" => "-08-31", "blurb" => "(June to August)" ),
					    	"C" => array( "start" => "-05-01",  "end" => "-08-31", "blurb" => "(May to August)" )
					    );
					    $customDates["Fall"] = array("start" => "-08-01", "end" => "-12-31", "blurb" => "(August to December)" );
					    
					    function echoSemesterLink($custom, $year, $key, $value, $html) {					    	
					    	if ($key == "Summer") {					    		
					    		foreach ($value as $sKey => $sValue) {
					    			if ($sKey == "start" || $sKey == "end") { continue; }
					    			echoSemesterLink($custom, $year, "$key $sKey", $sValue, $html );
					    		}
					    	} else {
					    		$custom["start"] = $year . $value["start"];
								$custom["end"] = $year . $value["end"];
					    		echo "<li>";
					    		echo $html->link("$key $year {$value["blurb"]}" , $custom, array('rel'=> "$key Semester"));
					    		echo "<li>";
					    	}
					    	
					    }
					     
					    $todayArray = explode("-", $today);
					    
					    if ($todayArray[1] < 4) {
					    	echoSemesterLink($custom, ($todayArray[0] - 1) , "Fall", $customDates["Fall"], $html);
					    	echoSemesterLink($custom, $todayArray[0], "Spring", $customDates["Spring"], $html);
					    	echoSemesterLink($custom, $todayArray[0], "Summer", $customDates["Summer"], $html);			    	
					    } else if ($todayArray[1] < 8) {
					    	echoSemesterLink($custom, $todayArray[0], "Spring", $customDates["Spring"], $html);
					    	echoSemesterLink($custom, $todayArray[0], "Summer", $customDates["Summer"], $html);
					    	echoSemesterLink($custom, $todayArray[0], "Fall", $customDates["Fall"], $html);	
					    } else {
					    	echoSemesterLink($custom, $todayArray[0], "Summer", $customDates["Summer"], $html);
					    	echoSemesterLink($custom, $todayArray[0] , "Fall", $customDates["Fall"], $html);
					    	echoSemesterLink($custom, ($todayArray[0] + 1), "Spring", $customDates["Spring"], $html);	
					    }
					    ?>
					</ul>
					
						
					
					
					
				</div><!--end popDate-->
		
		
		<!--category dropdown -->
		<div id="categoryDropDown"> 
		<span style="float: right;margin-right:.5em"><a id="closecat" href="#" style="color: red;text-decoration:none;outline:none";>X</a></span>

		<?php
			foreach($categories as $key => $category) {
				$noCategory["category"] = $key;
				$categoryFlag = "Off_";
				if (isset($this->params["named"]["category"]) && $this->params["named"]["category"] == $key ) {
					$categoryFlag = "On_";	
				} else if (!isset($this->params["named"]["category"]) && $key == 0) {
					$categoryFlag = "On_";	
				}
				echo "<li>" . $html->link($category, $noCategory , array('rel'=>"Select $category",'class'=>"category$categoryFlag$key boxes ds")) . "</li>";
			}
		?>
		
		</div>
		<!--end category dropdown -->
		
	<!-- type content new -->
	
	<div class="catContentBox grid_4 alpha clearfix" id="typeContentBox">
				
				
				<ul class="vPaddingBottom_2">
						<?php 
							$class = "calButton_off";
							if (!isset($this->params["named"]["type"])) {
								$this->params["named"]["type"] = null;
								$class = "calButton_on";
							}
						?>
						<li>
							<?= $html->link("All", $noType, array('rel'=>'Show All Events and Deadlines', 'class'=>$class)) ?>
						
						</li>
						<?php 
							$noType["type"] = 1;
							$class = "calButton_off";
							if ($this->params["named"]["type"] == 1) {
								$class = "calButton_on";
							}
						?>
						<li>
							<?= $html->link("Events", $noType, array('rel'=>'Show Events Only','class'=>$class)) ?>
						</li>
						<?php 
							$noType["type"] = 3;
							$class = "calButton_off";
							if ($this->params["named"]["type"] == 3) {
								$class = "calButton_on";
							}
						?>
						<li>
							<?= $html->link("Deadlines", $noType , array('rel'=>'Show Deadlines Only','class'=>$class)); ?>
						</li>
	
				</ul>
				
				
			</div>		
	
	<!--end type content new-->
	
	
	<!--calendars dropdown content new -->
		<div id="calendarsChoice">
		 		<span style="float: right;margin-right:.5em"><a id="closecal" href="#" style="color: red;text-decoration:none;outline:none";>X</a></span>
 
			 		(<?php echo $html->link("Embed Calendar", array("controller" => "events", "action" => "plug",'calendar'=>$this->params['named']['calendar'] ),array('rel'=>'embed a Calendar','class'=>'embedCalText','target'=>'_blank')); ?>)
		
	 
			
			<div id="calendarChoiceDropDown">	
				
				<?= $chooser->selectOutput($calendarsList); ?>		
			</div>
				
		</div>
	
<!--end calendars dropdown content new -->	

<?php 
 /**For upcoming: month **/
	$noDate["start"] = date("Y-m-d"); 
	$noDate["end"] = date("Y-m-d", strtotime("today +30 days"));
	$class = "calButton_off";
	if(
		isset($this->params["named"]["start"]) &&
		isset($this->params["named"]["end"]) &&
		$this->params["named"]["start"] == $noDate["start"] &&
		$this->params["named"]["end"] == $noDate["end"]
	) {
		$class = "calButton_on";
	}
?>	

<div id="monthViewDropDown">
<?= $html->link("List", $noDate, array('rel'=>'List view','class'=> $class, 'id'=>'listMonthViewButton', 'style'=>'float:right') );?> <?= $html->link("Grid", '/events/month/', array('rel'=>'Grid view','class'=> $class, 'id'=>'gridMonthViewButton') );?>
</div>
		
			<!--Events--> 
		<div id="mEvents" class=" noTopMargin vPaddingBottom_2   noTopShadow alpha omega">
		
		
		
		
		
		<!--View Events Tab-->
		
<?php
     /**For upcoming: week **/
	
	/*
	
	$noDate["start"] = date("Y-m-d", strtotime('last sunday') );
	$noDate["end"] = date("Y-m-d", strtotime("this saturday"));
	
	
	if (($this->params["named"]["start"] == $noDate["start"] &&
		$this->params["named"]["end"] == $noDate["end"]) ||
		!isset($this->params["named"]["start"])) {
		$upcomingClass = "calButton_off";
	}
	*/
	
	/*
	if(
		($this->params["named"]["start"] == $noDate["start"] &&
		$this->params["named"]["end"] == $noDate["end"]) ||
		!isset($this->params["named"]["start"])
	) {
		$class = "calButton_on";
	}
	*/
	//for upcoming view. long list
	/*
	if (
	 	!isset($this->params["named"]["limit"]) && 
	 	$this->params["named"]["limit"] != 500
	 ) {
    	$upcomingClass = "calButton_off";
    } else {
    	$upcomingClass = "calButton_on";
    	$class ="calButton_off";
    }
    	//for month/grid view	
    if ( $this->params["action"] != 'month' ) {
    	$monthClass = "calButton_off";
    } else {
    	$monthClass = "calButton_on";
    	$class = 'calButton_off';
    }
    	
    //for fblike list
    if (!isset($this->params["named"]["fblike"])){
    	$fblikeClass = "calButton_off";
    } else {
    	$fblikeClass  = "calButton_on";
    	$class ="calButton_off";
    }
    	
    		//for custom views
    if (!isset($this->params["named"]["custom"])) {
    	$customClass = "calButton_off";
    } else {
    	$customClass  = "calButton_on";
    	$class ="calButton_off";
    }
    */

	if (!isset($this->params["named"]["start"])) {
		$this->params["named"]["start"] = date("Y-m-d");
	}
	if (!isset($this->params["named"]["end"])) {
		$this->params["named"]["end"] = date("Y-m-d", strtotime("today +365 days"));
	}
	// upcoming is the default
	$upcomingClass = "calButton_on";
	$weekClass = $monthClass = $fblikeClass = "calButton_off";
?> 
		
	 <div id="midregion">
		<ul id="upperTabs" class="topTabs">
           <li id="txtButton"><a href="#">View:</a></li>
			
			
			<li id="Upcoming">
				<?php
				// Moved the logic here so it's easier to find.
				$upcoming = $noDate;
			    $upcoming["start"] = date("Y-m-d"); 
				$upcoming["end"] = date("Y-m-d", strtotime("today +365 days"));
			    $upcoming['limit']= 100;
			    
			    if (
			    	(
			    		isset($this->params["named"]["start"]) && 
			    		isset($this->params["named"]["end"])
			    	) && (
				    	$this->params["named"]["start"] != $upcoming["start"] ||
				    	$this->params["named"]["end"] != $upcoming["end"]
			    	)
			    ) {
					$upcomingClass = "calButton_off";
				}
				
				echo $html->link("Upcoming", $upcoming, 
					array( 
						'rel'=>'List view',
						'class'=> $upcomingClass, 
						'id'=>'listMonthViewButton', 
						'style'=>'float:right'
					) 
				);
				?>
			</li>
			<li>
				<?php 
				$weekParams = $noDate;
				$weekParams["start"] = date("Y-m-d", strtotime('last sunday') );
				$weekParams["end"] = date("Y-m-d", strtotime("this saturday"));
				$weekParams['limit']= 20;
				
				if (
			    	(
			    		isset($this->params["named"]["start"]) && 
			    		isset($this->params["named"]["end"])
			    	) && (
				    	$this->params["named"]["start"] == $weekParams["start"] ||
				    	$this->params["named"]["end"] == $weekParams["end"]
			    	)
			    ) {
					$weekClass = "calButton_on";
				}
				
				echo $html->link("Week", $weekParams, 
					array(
						'rel'=>'Select this week', 
						'class'=> $weekClass
					) 
				); 
				?>
			</li>
			
			<li id="Month">
				<?php 
				
				if ( $this->params["action"] == 'month' ) {
			    	$monthClass = "calButton_on";
			    }
				echo $html->link("Month", "/events/month/calendar:$calendar", 
					array(
						'rel'=>'Grid view',
						'class'=> $monthClass, 
						'id'=>'gridMonthViewButton'
					) 
				);
				?>
			</li>
 
		 
<!--Custom  -->
			<?php
			if (isset($this->params["named"]["custom"])) {
		    	$customClass = "calButton_on";
		    } else {
		    	$customClass  = "calButton_off";
		    }
			?>
			<li>
				<a rel="Select by Semester" href="#" class="<?=$customClass?> popButton" id='customButton'>Custom</a>
			</li>
			
		 	<li>
		 		<?php 
		 		
		 		 if (isset($this->params["named"]["fblike"])){
			    	$fblikeClass = "calButton_on";
			    }
			    
		 		echo $html->link("Most Liked", "index/calendar:$calendar/fblike:yes", 
		 			array(
		 				'rel'=>'Sort by Likes', 
		 				'class'=>$fblikeClass
		 			)
		 		);?>
		 	</li>			 
		</ul>
<!-- end topTabs -->

 	</div>	
		
		
		
		
		
		
			<div class="headingBox left">
			<h2 class="titles vPaddingTop_1 vPaddingBottom_0 grid_11 omega">
				<?php
				if (!isset($this->params["named"]["fblike"])) {
					echo $calendarName;
					if ($this->params["named"]["type"] == 3) {
						echo " Deadlines";
					} else if ($this->params["named"]["type"] == 1) {
						echo " Events";
					} else {
						echo " Events and Deadlines";
					}
				}else echo "Most Liked Events";
				?> <br/><span id="dateRange">
					<!--date range displayed-->
				<?= date("F j, Y", strtotime($this->params["named"]["start"])) . 
					' - ' . date("F j, Y", strtotime($this->params["named"]["end"]));
				?>
				</span>
			</h2>
 
				<div class="social-tools vMarginTop_1">
				
						<?php 
						$startDate = "";
						if (isset($this->params["named"]["start"])) { $startDate = date("Y-m-d", strtotime($this->params["named"]["start"])); }
						else {$startDate = date("Y-m-d", strtotime("sunday -1 week"));}
						
						$endDate = "";
						if (isset($this->params["named"]["end"])) { $endDate = date("Y-m-d", strtotime($this->params["named"]["end"])); }
						else {$endDate = date("Y-m-d", strtotime("saturday"));}	
						
						$category = "";
						if (isset($this->params["named"]["category"])) { $category = $this->params["named"]["category"]; }							
						?>
					
					    <?php $image = $html->image('pdf-logo.gif', array("alt" => "To PDF", 'id'=>'pdf-img'));
						 echo $html->link($image, array("action" => "pdf", "calendar:$calendar?start=$startDate&end=$endDate&cat=$category"), array('rel'=>"Convert $calendarName to PDF",'escape' => false));?>
						
						<?php $image = $html->image('rss.gif', array("alt" => "Subscribe", 'id'=>'rss-img'));
						echo $html->link($image, array("action" => "rss", "calendar:$calendar"), array('rel'=>"RSS Feed of $calendarName",'escape' => false));?>
						
						<?php $image = $html->image('cal.gif', array("alt" => "Add to Calendar", 'id'=>'cal-img'));
						echo $html->link($image, array("action" => "ical", "calendar:$calendar"), array('rel'=>"Subscribe to $calendarName",'escape' => false));?>
						
						<?php $image = $html->image('excel.gif', array("alt" => "Open to your spreadsheet", 'id'=>'excel-img'));
						echo $html->link($image, array("action" => "spreadsheet", "calendar:$calendar?start=$startDate&end=$endDate&cat=$category"), array('rel'=>"Download the spreadsheet to $calendarName",'escape' => false));?>
</div>	
</div>