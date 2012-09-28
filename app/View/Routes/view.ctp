<div class="stations view">
<h2><?php  echo __('Station');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($station['Station']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Id Ratt'); ?></dt>
		<dd>
			<?php echo h($station['Station']['id_ratt']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Station Group'); ?></dt>
		<dd>
			<?php echo $this->Html->link($station['StationGroup']['name'], array('controller' => 'station_groups', 'action' => 'view', $station['StationGroup']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($station['Station']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Lat'); ?></dt>
		<dd>
			<?php echo h($station['Station']['lat']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Lng'); ?></dt>
		<dd>
			<?php echo h($station['Station']['lng']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Region'); ?></dt>
		<dd>
			<?php echo h($station['Station']['region']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Station'), array('action' => 'edit', $station['Station']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Station'), array('action' => 'delete', $station['Station']['id']), null, __('Are you sure you want to delete # %s?', $station['Station']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Stations'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Station'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Station Groups'), array('controller' => 'station_groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Station Group'), array('controller' => 'station_groups', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Station Lines'), array('controller' => 'station_lines', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Station Line'), array('controller' => 'station_lines', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Times'), array('controller' => 'times', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Time'), array('controller' => 'times', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Station Lines');?></h3>
	<?php if (!empty($station['StationLine'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Station Id'); ?></th>
		<th><?php echo __('Line Id'); ?></th>
		<th><?php echo __('Order'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($station['StationLine'] as $stationLine): ?>
		<tr>
			<td><?php echo $stationLine['id'];?></td>
			<td><?php echo $stationLine['station_id'];?></td>
			<td><?php echo $stationLine['line_id'];?></td>
			<td><?php echo $stationLine['order'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'station_lines', 'action' => 'view', $stationLine['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'station_lines', 'action' => 'edit', $stationLine['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'station_lines', 'action' => 'delete', $stationLine['id']), null, __('Are you sure you want to delete # %s?', $stationLine['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Station Line'), array('controller' => 'station_lines', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Times');?></h3>
	<?php if (!empty($station['Time'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Station Id'); ?></th>
		<th><?php echo __('Line Id'); ?></th>
		<th><?php echo __('Time'); ?></th>
		<th><?php echo __('Day'); ?></th>
		<th><?php echo __('Type'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($station['Time'] as $time): ?>
		<tr>
			<td><?php echo $time['id'];?></td>
			<td><?php echo $time['station_id'];?></td>
			<td><?php echo $time['line_id'];?></td>
			<td><?php echo $time['time'];?></td>
			<td><?php echo $time['day'];?></td>
			<td><?php echo $time['type'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'times', 'action' => 'view', $time['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'times', 'action' => 'edit', $time['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'times', 'action' => 'delete', $time['id']), null, __('Are you sure you want to delete # %s?', $time['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Time'), array('controller' => 'times', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
