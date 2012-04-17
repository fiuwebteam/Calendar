<?php

$this->set('meta_title',  $event['Event']['title']);
$this->set('meta_location',$this->params['url']['url']);
$this->set('meta_image',"http://". $_SERVER['HTTP_HOST'].$this->webroot."img/fiu-fb.jpg");
$this->set('meta_type', 'article');
$this->set('meta_description' , $event['Event']['description']);
$this->set('meta_url', "http://". $_SERVER['HTTP_HOST'].$this->webroot.$this->params['url']['url']);;  



$js ="
$(document).ready(function(){
	$('#eventAddButton').click(function() {
		var loc = '".$html->url(array("controller" => "events", "action" => "appendEventToCalendar", $this->params["pass"][0]), true)."';
		loc += '/' + $('#calendarChooser').val();
		window.location = loc;
	});
});
";


$javascript->codeBlock( $js , array('inline'=>false));
$currentHref="http://". $_SERVER['HTTP_HOST'].$this->webroot.$this->params['url']['url'];
//"http://".$_SERVER['HTTP_HOST'].$html->url($event['Event']['id'])."/"; 

?>
 
<div id="fb-root"></div>
<div class="grid_2 omega vMarginTop_0">
	<?php
		$pos = strpos($_SERVER["HTTP_REFERER"], $_SERVER["HTTP_HOST"]);
			if($pos !== false) {
					echo "<a class='btn vMarginBottom_1' href='{$_SERVER["HTTP_REFERER"]}'>&#9668; Back</a>";
			}
	?>
</div>
<div class="clear"></div>
	<div class="events view grid_11  omega">

	

	<dl><?php $i = 0; $class = 'altrow';?>
		<dt class="grid_2 alpha  <?php if ($i % 2 == 0) echo $class;?>"><?php __('Title:'); ?></dt>
		<dd class="grid_8 alpha omega  <?php if ($i++ % 2 == 0) $class;?>">
			<?php echo $event['Event']['title']; ?>
		 
		<br/><!---facebook like button -->
	
	 	
	 	
<div id="fblike" class="fb-like" syle="text-align:center;width:80px;overflow:hidden;" >		
			 <fb:like  href="<?php echo $currentHref;?>" layout="button_count" ref="<?php echo $event['Event']['id'];?>" show_faces="false" data-send="false" width="240" height="40" action="like" colorscheme="light"></fb:like></div>	
		</dd>
	 
		<dt class="grid_2 alpha <?php if ($i % 2 == 0) echo $class;?>"><?php __('Date/Time:'); ?></dt>
		<dd class="grid_8 alpha <?php if ($i++ % 2 == 0) echo $class;?>">
			<?php echo $dateOutput; ?>
		</dd>

				
		<dt class="location grid_2 alpha <?php if ($i % 2 == 0) echo $class;?>"><?php __('Location:'); ?></dt>

		<dd class="grid_8 alpha omega  <?php if ($i++ % 2 == 0) $class;?>">
			<?php echo $event['Event']['location']; ?>
		</dd>
		
		
		<dt class="desc" <?php if ($i % 2 == 0) echo "class='$class' ";?>><?php __('Description:'); ?></dt>
		<dd class="summary grid_10 descriptionContent alpha omega <?php if ($i++ % 2 == 0) echo $class;?>">
			<?php if (isset($event['Event']['flyer']) && $event['Event']['flyer'] != "") echo $html->image('flyers/' . $event['Event']['flyer'], array('style' => "float:right; padding:5px;") ); ?>
			<?php echo nl2br($event['Event']['description']); ?>
		</dd>
		
	
		<!-- <dt class="grid_2 alpha  <?php if ($i % 2 == 0) echo $class;?>"><?php __('Category:'); ?></dt>
		<dd class="grid_8 alpha omega  <?php if ($i++ % 2 == 0) echo $class;?>">
			<?/* php echo $html->link($event['Category']['title'], array('controller' => 'categories', 'action' => 'view', $event['Category']['id'])); */ ?>
			
		</dd>-->

				
		
		<dt class="grid_2 alpha  <?php if ($i % 2 == 0) echo $class;?>"><?php __('Contact:'); ?></dt>
		<dd class="grid_8 alpha omega <?php if ($i++ % 2 == 0) echo $class;?>">
			<?php echo $event['Event']['contact']; ?>
			
		</dd>
		
		<?php if ($event['Event']['email'] != "") { ?>
		
		
		<dt class="grid_2 alpha   <?php if ($i % 2 == 0) echo $class;?>"><?php __('Email:'); ?></dt>
		<dd class="grid_8 alpha omega <?php if ($i++ % 2 == 0) echo $class;?>">
			<a href='mailto:<?php echo $event['Event']['email']; ?>'><?php echo $event['Event']['email']; ?></a>
		</dd>
		
		<?php } ?>
		<?php if ($event['Event']['phone'] != "") { ?>
		
		
		
		<dt class="grid_2 alpha <?php if ($i % 2 == 0) echo $class;?>"><?php __('Phone:'); ?></dt>
		<dd class="grid_8 alpha omega <?php if ($i++ % 2 == 0) echo $class;?>">
			<?php echo $event['Event']['phone']; ?>
		
		</dd>

		
		<?php } ?>
		<?php if ($event['Event']['url'] != "") { ?>
		
		
	
		
		<dt class="grid_2 alpha url <?php if ($i % 2 == 0) echo $class;?>"><?php __('Url:'); ?></dt>
		<dd class="grid_8 alpha omega <?php if ($i++ % 2 == 0) echo $class;?>">
			<a href='<?php echo $event['Event']['url']; ?>'><?php echo $event['Event']['url']; ?></a>			
		</dd>
		
		<?php } ?>
		
		
		<dt class="grid_2 alpha  <?php if ($i % 2 == 0) echo $class;?>"><?php __('Type:'); ?></dt>
		<dd class="grid_8 alpha omega  <?php if ($i++ % 2 == 0) echo $class;?>">
			<?php switch ($event['Event']['type']) {
				case 1:
					echo "Normal";
					break;
				case 2:
					echo "Ongoing";
					break;
				case 3:
					echo "Deadline";
					break;
			} ?>
		</dd>
		
		<dt class="grid_2 alpha  <?php if ($i % 2 == 0) echo $class;?>"><?php __('Source:'); ?></dt>
		<dd class="grid_8 alpha omega  <?php if ($i++ % 2 == 0) echo $class;?>">
			<?php echo $calendarName;  ?>
		</dd>
		
	
		
	</dl>
  </div>
 


