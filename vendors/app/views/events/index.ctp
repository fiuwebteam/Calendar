
<!--js for buttons and tabs interaction -->
<?php echo $this->element('interactiveJs'); ?>
 <?php
$paginator->options(array('url' => $this->passedArgs));
?>
<div id="fb-root"></div> 
<div class="clear"></div>
 
 
<div class="calBody alpha omega" >

	<!--featured event -->
	  <?php echo $this->element('featuredEvent'); ?>
	<div class="clear"></div>
	
	<!--view: upcoming|week|month|custom|most liked--> 
	<?php echo $this->element('viewButtons'); ?>

	<!--add event|category|calendars-->			
     <?php echo $this->element('tabs'); ?>
	
	<div class="paging grid_7 push_2  omega  ">
		<?php echo $this->element('topPaginator'); ?>	
	</div>
	<div class="clear"></div>
	
<!--List Events. Element inludes: facebook FQL build, bottom paginators. -->
   <?php
	if (isset($this->params["named"]["sort"])) {
		echo $this->element('eventList');
	} else {
		echo $this->element('eventDayList');
	}
   ?>

  <?php $FQL = Configure::read('FQL');  ?>
  <?php //echo "FQLL ".$FQL; ?>



</div><!--end container-->



<script>
//Facebook API

  /*  window.fbAsyncInit = function() {
    FB.init({
      appId      : '120438498689', // App ID
      channelURL : '//WWW.fiu.edu/channel.html', // Channel File
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      oauth      : true, // enable OAuth 2.0
      xfbml      : true  // parse XFBML
    });

    // Additional initialization code here
    
    
    //listen for liked event    
  FB.Event.subscribe('edge.create',
    function(response) {
         var e=$('#fblike fb\\:like').attr('ref');
        //alert('You liked the URL: ' + e);
     	// $.post('/events/fblike/'+e);
  
	});

	//query FB for event url like count then send results (r) to events/fblikes
   
	 FB.api(
	  	{
	    	method: 'fql.multiquery',
	    	queries:{<?php //echo $FQL;?>}
	  	},
	  function(r) {
	   
	    //alert(eventLikes[9351]);
		 r = '({\"data\":'+JSON.stringify(r)+'})';
		 r = eval(r);       
		$.ajax({
		   type: 'POST',
		   dataType: 'text',
		   url: '/events/fblike/',
		   data: r,
		   success: function(response) {
		      //alert(response);
		   }
		});  
	  });
	};

*/

  // Load the FB SDK Asynchronously
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


 

