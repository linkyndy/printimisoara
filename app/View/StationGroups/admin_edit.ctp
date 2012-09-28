<div class="row-fluid">
	<div class="page-header">
		<h1><?php echo $this->Form->value('StationGroup.name'); ?></h1>
	</div>
</div>

<div class="row-fluid">
	<ul class="nav nav-tabs">
		<li><?php echo $this->Html->link('Detalii', array('controller' => 'station_groups', 'action' => 'view', $this->Form->value('StationGroup.id')), array('escape' => false)); ?></li>
		<li class="active"><?php echo $this->Html->link(__('Editează'), array('controller' => 'station_groups', 'action' => 'edit', $this->Form->value('StationGroup.id')), array('escape' => false)); ?></li>
		<li><?php echo $this->Form->postLink(__('Șterge'), array('controller' => 'station_groups', 'action' => 'delete', $this->Form->value('StationGroup.id')), array('escape' => false), __('Eşti sigur că vrei să ştergi grupul de staţii # %s?', $this->Form->value('StationGroup.id'))); ?></li>
	</ul>
</div>

<div class="row-fluid">
	<?php echo $this->Form->create('StationGroup', array('class' => 'form-horizontal')); ?>
		<fieldset>
			<legend>Completeaza campurile</legend>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('name', array('div' => 'control-group', 'label' => array('text' => 'Denumirea grupului de statii', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
			?>
		</fieldset>
	<?php echo $this->Form->end(array('label' => __('Salveaza'), 'div' => 'form-actions', 'class' => 'btn btn-primary')); ?>
</div>