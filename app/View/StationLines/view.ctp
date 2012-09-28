<div class="stationLines view">
<h2><?php  echo __('Station Line');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($stationLine['StationLine']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Station'); ?></dt>
		<dd>
			<?php echo $this->Html->link($stationLine['Station']['name'], array('controller' => 'stations', 'action' => 'view', $stationLine['Station']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Line'); ?></dt>
		<dd>
			<?php echo $this->Html->link($stationLine['Line']['name'], array('controller' => 'lines', 'action' => 'view', $stationLine['Line']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Order'); ?></dt>
		<dd>
			<?php echo h($stationLine['StationLine']['order']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Station Line'), array('action' => 'edit', $stationLine['StationLine']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Station Line'), array('action' => 'delete', $stationLine['StationLine']['id']), null, __('Are you sure you want to delete # %s?', $stationLine['StationLine']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Station Lines'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Station Line'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Stations'), array('controller' => 'stations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Station'), array('controller' => 'stations', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Lines'), array('controller' => 'lines', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Line'), array('controller' => 'lines', 'action' => 'add')); ?> </li>
	</ul>
</div>
