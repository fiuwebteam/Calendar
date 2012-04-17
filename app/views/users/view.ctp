<div class="clear"></div>
<div class="grid_2 omega vMarginTop_0">
<?php
$pos = strpos($_SERVER["HTTP_REFERER"], $_SERVER["HTTP_HOST"]);
if($pos !== false) {
	echo "<a class='backBtn button' href='{$_SERVER["HTTP_REFERER"]}'>Back</a>";
}
?>
</div>
<div class="clear"></div><div class="grid_16">
	<h2 class="titles vPaddingTop_1">View User Details</h2>
</div>

<div class="users details view vMarginTop_2  vPaddingBottom_1 grid_16 ">
<h3 class="titles backendTitles vPaddingTop_1 vPaddingBottom_0"><?php  __('User Details');?></h3>
<hr class="dashed"/>
	
	<dl class="grid_6 push_2 alpha"><?php $i = 0; $class = ' class="altrow"';?>
		
		<dt class="grid_2"<?php if ($i % 2 == 0) ?>><?php __('First Name:'); ?></dt>
		<dd class="grid_4 alpha omega clearfix"<?php if ($i++ % 2 == 0)?>>
			<?php echo $user['User']['first_name']; ?>
			&nbsp;
		</dd>
		<div class="clear"></div>
		
		<dt class="grid_2" <?php if ($i % 2 == 0) ?>><?php __('Last Name:'); ?></dt>
		<dd class="grid_4 omega"<?php if ($i++ % 2 == 0)?>>
			<?php echo $user['User']['last_name']; ?>
			&nbsp;
		</dd>
		<div class="clear"></div>
		
		<dt class="grid_2" <?php if ($i % 2 == 0);?>><?php __('Username:'); ?></dt>
		<dd class="grid_4 alpha omega" <?php if ($i++ % 2 == 0)?>>
			<?php echo $user['User']['username']; ?>
			&nbsp;
		</dd>
		<div class="clear"></div>
		<dt class="grid_2" <?php if ($i % 2 == 0)?>><?php __('E-mail:'); ?></dt>
		<dd class="grid_4 alpha omega"<?php if ($i++ % 2 == 0)?>>
			<?php echo $user['User']['email']; ?>
			&nbsp;
		</dd>
		
	
		
	</dl>
		
	<dl class="grid_7 push_2 omega"><?php $i = 0; $class = ' class="altrow"';?>
		<dt class="grid_3"<?php if ($i % 2 == 0)?>><?php __('Receives E-mail:'); ?></dt>
		<dd class="grid_4 alpha omega"<?php if ($i++ % 2 == 0)?>>
			<?php echo ($user['User']['receives_email']) ? "Yes" : "No" ; ?>
			&nbsp;
		</dd>
		
		<dt class="grid_3"<?php if ($i % 2 == 0)?>><?php __('Created:'); ?></dt>
		<dd  class="grid_4 alpha omega"<?php if ($i++ % 2 == 0)?>>
			<?php echo $user['User']['created']; ?>
			&nbsp;
		</dd>
		
		
	</dl>
	
</div>
<div class="clear"></div>
<div class="vMarginTop_2 grid_16">
	<h3 class="titles backendTitles vPaddingTop_1 vPaddingBottom_0"><?php __('User Related Events');?></h3>
	<hr class="dashed"/>
	<?php if (!empty($user['Event'])):?>
	<table class="eventsTable" cellpadding = "0" cellspacing = "0">
	<tr class="tableHeadings">
		<!-- <th><?php __('Id'); ?></th>-->
		<th><?php __('Title'); ?></th>
		<th><?php __('Description'); ?></th>
		 <th><?php __('Location'); ?></th>
		<th><?php __('Contact'); ?></th>
		<!--<th><?php __('Email'); ?></th>-->
		<!--<th><?php __('Phone'); ?></th>-->
		<!--<th><?php __('Url'); ?></th>-->
		<th><?php __('Type'); ?></th>
		<!-- <th><?php __('User Id'); ?></th>-->
		<!-- <th><?php __('Category Id'); ?></th>-->
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['Event'] as $event):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<!-- <td><?php echo $event['id'];?></td>-->
			<td><?php echo $event['title'];?></td>
			
			<td><?php echo $text->truncate($event['description'], 50, array('ending'=>'...', 'exact'=> false)); ?></td>
			
			<td><?php echo $event['location'];?></td>
			<td><?php echo $event['contact'];?></td>
			<!--<td><?php echo $event['email'];?></td>-->
			<!--<td><?php echo $event['phone'];?></td>-->
			<!--<td><?php echo $event['url'];?></td>-->
				<!-- <td><?php echo $event['user_id'];?></td>-->
			<!-- <td><?php echo $event['category_id'];?></td>-->
			
			<td><?php switch ($event['type']) {
				case 1: echo "Normal"; break;
				case 2: echo "Ongoing"; break;
				case 3: echo "Deadline"; break;
			} ?></td>
			
			
			
			<td>
				<?php echo date("M j, Y <b\\r/> g:i a", strtotime($event['created'])) ; ?>
			</td>
			<td>
				<?php echo date("M j, Y <b\\r/> g:i a", strtotime($event['modified'])) ; ?>
			</td>
			
			
			
			<td class="actions">
			  <ul>	
				<li><?php echo $html->link(__('View', true), array('controller' => 'events', 'action' => 'view', $event['id'])); ?></li>
				<li><?php echo $html->link(__('Edit', true), array('controller' => 'events', 'action' => 'edit', $event['id'])); ?></li>
				<li><?php echo $html->link(__('Delete', true), array('controller' => 'events', 'action' => 'delete', $event['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $event['id'])); ?></li>
			</ul>
			</td>
			
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
</div>
<div class="clear"></div>
<div class="grid_16 vMarginTop_2">
	<h3 class="titles backendTitles vPaddingTop_1 vPaddingBottom_0"><?php __('User Related Calendars');?></h3>
	<hr class="dashed"/>
	<?php if (!empty($user['Calendar'])):?>
	<table class="eventsTable" cellpadding = "0" cellspacing = "0">
	<tr class="tableHeadings">
		<th><?php __('Title'); ?></th>
		<th><?php __('Description'); ?></th>
		<th><?php __('Group'); ?></th>
		<th><?php __('Created'); ?></th>
		<th><?php __('Modified'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['Calendar'] as $calendar):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $calendar['title'];?></td>
			<td><?php echo $calendar['description'];?></td>
			<td>
				<?php switch($calendar['CalendarsGroupsUser']['group_id']) {
					case 5:
						echo "Contributor";
						break;
					case 4:
						echo "Author";
						break;
					case 3:
						echo "Editor";
						break;
					case 2:
						echo "Administrator";
						break;						
					case 1:
						echo "SuperAdmin";
						break;
				} ?>
			</td>
			<td><?php echo $calendar['created'];?></td>
			<td><?php echo $calendar['modified'];?></td>
			<td class="actions">
				<ul>	
					<li><?php echo $html->link(__('Edit', true), array('controller' => 'users', 'action' => 'changeTier', "calendar:{$calendar['id']}/user:{$user["User"]["id"]}" )); ?></li>				
				</ul>
			</td>
			
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
</div>
