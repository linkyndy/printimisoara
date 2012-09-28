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
		<li class="active"><?php echo $this->Html->link('Afla timp', array('controller' => 'times', 'action' => 'quick'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('Ultimii timpi', array('controller' => 'times', 'action' => 'index'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('Staţii', array('controller' => 'times', 'action' => 'stations'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('Linii', array('controller' => 'times', 'action' => 'lines'), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('Acoperire', array('controller' => 'times', 'action' => 'coverage'), array('escape' => false)); ?></li>
	</ul>
</div>
	
<div class="row-fluid">
	<div class="span6">
		<?php echo $this->Form->create('Time', array('url' => array('action' => 'quick'), 'class' => 'form-search well')); ?>
			<div class="input-append">
				<?php echo $this->Form->input('station_line', array('data-provide' => 'typeahead', 'data-source' => json_encode(array_values($station_line_list)), 'data-items' => 10, 'autocomplete' => 'off', 'placeholder' => 'Linia | Staţia', 'class' => 'search-query span12', 'label' => false, 'div' => false)); ?>
				<?php echo $this->Form->button('Caută timp', array('type' => 'submit', 'class' => 'btn btn-info', 'div' => false)); ?>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
	
	<div class="span6">
		<?php if (isset($times) || isset($optimizedTime)): ?>
			<table class="table table-striped table-bordered">
				<tr>
					<td class="span3">Timpii de la RATT</td>
					<td>
						<?php if (!empty($times)): ?>
							<?php foreach ($times as $time): ?>
								<span class="label label-<?php echo h($time['type']); ?>"><?php echo $this->Time->format('H:i', strtotime(h($time['time']))); ?></span>
							<?php endforeach; ?>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td>Timpul optimizat</td>
					<td>
						<?php if (!empty($optimizedTime)): ?>
							<span class="label label-<?php echo h($optimizedTime['type']); ?>"><?php echo $this->Time->format('H:i', strtotime(h($optimizedTime['time']))); ?></span>
						<?php endif; ?>
					</td>
				</tr>
			</table>
			
			<p>
				<small class="muted">
					<span>Legenda: </span>
					<span class="label label-M">in minute</span> &bull;
					<span class="label label-G">din grafic</span> &bull;
					<span class="label label-T">din tabelă</span> &bull;
					<span class="label label-U">de la utilizator</span>
				</small>
			</p>
		<?php endif;?>
	</div>
</div>