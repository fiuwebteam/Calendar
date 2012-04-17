<div class="clear"></div>

	<div class="grid_8"> 
	<h1 class="titles vPaddingTop_1 vPaddingBottom_0"><?php __('Tags');?></h1>
</div>
<div class="clear"></div>


<div class="users index">
 <div class="grid_8"><h5 class="titles">All tags found on the FIU University Calendar</h5></div>
<hr class="dashed" />

<ul class="listTags tags generic vPaddingTop_2 vPaddingBottom_1">
<?php foreach($tags as $tag) {?>
<li class="vMarginTop_0 vMarginBottom_0">
<?php echo $html->link($tag["Tag"]["title"], array("action" => "view", $tag["Tag"]["title"])); ?>
</li>
<?php } ?>
</ul>
</div>