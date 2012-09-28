<div class="stations index">
	<h2><?php echo __('Stations');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('id_ratt');?></th>
			<th><?php echo $this->Paginator->sort('station_group_id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('lat');?></th>
			<th><?php echo $this->Paginator->sort('lng');?></th>
			<th><?php echo $this->Paginator->sort('region');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($stations as $station): ?>
	<tr>
		<td><?php echo h($station['Station']['id']); ?>&nbsp;</td>
		<td><?php echo h($station['Station']['id_ratt']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($station['StationGroup']['name'], array('controller' => 'station_groups', 'action' => 'view', $station['StationGroup']['id'])); ?>
		</td>
		<td><?php echo h($station['Station']['name']); ?>&nbsp;</td>
		<td><?php echo h($station['Station']['lat']); ?>&nbsp;</td>
		<td><?php echo h($station['Station']['lng']); ?>&nbsp;</td>
		<td><?php echo h($station['Station']['region']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $station['Station']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $station['Station']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $station['Station']['id']), null, __('Are you sure you want to delete # %s?', $station['Station']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Station'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Station Groups'), array('controller' => 'station_groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Station Group'), array('controller' => 'station_groups', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Station Lines'), array('controller' => 'station_lines', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Station Line'), array('controller' => 'station_lines', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Times'), array('controller' => 'times', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Time'), array('controller' => 'times', 'action' => 'add')); ?> </li>
	</ul>
</div>
