<div class="row-fluid">
	<div class="page-header">
		<h1>Timpi</h1>
	</div>
</div>

<div class="row-fluid">
	<ul class="nav nav-tabs">
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				Adaugă
				<b class="caret"></b>
			</a>
			<ul class="dropdown-menu">
				<li><?php echo $this->Html->link('din tabelă', array('controller' => 'times', 'action' => 'add', 'T'), array('escape' => false)); ?></li>
				<li><?php echo $this->Html->link('de la utilizator', array('controller' => 'times', 'action' => 'add', 'U'), array('escape' => false)); ?></li>
			</ul>
		</li>
		<li><?php echo $this->Html->link('Afla timp', array('controller' => 'times', 'action' => 'quick'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('Ultimii timpi', array('controller' => 'times', 'action' => 'index'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('Staţii', array('controller' => 'times', 'action' => 'stations'), array('escape' => false)); ?></li>
		<li class="active"><?php echo $this->Html->link('Linii', array('controller' => 'times', 'action' => 'lines'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('Acoperire', array('controller' => 'times', 'action' => 'coverage'), array('escape' => false)); ?></li>
	</ul>
</div>
	
<div class="row-fluid">
	<?php echo $this->Form->create('Time', array('url' => array('action' => 'line'), 'class' => 'form-search well')); ?>
		<div class="input-append">
			<?php echo $this->Form->input('search', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($line_list)), 'data-items' => 10, 'autocomplete' => 'off', 'placeholder' => 'Caută o linie...', 'class' => 'search-query input-xlarge', 'label' => false, 'div' => false)); ?>
			<?php echo $this->Form->button('Caută timp pt. linie', array('type' => 'submit', 'class' => 'btn btn-info', 'div' => false)); ?>
		</div>
	<?php echo $this->Form->end(); ?>
</div>