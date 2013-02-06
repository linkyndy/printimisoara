<?php $line_types = array('tv' => 'Tramvai', 'tb' => 'Troleibuz', 'ab' => 'Autobuz', 'am' => 'Autobuz Metropolitan', 'ae' => 'Autobuz Expres'); ?>
<?php $line_importance = array('0' => 'Majora', '1' => 'Normala', '2' => 'Minora', '3' => 'Ocazionala'); ?>
<?php $line_colours = array('green' => 'Green', 'lime' => 'Lime', 'yellow' => 'Yellow', 'orange' => 'Orange', 'pink' => 'Pink', 'red' => 'Red', 'violet' => 'Violet', 'purple' => 'Purple', 'blue' => 'Blue', 'cyan' => 'Cyan'); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1>
			Linia 
			<?php echo $this->Html->line($this->Form->value('Line.name'), $this->Form->value('Line.colour'), $this->Form->value('Line.id')); ?>
		</h1>
	</div>
</div>

<div class="row-fluid">
	<ul class="nav nav-tabs">
		<li><?php echo $this->Html->link(__('Detalii'), array('controller' => 'lines', 'action' => 'view', $this->Form->value('Line.id')), array('escape' => false)); ?></li>
		<li class="active"><?php echo $this->Html->link(__('Editează'), array('controller' => 'lines', 'action' => 'edit', $this->Form->value('Line.id')), array('escape' => false)); ?></li>
		<li><?php echo $this->Form->postLink(__('Șterge'), array('controller' => 'lines', 'action' => 'delete', $this->Form->value('Line.id')), array('escape' => false), __('Eşti sigur că vrei să ştergi linia # %s?', $this->Form->value('Line.id'))); ?></li>
		<li><?php echo $this->Html->link(__('Staţii'), array('controller' => 'station_lines', 'action' => 'view', $this->Form->value('Line.id')), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link(__('Timpi'), array('controller' => 'times', 'action' => 'line', $this->Form->value('Line.id')), array('escape' => false)); ?></li>
		<!--<li><?php echo $this->Html->link(__('Verifica nodurile'), array('action' => 'validate_nodes', $this->Form->value('Line.id')), array('escape' => false)); ?></li>-->
	</ul>
</div>

<div class="row-fluid">
	<?php echo $this->Form->create('Line', array('class' => 'form-horizontal'));?>
		<fieldset>
			<legend>Modifica campurile</legend>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('id_ratt', array('div' => 'control-group', 'label' => array('text' => 'ID Ratt', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
				echo $this->Form->input('name', array('div' => 'control-group', 'label' => array('text' => 'Denumirea liniei', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
				echo $this->Form->input('type', array('options' => $line_types, 'div' => 'control-group', 'label' => array('text' => 'Tipul liniei', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
				echo $this->Form->input('importance', array('options' => $line_importance, 'div' => 'control-group', 'label' => array('text' => 'Importanta liniei', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
				echo $this->Form->input('colour', array('options' => $line_colours, 'div' => 'control-group', 'label' => array('text' => 'Culoarea liniei', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
			?>
		</fieldset>
		<div class="form-actions">
			<?php echo $this->Form->button('Salvează', array('type' => 'submit', 'class' => 'btn btn-primary')); ?>
		</div>
	<?php echo $this->Form->end(); ?>
</div>