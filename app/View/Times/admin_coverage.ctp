<?php $marks = array('poor' => 'progress-danger', 'average' => 'progress-warning', 'good' => 'progress-info', 'high' => 'progress-success'); ?>
<?php $days = array('L', 'LV', 'S', 'D'); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1>Timpi</h1>
	</div>
</div>

<div class="row-fluid">
	<?php echo $this->element('admin_time_submenu', array('active' => 'coverage')); ?>
</div>

<div class="row-fluid">
	<h3>Acoperire generala: <strong><?php echo $global_coverage_percent; ?>%</strong></h3>
	
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>Linie</th>
				<th><abbr title="Acoperire generala">A.G.</abbr></th>
				<?php foreach ($days as $day): ?>
					<th colspan="2">Acoperire <span class="label label-<?php echo $day; ?>"><?php echo $day; ?></span></th>
				<?php endforeach; ?>
				<th></th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach ($lines as $line): ?>
				<tr>
					<td><?php echo $this->Html->line(h($line['Line']['name']), h($line['Line']['colour']), h($line['Line']['id'])); ?></td>
					<?php if (!is_null($line['Coverage']['coverage'])): ?>
						<td><strong><?php echo $line['Coverage']['coverage']; ?>%</strong></td>
						<?php foreach ($days as $day): ?>
							<td class="span3">
								<div class="progress <?php 
									if ($line['Coverage']['coverage_' . $day] > 95) {
										echo 'progress-success';
									} elseif ($line['Coverage']['coverage_' . $day] > 80) {
										echo 'progress-info';
									} elseif ($line['Coverage']['coverage_' . $day] > 50) {
										echo 'progress-warning';
									} else {
										echo 'progress-danger';
									}
								?>">
									<div class="bar" style="width: <?php echo $line['Coverage']['coverage_' . $day]; ?>%;"></div>
								</div>
							</td>
							<td><?php echo $line['Coverage']['coverage_' . $day]; ?><small class="muted">%</small></td>
						<?php endforeach; ?>
					<?php else: ?>
						<td colspan="9">Linia nu are stații definite</td>
					<?php endif; ?>
					
					<?php
						if ($this->Time->wasWithinLast('1 day', $line['Coverage']['modified'])) {
							$buttonClass = 'btn-success';
						} elseif ($this->Time->wasWithinLast('1 week', $line['Coverage']['modified'])) {
							$buttonClass = 'btn-warning';
						} else {
							$buttonClass = 'btn-danger';
						}
					?>
					
					<td><?php echo $this->Html->link('<i class="icon-refresh"></i>', array('controller' => 'lines', 'action' => 'compute_coverage', $line['Line']['id']), array('class' => 'btn ' . $buttonClass, 'escape' => false, 'rel' => 'tooltip', 'title' => 'Recalculat ultima data: ' . $this->Time->timeAgoInWords($line['Coverage']['modified']), 'data-placement' => 'left')); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>