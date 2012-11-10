<div class="times form">
<?php echo $this->Form->create('Time');?>
	<fieldset>
		<legend><?php echo __('Admin Edit Time'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('station_id');
		echo $this->Form->input('line_id');
		echo $this->Form->input('time');
		echo $this->Form->input('day');
		echo $this->Form->input('type');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Time.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Time.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Times'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Stations'), array('controller' => 'stations', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Station'), array('controller' => 'stations', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Lines'), array('controller' => 'lines', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Line'), array('controller' => 'lines', 'action' => 'add')); ?> </li>
	</ul>
</div>
