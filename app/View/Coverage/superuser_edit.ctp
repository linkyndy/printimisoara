<?php $line_types = array('tv' => 'Tramvai', 'tb' => 'Troleibuz', 'ab' => 'Autobuz', 'am' => 'Autobuz Metropolitan', 'ae' => 'Autobuz Expres'); ?>

<div class="row">
	<div class="span3">
		<div class="well">
			<ul class="nav nav-list">
				<li class="nav-header">Actiuni</li>
				<li><?php echo $this->Html->link(__('<i class="icon-th-list"></i> Detalii despre linie'), array('action' => 'view', $this->Form->value('Line.id')), array('escape' => false)); ?></li>
				<li><?php echo $this->Html->link(__('<i class="icon-th"></i> Vezi toate liniile'), array('action' => 'index'), array('escape' => false)); ?></li>
			</ul>
		</div>
	</div>
	<div class="span9">
		<h1>Sugereaza modificari pentru linia <?php echo $this->Form->value('Line.name'); ?></h1>
		<br>
		
		<?php echo $this->Form->create('Line', array('class' => 'form-horizontal'));?>
			<fieldset>
				<legend>Modifica campurile</legend>
				<?php
					echo $this->Form->input('id');
					echo $this->Form->input('id_ratt', array('div' => 'control-group', 'label' => array('text' => 'ID Ratt', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
					echo $this->Form->input('name', array('div' => 'control-group', 'label' => array('text' => 'Denumirea liniei', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
					echo $this->Form->input('type', array('options' => $line_types, 'div' => 'control-group', 'label' => array('text' => 'Tipul liniei', 'class' => 'control-label'), 'between' => '<div class="controls">', 'after' => '</div>', 'format' => array('before', 'label', 'between', 'input', 'error', 'after'), 'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline'))));
				?>
			</fieldset>
		<?php echo $this->Form->end(array('label' => __('Sugereaza modificarile'), 'div' => 'form-actions', 'class' => 'btn btn-primary')); ?>
	</div>
</div>