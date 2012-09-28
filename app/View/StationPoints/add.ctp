<div class="stations form">
<?php echo $this->Form->create('Station');?>
	<fieldset>
		<legend><?php echo __('Add Station'); ?></legend>
	<?php
		echo $this->Form->input('id_ratt');
		echo $this->Form->input('station_group_id');
		echo $this->Form->input('name');
		echo $this->Form->input('lat');
		echo $this->Form->input('lng');
		echo $this->Form->input('region');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Stations'), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Station Groups'), array('controller' => 'station_groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Station Group'), array('controller' => 'station_groups', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Station Lines'), array('controller' => 'station_lines', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Station Line'), array('controller' => 'station_lines', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Times'), array('controller' => 'times', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Time'), array('controller' => 'times', 'action' => 'add')); ?> </li>
	</ul>
</div>
