<div class="row-fluid">
	<div class="page-header">
		<h1>
			<?php echo h($station['Station']['name']); ?>
			<small>&rarr; <?php echo h($station['Station']['direction']); ?></small>
		</h1>
	</div>
</div>

<div class="row-fluid">
	<ul class="nav nav-tabs">
		<li><?php echo $this->Html->link('Detalii', array('controller' => 'stations', 'action' => 'view', $station['Station']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link(__('Editează'), array('controller' => 'stations', 'action' => 'edit', $station['Station']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Form->postLink(__('Șterge'), array('controller' => 'stations', 'action' => 'delete', $station['Station']['id']), array('escape' => false), __('Eşti sigur că vrei să ştergi staţia # %s?', $station['Station']['id'])); ?></li>
		<li class="active"><?php echo $this->Html->link(__('Timpi'), array('controller' => 'times', 'action' => 'station', $station['Station']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('Bug', array('controller' => 'bugs', 'action' => 'add'), array('data-toggle' => 'modal', 'data-target' => '#modal-'.$station['Station']['id'], 'escape' => false)); ?></li>
	</ul>
</div>

<div class="row-fluid">	
	<p>
		<small class="muted">
			<span>Legenda: </span>
			<span class="label label-M">in minute</span> &bull;
			<span class="label label-G">din grafic</span> &bull;
			<span class="label label-T">din tabelă</span> &bull;
			<span class="label label-U">de la utilizator</span>
		</small>
	</p>
		
	<br>
		
	<div class="tabbable tabs-left">
		<ul class="nav nav-tabs">
			<?php foreach ($lines as $i => $line): ?>
				<li<?php echo ($i == 0) ? ' class="active"' : null; ?>>
					<a href="#line-<?php echo $line['Line']['id']; ?>" data-toggle="tab">
						<?php echo $this->Html->line($line['Line']['name'], $line['Line']['colour']); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="tab-content">
			<?php foreach($lines as $i => $line): ?>
				<div class="tab-pane<?php echo ($i == 0) ? ' active' : null; ?>" id="line-<?php echo $line['Line']['id']; ?>">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th rowspan="2" class="span1">Ora</th>
								<th colspan="4">Minutul</th>
							</tr>
							<tr>
								<th class="span3"><span class="label label-L">L</span></th>
								<th class="span3"><span class="label label-LV">LV</span></th>
								<th class="span3"><span class="label label-S">S</span></th>
								<th class="span3"><span class="label label-D">D</span></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($line['Time'] as $hour => $days): ?>
								<tr>
									<td><?php echo $hour; ?></td>
									<?php $currentDay = null; ?>
									<?php foreach($days as $day => $times): ?>
										<?php if($day != $currentDay): ?>
											<td>
										<?php endif; ?>
										
										<?php foreach($times as $time): ?>
											<span class="label label-<?php echo h($time['type']); ?>"><?php echo $this->Time->format('i', strtotime(h($time['time']))); ?></span>
										<?php endforeach; ?>
											
										<?php if($day != $currentDay): ?>
											</td>
										<?php $day = $currentDay; endif; ?>
									<?php endforeach; ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>