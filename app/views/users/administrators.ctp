<ul>
	<?php foreach($users as $value) {
		echo "<li>";
		echo $html->link($value["User"]["username"], "mailto:{$value["User"]["email"]}");
		echo "</li>";
	}?>
</ul>