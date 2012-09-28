<?php $labels = array('new' => 'label-new', 'pending' => 'label-pending', 'invalid' => 'label-invalid', 'resolved' => 'label-resolved', 'future' => 'label-future'); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1><?php echo $this->Form->value('Bug.title'); ?></h1>
	</div>
</div>

<div class="row-fluid">
	<ul class="nav nav-tabs">
		<li><?php echo $this->Html->link('Detalii', array('controller' => 'bugs', 'action' => 'view', $this->Form->value('Bug.id')), array('escape' => false)); ?></li>
		<li class="active"><?php echo $this->Html->link(__('Editează'), array('controller' => 'bugs', 'action' => 'edit', $this->Form->value('Bug.id')), array('escape' => false)); ?></li>
		<li><?php echo $this->Form->postLink(__('Șterge'), array('controller' => 'bugs', 'action' => 'delete', $this->Form->value('Bug.id')), array('escape' => false), __('Eşti sigur că vrei să ştergi bug-ul # %s?', $this->Form->value('Bug.id'))); ?></li>
		<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					Modifică stare
					<b class="caret"></b>
				</a>
				<ul class="dropdown-menu">
					<?php foreach ($labels as $status => $class): ?>
						<li>
							<?php echo $this->Html->link($status, array('action' => 'update_status', $this->Form->value('Bug.id'), $status), array('escape' => false)); ?>	
						</li>
					<?php endforeach; ?>
				</ul>
			</li>
	</ul>
	
	<?php echo $this->Form->create('Bug', array('class' => 'form-horizontal')); ?>
		<fieldset>
			<legend>Completeaza campurile</legend>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('type', array('div' => 'control-group', 'label' => array('text' => 'Tipul bug-ului', 'class' => 'control-label'), 'options' => array('routes' => 'Rute', 'times' => 'Timpi', 'database' => 'Baza de date', 'location' => 'Locatie', 'app' => 'Aplicatie'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
				echo $this->Form->input('title', array('div' => 'control-group', 'label' => array('text' => 'Titlu', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
				echo $this->Form->input('bug', array('div' => 'control-group', 'label' => array('text' => 'Bug', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
			?>
		</fieldset>
	<?php echo $this->Form->end(array('label' => __('Salveaza'), 'div' => 'form-actions', 'class' => 'btn btn-primary')); ?>
</div>