<div class="row">
	<div class="span3">
		<div class="well">
			<ul class="nav nav-list">
				<li class="nav-header">Actiuni</li>
				<li><?php echo $this->Html->link(__('<i class="icon-th-list"></i> Detalii despre grupul de statii'), array('action' => 'view', $this->Form->value('StationGroup.id')), array('escape' => false)); ?></li>
				<li><?php echo $this->Html->link(__('<i class="icon-th"></i> Vezi toate grupurile de statii'), array('action' => 'index'), array('escape' => false)); ?></li>
			</ul>
		</div>
	</div>
	<div class="span9">
		<h1>Sugereaza modificari pentru grupul de statii: <?php echo $this->Form->value('StationGroup.name'); ?></h1>
		<br>
		
		<?php echo $this->Form->create('StationGroup', array('class' => 'form-horizontal')); ?>
			<fieldset>
				<legend>Completeaza campurile</legend>
				<?php
					echo $this->Form->input('id');
					echo $this->Form->input('name', array('div' => 'control-group', 'label' => array('text' => 'Denumirea grupului de statii', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
				?>
			</fieldset>
		<?php echo $this->Form->end(array('label' => __('Sugereaza modificarile'), 'div' => 'form-actions', 'class' => 'btn btn-primary')); ?>
	</div>
</div>