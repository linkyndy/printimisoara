<div class="row">
	<div class="span12">
		<h1>Editeaza utilizator</h1>
		<br>
		
		<?php echo $this->Form->create('User', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend>Completeaza campurile</legend>
				<?php
					echo $this->Form->input('id');
					echo $this->Form->input('password', array('value' => '', 'div' => 'control-group', 'label' => array('text' => 'Parola', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
				?>
			</fieldset>
		<?php echo $this->Form->end(array('label' => __('Salveaza'), 'div' => 'form-actions', 'class' => 'btn btn-primary')); ?>
	</div>
</div>