<?php //$featured = $this->requestAction('events/index'); ?>


<div class="noname">
		<?php if (!empty($featured)) {?>
		<!-- Featured Events-->
	     <div class="featured grid_16 alpha omega vPaddingBottom_3 noBottomShadow">
			<div class="headingBox vPaddingBottom_1">
				<h4 class="titles vPaddingTop_1 ">Featured Event</h4>
			</div>
			<div class="clear"></div>
			<div id="featureBox" class="grid_16 alpha omega">
	
												
						<div class="hActionsList" id='featureList'>
							<?php
								$i = 0;
								foreach ($featured as $feature):
								$class = null;
								if ($i++ % 2 == 0) {
								$class = 'class="altrow"';
								}
						  	?>

							<div>
							  	<div class="grid_6 alpha">
							  		<?= ($feature['Event']['flyer'] == "") ? $html->image("flyers/default.gif",array('id' => 'noFeatureImg')) :  $html->image("flyers/".$feature['Event']['flyer'],array('id' => 'featureImg') ); ?>
							  	</div>
							  	<div class="grid_9 omega featureSummary">
							  		<h2><?php echo $html->link($feature['Event']['title'], array("action" => "view", $feature['Event']['id']),array('rel'=>"Select {$feature['Event']['title']}")); ?></h2>
									<h5><?php echo date("F j, Y" ,strtotime($feature['CalendarsEvent']['start_date_time'])); ?></h5>
									<span class="description">
									<?php echo $text->truncate($feature['Event']['description'], 300, array('ending'=>'...', 'exact'=> false)); ?>
									</span>									
							  	</div>								
								
								
							</div>
							
							
							<?php endforeach; ?>	
						</div>	
						
						<div id="featureArrows" class="grid_3 alpha omega">
							<a rel='Previous Feature Event' class="boxes prev grid_1" id='featurePrev' href='#' style='z-index:5'>Prev</a>
							<a rel='Next Feature Event' class="boxes next grid_1" id='featureNext' href='#' style='z-index:5'>Next</a>
						</div>					
			
				</div>
				<div class="clear"></div>	

			
	
			</div>
			

			
			<div class="clear"></div>		
		</div><!-- end featuredEvents-->
		<?php } ?>