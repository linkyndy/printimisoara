<div class="row-fluid">
	<div class="page-header">
		<h1>
			Linia 
			<?php echo $this->Html->line($line['Line']['name'], $line['Line']['colour'], $line['Line']['id']); ?>
		</h1>
	</div>
</div>

<div class="row-fluid">
	<ul class="nav nav-tabs">
		<li><?php echo $this->Html->link(__('Detalii'), array('controller' => 'lines', 'action' => 'view', $line['Line']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link(__('Editează'), array('controller' => 'lines', 'action' => 'edit', $line['Line']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Form->postLink(__('Șterge'), array('controller' => 'lines', 'action' => 'delete', $line['Line']['id']), array('escape' => false), __('Eşti sigur că vrei să ştergi linia # %s?', $line['Line']['id'])); ?></li>
		<li><?php echo $this->Html->link(__('Staţii'), array('controller' => 'station_lines', 'action' => 'view', $line['Line']['id']), array('escape' => false)); ?></li>
		<li class="active"><?php echo $this->Html->link(__('Timpi'), array('controller' => 'times', 'action' => 'line', $line['Line']['id']), array('escape' => false)); ?></li>
		<!--<li><?php echo $this->Html->link(__('Verifica nodurile'), array('action' => 'validate_nodes', $line['Line']['id']), array('escape' => false)); ?></li>-->
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
			<?php foreach($directions as $i => $direction): ?>
				<li class="disabled">
					<a href="#">
						Directia
						<?php echo $direction[0]['Station']['name']; ?>
						&rarr;
						<?php echo $direction[count($direction) - 1]['Station']['name']; ?>
					</a>
				</li>
				<?php foreach($direction as $j => $station): ?>
					<li<?php echo ($i == 0 && $j == 0) ? ' class="active"' : null; ?>>
						<a href="#station-<?php echo $station['Station']['id']; ?>" data-toggle="tab">
							<?php echo $station['Station']['name']; ?>
						</a>
					</li>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</ul>
		<div class="tab-content">
			<?php foreach($directions as $i => $direction): ?>
				<?php foreach($direction as $j => $station): ?>
					<div class="tab-pane<?php echo ($i == 0 && $j == 0) ? ' active' : null; ?>" id="station-<?php echo $station['Station']['id']; ?>">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th rowspan="2">Ora</th>
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
								<?php foreach($station['Time'] as $hour => $days): ?>
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
			<?php endforeach; ?>
		</div>
	</div>
</div>