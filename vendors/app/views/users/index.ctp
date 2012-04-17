<?php 
$paginator->options(array('url' => $this->passedArgs));
$noLimit = "";
foreach($this->passedArgs as $key => $value) {
	if ($key != "limit") {
		$noLimit .= "/$key:$value"; 
	}
}
?>







<div class="omega alpha users index">
<div class="clear"></div>
<div class="grid_8">
	<h1 class="titles vPaddingTop_1 vPaddingBottom_0">My Users</h1>
</div>	
		<div class="clear"></div>


<h5 class="titles grid_5"><?php __('These are your users');?></h5>
<hr class="dashed"/>
<div class="vPaddingTop_1 vPaddingBottom_1 grid_16">
<?php
$noFilterUrl = "";
foreach($this->params["named"] as $key => $value ) {
	if ($key != "filter") {	$noFilterUrl .= "$key:$value/";	}	
}
echo 'Filter by last name';
echo '<ul class="hActionsList usersList vMarginTop_0">';
for ($x = 65; $x <= 90; $x++) {
	
	echo '<li>' . $html->link(chr($x), array("controller" => "users", "action" => "index", $noFilterUrl . "/filter:" . chr($x)  )) . '</li>';
	/* if ($x != 90) { echo " | "; }*/
}
echo '</ul>'
?>
</div>


<hr class="dashed"/>

<div class="paging grid_7 push_8 vMarginTop_1 omega vMarginBottom_1">
	<?php echo $paginator->prev(__('Previous', true), array('class'=>'boxes prev'));?>  
	  <span class='numbers'><?php echo $paginator->numbers(array('modulus' => 5));?></span> 
	  <?php if ($paginator->numbers(array('modulus' => 5)) != "") { ?>
	  <span>|</span>
	  <?php } ?>
	<?php echo $paginator->next(__('Next', true), array('class' => 'boxes next'));?>
</div>


<table class="eventsTable" cellpadding="0" cellspacing="0">
<tr class="tableHeadings">
	<th>
		<?php echo $paginator->sort('username');?>
		<?php
			if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "username") {
				if ($this->params["named"]["direction"] == "asc" ) {
					echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				} else {
					echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				}
			}
		?>
	</th>
	<th>
		<?php echo $paginator->sort('email');?>
		<?php
			if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "email") {
				if ($this->params["named"]["direction"] == "asc" ) {
					echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				} else {
					echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				}
			}
		?>
	</th>
	<th>
		<?php echo $paginator->sort('first_name');?>
		<?php
			if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "first_name") {
				if ($this->params["named"]["direction"] == "asc" ) {
					echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				} else {
					echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				}
			}
		?>
	</th>
	<th>
		<?php echo $paginator->sort('last_name');?>
		<?php
			if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "last_name") {
				if ($this->params["named"]["direction"] == "asc" ) {
					echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				} else {
					echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				}
			}
		?>
	</th>
	<th>
		<?php echo $paginator->sort('receives_email');?>
		<?php
			if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "received_email") {
				if ($this->params["named"]["direction"] == "asc" ) {
					echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				} else {
					echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				}
			}
		?>
	</th>
	<th>
		<?php echo $paginator->sort('active');?>
		<?php
			if (isset($this->params["named"]["sort"]) && $this->params["named"]["sort"] == "active") {
				if ($this->params["named"]["direction"] == "asc" ) {
					echo $html->image('vertical-arrow-down.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				} else {
					echo $html->image('vertical-arrow.gif',array("class" =>"vArrow", "alt" => "Sort Column"));
				}
			}
		?>
	</th>
	
	
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($users as $user):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $user['User']['username']; ?>
		</td>
		<td>
			<?php echo $user['User']['email']; ?>
		</td>
		<td>
			<?php echo $user['User']['first_name']; ?>
		</td>
		<td>
			<?php echo $user['User']['last_name']; ?>
		</td>
		<td>
			<?php echo ($user['User']['receives_email']) ? "Yes" : "No"; ?>
		</td>
		<td>
			<?php echo ($user['User']['active']) ? "Yes" : "No"; ?>
		</td>
				<td class="actions">
		<ul>
			<li><?php echo $html->link(__('View', true), array('action' => 'view', $user['User']['id'])); ?></li>
			<?php if ($user['User']['id'] != 1) { ?>
			<li><?php echo $html->link(__('Edit', true), array('controller' => 'users', 'action' => 'changeTier', "user:{$user["User"]["id"]}" )); ?></li>
			<li><?php echo $html->link(__('Delete', true), array('action' => 'delete', $user['User']['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $user['User']['username'])); ?></li>
			<?php } ?>
		</ul>
		</td>
		
	</tr>
<?php endforeach; ?>
</table>
	<div class="grid_7 vMarginTop_1">
				
				<?php
				  
					if ($paginator->counter(array('format' => __('%page%',true))) >= 1) {
						echo  '<p class="pageInfo ">';
						echo $paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total', true)));
						echo '</p>';
					}
					else{
						echo '<p class="pageInfo"> There are no entries. </p>';
					}
				
				?>

</div>
			<div class="paging grid_7 push_1 vMarginTop_1 omega vMarginBottom_1">
				<?php echo $paginator->prev(__('Previous', true), array('class'=>'boxes prev'));?>  
				  <span class='numbers'><?php echo $paginator->numbers(array('modulus' => 5));?></span> 
				  <?php if ($paginator->numbers(array('modulus' => 5)) != "") { ?>
				  <span>|</span>
				  <?php } ?>
		
		
				<?php echo $paginator->next(__('Next', true), array('class' => 'boxes next'));?>
			</div>
						<div class="clear"></div>
			<div class="paging grid_7 push_8 vMarginTop_1 omega vMarginBottom_1">
			<?php
			echo $html->link("20", array($noLimit."/limit:20"));
			echo " | ";
			echo $html->link("50", array($noLimit."/limit:50"));
			echo " | ";
			echo $html->link("100", array($noLimit."/limit:100"));
			echo " | ";
			echo $html->link("500", array($noLimit."/limit:500"));	
			?> per page
			</div>
			<div class="clear"></div>

</div>