<?php if (isset($owner)) { ?>
<div class=" grid_5 events omega">
<div class="social-tools related vMarginTop_1 vPaddingTop_0 vPaddingBottom_0 grid_4 alpha omega">
	<?= $html->link("Edit", array("controller" => "events", "action" => "edit", $this->params["pass"][0]),array("class"=>'boxes')); ?>
	<?= $html->link("Drop", array("controller" => "events", "action" => "delete", $this->params["pass"][0]),array("class"=>'boxes'), "Are you sure you want to drop this event?"); ?>
</div>
<?php } ?>

<?php if (isset($calendars)) { ?>
<div class="eventsRelated vMarginTop_1 vPaddingTop_0 vPaddingBottom_0  grid_4  omega">
	<h3 style="font-size:15px;">Add this event to a calendar:</h3>
	<div class="vMarginBottom_1">
	<?= $form->select('calendarChooser', $chooser->arrayOutput($calendars), null, array("style" => "width:200px;") ); ?>
	</div>
	<input class='btn smaller' type ='button' value='Go' id='eventAddButton' name='eventAddButton'/>
</div>	
<?php } ?>

<div class="social-tools vMarginTop_0 vPaddingTop_0 vPaddingBottom_0 grid_4 alpha omega">
	<?php $id = $this->params["pass"][0];?>
	<?php echo $html->image('pdf-logo-lrg.gif', array( "url" => array( "action" => "pdf", "id:$id"), "alt" => "To PDF", "id"=>"pdf-img"))?>
	<?php echo $html->image('rss-lrg.gif', array( "url" => array( "action" => "rss", "id:$id"), "alt" => "Subscribe to Category","id"=>"rss-img"))?>
	<?php echo $html->image('cal-lrg.gif', array( "url" => array( "action" => "ical", "id:$id"), "alt" => "Add to your Calendar","id"=>"cal-img"))?>
</div>


<!-- AddThis Button BEGIN -->
<div class=" social-tools vMarginTop_2 vPaddingTop_1 vPaddingBottom_2 grid_4 alpha omega addthis_toolbox addthis_default_style addthis_32x32_style">
<a class="addthis_button_preferred_1"></a>
<a class="addthis_button_preferred_2"></a>
<a class="addthis_button_compact"></a>
<a class="addthis_counter addthis_bubble_style"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4d8a095f17af53f9"></script>
<!-- AddThis Button END -->

