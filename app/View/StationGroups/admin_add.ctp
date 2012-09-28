<div class="row-fluid">
	<div class="page-header">
		<h1>Adaugă grup de staţii</h1>
	</div>
</div>

<div class="row-fluid">
	<?php echo $this->Form->create('StationGroup', array('class' => 'form-horizontal')); ?>
		<fieldset>
			<legend>Completeaza campurile</legend>
			<?php
				echo $this->Form->input('name', array('div' => 'control-group', 'label' => array('text' => 'Denumirea grupului de statii', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
			?>
		</fieldset>
	<?php echo $this->Form->end(array('label' => __('Adauga'), 'div' => 'form-actions', 'class' => 'btn btn-primary')); ?>
</div>
