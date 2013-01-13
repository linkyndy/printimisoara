<ul class="nav nav-tabs">
	<li class="dropdown">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#">
			Adaugă
			<b class="caret"></b>
		</a>
		<ul class="dropdown-menu">
			<li><?php echo $this->Html->link('din tabelă', array('controller' => 'times', 'action' => 'add', 'T', 'station_line_batch'), array('escape' => false)); ?></li>
			<li><?php echo $this->Html->link('de la utilizator', array('controller' => 'times', 'action' => 'add', 'U', 'station_line_batch'), array('escape' => false)); ?></li>
			<li><?php echo $this->Html->link('gps', array('controller' => 'times', 'action' => 'add', 'U', 'gps'), array('escape' => false)); ?></li>
		</ul>
	</li>
	<li<?php echo ($active == 'quick') ? ' class="active"' : null; ?>><?php echo $this->Html->link('Afla timp', array('controller' => 'times', 'action' => 'quick'), array('escape' => false)); ?></li>
	<li<?php echo ($active == 'index') ? ' class="active"' : null; ?>><?php echo $this->Html->link('Ultimii timpi', array('controller' => 'times', 'action' => 'index'), array('escape' => false)); ?></li>
	<li<?php echo ($active == 'stations') ? ' class="active"' : null; ?>><?php echo $this->Html->link('Staţii', array('controller' => 'times', 'action' => 'stations'), array('escape' => false)); ?></li>
	<li<?php echo ($active == 'lines') ? ' class="active"' : null; ?>><?php echo $this->Html->link('Linii', array('controller' => 'times', 'action' => 'lines'), array('escape' => false)); ?></li>
	<li<?php echo ($active == 'coverage') ? ' class="active"' : null; ?>><?php echo $this->Html->link('Acoperire', array('controller' => 'times', 'action' => 'coverage'), array('escape' => false)); ?></li>
	<li><?php echo $this->Html->link('Timpi detaliati', array('controller' => 'computed_times', 'action' => 'index'), array('escape' => false)); ?></li>
</ul>