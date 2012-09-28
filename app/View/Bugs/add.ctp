<div class="row">
	<div class="span12">
		<h1>Adauga bug</h1>
		<br>
		
		<?php echo $this->Form->create('Bug', array('class' => 'form-horizontal')); ?>
			<fieldset>
				<legend>Completeaza campurile</legend>
				<?php
					echo $this->Form->input('type', array('div' => 'control-group', 'label' => array('text' => 'Tipul bug-ului', 'class' => 'control-label'), 'options' => array('routes' => 'Rute', 'times' => 'Timpi', 'database' => 'Baza de date', 'location' => 'Locatie', 'app' => 'Aplicatie'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
					echo $this->Form->input('title', array('div' => 'control-group', 'label' => array('text' => 'Titlu', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
					echo $this->Form->input('bug', array('div' => 'control-group', 'label' => array('text' => 'Bug', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
				?>
			</fieldset>
		<?php echo $this->Form->end(array('label' => __('Adauga'), 'div' => 'form-actions', 'class' => 'btn btn-primary')); ?>
	</div>
</div>