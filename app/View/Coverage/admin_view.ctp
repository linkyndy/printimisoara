<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_line', array('inline' => false)); ?>
<?php echo $this->Form->hidden('Line.id', array('value' => $line['Line']['id'])); ?>

<?php $line_types = array('tv' => 'Tramvai', 'tb' => 'Troleibuz', 'ab' => 'Autobuz', 'am' => 'Autobuz Metropolitan', 'ae' => 'Autobuz Expres'); ?>
<?php $line_importance = array('0' => 'Majora', '1' => 'Normala', '2' => 'Minora', '3' => 'Ocazionala'); ?>

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
		<li class="active"><?php echo $this->Html->link(__('Detalii'), array('controller' => 'lines', 'action' => 'view', $line['Line']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link(__('Editează'), array('controller' => 'lines', 'action' => 'edit', $line['Line']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Form->postLink(__('Șterge'), array('controller' => 'lines', 'action' => 'delete', $line['Line']['id']), array('escape' => false), __('Eşti sigur că vrei să ştergi linia # %s?', $line['Line']['id'])); ?></li>
		<li><?php echo $this->Html->link(__('Staţii'), array('controller' => 'station_lines', 'action' => 'view', $line['Line']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link(__('Timpi'), array('controller' => 'times', 'action' => 'line', $line['Line']['id']), array('escape' => false)); ?></li>
		<!--<li><?php echo $this->Html->link(__('Verifica nodurile'), array('action' => 'validate_nodes', $line['Line']['id']), array('escape' => false)); ?></li>-->
	</ul>
</div>

<div class="row-fluid">
	<div class="span4">
		<table class="table table-striped table-bordered">
			<tbody>
				<tr>
					<td>ID</td>
					<td><?php echo $this->Html->link('#' . $line['Line']['id'], array('action' => 'view', $line['Line']['id'])); ?></td>
				</tr>
				<tr>
					<td>ID RATT</td>
					<td>#<?php echo h($line['Line']['id_ratt']); ?></td>
				</tr>
				<tr>
					<td>Nume</td>
					<td>
						<?php echo $this->Html->line($line['Line']['name'], $line['Line']['colour'], $line['Line']['id']); ?>
					</td>
				</tr>
				<tr>
					<td>De</td>
					<td><?php echo $line_types[h($line['Line']['type'])]; ?></td>
				</tr>
				<tr>
					<td>Importanta</td>
					<td><?php echo $line_importance[h($line['Line']['importance'])]; ?></td>
				</tr>
			</tbody>
		</table>
		
		<div class="progress <?php //echo $marks[$line['Line']['coverage_mark'][$day]]; ?>">
			<div class="bar" style="width: <?php //echo $line['Line']['coverage_percent'][$day]; ?>%;"></div>
		</div>
		<?php echo $this->Html->link('compute', array('action' => 'compute_coverage', $line['Line']['id'])); ?>
	</div>
	
	<div class="span8">
		<div class="well well-small">
			<figure id="map" style="height:300px;"></figure>
		</div>
	</div>
</div>