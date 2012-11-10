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
	<p>
		<span class="label label-info">Nu uita!</span>
		<small class="muted">
			doar liniile care au definite stații sunt luate în calcul  &bull;
			acoperirea generala <strong>pe linii</strong> tine cont de tipul zilei (L/LV)
		</small>
	</p>
	
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>Linie</th>
				<th><abbr title="Acoperire generala">A.G.</abbr></th>
				<?php foreach ($days as $day): ?>
					<th colspan="2">Acoperire <span class="label label-<?php echo $day; ?>"><?php echo $day; ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach ($lines as $line): ?>
				<tr>
					<td><?php echo $this->Html->line(h($line['Line']['name']), h($line['Line']['colour']), h($line['Line']['id'])); ?></td>
					<?php if (isset($line['Line']['coverage_percent'])): ?>
						<td><?php echo $line['Line']['coverage_percent']['general']; ?>%</td>
						<?php foreach ($days as $day): ?>
							<td class="span3">
								<div class="progress <?php echo $marks[$line['Line']['coverage_mark'][$day]]; ?>">
									<div class="bar" style="width: <?php echo $line['Line']['coverage_percent'][$day]; ?>%;"></div>
								</div>
							</td>
							<td><small><?php echo $line['Line']['coverage_percent'][$day]; ?>%</small></td>
						<?php endforeach; ?>
					<?php else: ?>
						<td colspan="10">Linia nu are stații definite</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>