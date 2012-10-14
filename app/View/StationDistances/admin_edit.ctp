<div class="row-fluid">
	<div class="page-header">
		<h1>
			<?php echo h($this->Form->value('FromStation.name')); ?>
			<small>&rarr; <?php echo h($this->Form->value('FromStation.direction')); ?></small>
			-
			<?php echo h($this->Form->value('ToStation.name')); ?>
			<small>&rarr; <?php echo h($this->Form->value('ToStation.direction')); ?></small>
		</h1>
	</div>
</div>

<div class="row-fluid">
	<ul class="nav nav-tabs">
		<li><?php echo $this->Html->link('Detalii', array('controller' => 'station_distances', 'action' => 'view', $this->Form->value('StationDistance.id')), array('escape' => false)); ?></li>
		<li class="active"><?php echo $this->Html->link(__('Editează'), array('controller' => 'station_distances', 'action' => 'edit', $this->Form->value('StationDistance.id')), array('escape' => false)); ?></li>
		<li><?php echo $this->Form->postLink(__('Șterge'), array('controller' => 'station_distances', 'action' => 'delete', $this->Form->value('StationDistance.id')), array('escape' => false), __('Eşti sigur că vrei să ştergi distanta # %s?', $this->Form->value('StationDistance.id'))); ?></li>
	</ul>
</div>

<div class="row-fluid">
	<?php echo $this->Form->create('StationDistance', array('class' => 'form-horizontal')); ?>
		<fieldset>
			<legend>Modifica campurile</legend>
			<?php echo $this->Form->input('minutes', array('div' => 'control-group', 'label' => array('text' => 'Distanta (in minute)', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline')), 'class' => 'input-small')); ?>
			<?php echo $this->Form->input('time', array('type' => 'text', 'div' => 'control-group', 'label' => array('text' => 'Timpul (H:i)', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline')), 'class' => 'input-small')); ?>
			
			<div class="control-group">
				<label class="control-label">Tipul de zi</label>
				<div class="controls">
					<div class="btn-group" data-toggle="buttons-radio" data-hidden="StationDistanceDay">
						<button type="button" class="btn active" value="L"><span class="label label-L">L</span></button>
						<button type="button" class="btn" value="LV"><span class="label label-LV">LV</span></button>
						<button type="button" class="btn" value="S"><span class="label label-S">S</span></button>
						<button type="button" class="btn" value="D"><span class="label label-D">D</span></button>
					</div>
				</div>
			</div>
			<?php echo $this->Form->hidden('day', array('value' => 'L')); ?>
		</fieldset>
		
	<?php echo $this->Form->end(array('label' => __('Salveaza'), 'div' => 'form-actions', 'class' => 'btn btn-primary')); ?>
</div>