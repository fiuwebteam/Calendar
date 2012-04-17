
	<?php //echo $html->charset(); ?>
	<html xmlns:og="http://opengraphprotocol.org/schema/"
      xmlns:fb="http://www.facebook.com/2008/fbml">
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

		
	?>
	
<script type="text/javascript" src="http://use.typekit.com/xof1kob.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	