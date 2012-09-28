<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_station', array('inline' => false)); ?>

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
		<li class="active"><?php echo $this->Html->link('Detalii', array('controller' => 'stations', 'action' => 'view', $station['Station']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link(__('Editează'), array('controller' => 'stations', 'action' => 'edit', $station['Station']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Form->postLink(__('Șterge'), array('controller' => 'stations', 'action' => 'delete', $station['Station']['id']), array('escape' => false), __('Eşti sigur că vrei să ştergi staţia # %s?', $station['Station']['id'])); ?></li>
		<li><?php echo $this->Html->link(__('Timpi'), array('controller' => 'times', 'action' => 'station', $station['Station']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link('Bug', array('controller' => 'bugs', 'action' => 'add'), array('data-toggle' => 'modal', 'data-target' => '#modal-'.$station['Station']['id'], 'escape' => false)); ?></li>
	</ul>
</div>

<div class="row-fluid">
	<div class="span4">		
		<table class="table table-striped table-bordered">
			<tbody>
				<tr>
					<td>ID</td>
					<td><?php echo $this->Html->link('#' . h($station['Station']['id']), array('action' => 'view', $station['Station']['id'])); ?></td>
				</tr>
				<tr>
					<td>ID RATT</td>
					<td>#<?php echo h($station['Station']['id_ratt']); ?></td>
				</tr>
				<tr>
					<td>Nume</td>
					<td><?php echo h($station['Station']['name']); ?></td>
				</tr>
				<tr>
					<td>Direcţie</td>
					<td><?php echo h($station['Station']['direction']); ?></td>
				</tr>
				<tr>
					<td>Grupul de staţii</td>
					<td><?php echo $this->Html->link($station['StationGroup']['name'], array('controller' => 'station_groups', 'action' => 'view', $station['StationGroup']['id'])); ?></td>
				</tr>
				<tr>
					<td>Nod?</td>
					<td>
						<?php if ($station['Station']['node'] == 1): ?>
							<span class="label label-info">&#10004;</span>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td>Coordonate</td>
					<td>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Latitudine</th>
									<th>Longitudine</th>
									<th>Regiune</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td id="lat"><?php echo h($station['Station']['lat']); ?></td>
									<td id="lng"><?php echo h($station['Station']['lng']); ?></td>
									<td><?php echo h($station['Station']['region']); ?></td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td>Linii</td>
					<td>
						<?php if(!empty($station['StationLine'])): ?>
							<?php foreach($station['StationLine'] as $line): ?>
								<?php echo $this->Html->line($line['Line']['name'], $line['Line']['colour'], $line['Line']['id']); ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="span8">
		<div class="well well-small">
			<figure id="map" style="height:300px;"></figure>
		</div>
	</div>
</div>

<?php 

// Render bug reporting modal
echo $this->element('report', array('id' => $station['Station']['id'], 'type' => 'database', 'title' => 'Problema legata de statia '.$station['Station']['name_direction'].' (#'.$station['Station']['id'].')')); 

?>