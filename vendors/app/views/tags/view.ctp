<div class="clear"></div>
<div class="vMarginTop_2 vPaddingTop_1 vPaddingBottom_0">

<h2 class="titles vPaddingTop_1 vPaddingBottom_0">Tag: <?= $this->params["pass"][0] ?></h2>
<hr class="dashed" />

<ul class="generic listTags vPaddingTop_2 vPaddingBottom_1">
<?php foreach($tag[0]["Event"] as $event ) { ?>
<li>
<?php echo $html->link($event["title"], array("controller" =>"events", "action" => "view", $event["id"] ));  ?>
</li>
<?php } ?>
</ul>
</div>