<div class="eventsRelated vMarginTop_2 vPaddingTop_0 vPaddingBottom_2 grid_4 omega">
<div class="related">
	<h3><?php __('Related Calendars');?></h3>
	<hr class="dashedEnclosed vPaddingBottom_0"/>
	<?php if (!empty($event['Calendar'])):?>
	<ul class="vPaddingTop_0 vPaddingBottom_1">
	<?php
	$i = 0;
	$marked = array();
	foreach ($event['Calendar'] as $calendar):
		if (in_array($calendar["id"], $marked)) {
			continue;
		}
		$marked[] = $calendar["id"];
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>		
	<li <?php echo $class;?>><?php echo $html->link($calendar['title'], array('controller'=>'events','action'=>'index','calendar:'.$calendar['url']),array('class'=>'boxes'));?></li>
	<?php endforeach; ?>
	</ul>
	
<?php endif; ?>

</div>
<div class="relatedCat vPaddingTop_1">
	
	<h3><?php __('Category');?></h3>
	<hr class="dashedEnclosed vPaddingBottom_1"/>
	<?php if ($i++ % 2 == 0) echo $class;?>
		<?php echo $html->link($event['Category']['title'], array('controller' => 'events', 'action' => 'index', 'calendar:main' . '/category:' . $event['Category']['id']),array('class'=>'boxes categoryOn_'.$event["Event"]["category_id"])); ?>
	
</div>

<div class="related tags vPaddingTop_2">
	
	<h3><?php __('Related Tags');?></h3>
	<hr class="dashedEnclosed vPaddingBottom_1"/>
	
	<?php if (!empty($event['Tag'])):?>
	<?php foreach ($event['Tag'] as $tag): ?>
		<?php echo $html->link(__($tag['title'], true), array('controller' => 'tags', 'action' => 'view', $tag['title']),array('class'=>'boxes')) . " ";?>
	<?php endforeach; ?>
	<?php endif; ?>
	
    
	
</div>
</div><!-- end EventsRelated-->
</div><!-- sidebar -->


<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '279171232143928', // App ID
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      oauth      : true, // enable OAuth 2.0
      xfbml      : true  // parse XFBML
    });

    // Additional initialization code here
    
    
    //like event    
    FB.Event.subscribe('edge.create',
    function(response) {
         var e=$('#fblike fb\\:like').attr('ref');
        //alert('You liked the URL: ' + e);
     // $.post('/events/fblike/'+e);
 
	

	//get count for event and update db
	/* FB.api(
  	{
    	method: 'fql.query',
    	query: 'SELECT like_count, share_count FROM link_stat WHERE "<?php //echo $currentHref; ?>" IN url  ORDER BY like_count LIMIT 1;'
  	},
  function(r) {
   //update db for this event
     var e=$('#fblike fb\\:like').attr('ref');
     $.post('/events/fblike/'+e+'/'+r[0].like_count);
     //alert('like count =  '+r[0].like_count);

  });*/

 




	 FB.api(
	  	{
	    	method: 'fql.query',
	    	query:'SELECT like_count, share_count FROM link_stat WHERE "<?php echo $currentHref; ?>" IN url  ORDER BY like_count LIMIT 1;'
	  	},
	  function(r) {
	  
	     
	     r = r[0].like_count;
	     var e=$('#fblike fb\\:like').attr('ref');
	    //r = '({\"data\":'+JSON.stringify(r)+'})';
	   
	    // r = eval(r);   
	    r={data:{id:e, fblike:r}};
		$.ajax({
		   type: 'POST',
		   dataType: 'text',
           url: '/calv2/events/fblike',
     	   data: r,
		   success: function(response) {
		      //alert(response);
		   }
		});  
	  });
	 
});




 FB.Event.subscribe('edge.remove',
    function(response) {
    
     FB.api(
	  	{
	    	method: 'fql.query',
	    	query:'SELECT like_count, share_count FROM link_stat WHERE "<?php echo $currentHref; ?>" IN url  ORDER BY like_count LIMIT 1;'
	  	},
	  function(r) {
	  
	     
	     r = r[0].like_count;
	     var e=$('#fblike fb\\:like').attr('ref');
	    //r = '({\"data\":'+JSON.stringify(r)+'})';
	   
	    // r = eval(r);   
	    r={data:{id:e, fblike:r}};
		$.ajax({
		   type: 'POST',
		   dataType: 'text',
 		   url: '/events/fblike',
		   url: '/calv2/events/fblike',
		   data: r,
		   success: function(response) {
		      //alert(response);
		   }
		});  
	  });
    });
    
    
    
  };






  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     d.getElementsByTagName('head')[0].appendChild(js);
   }(document));

 </script>
 
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>


<script>

</script>
