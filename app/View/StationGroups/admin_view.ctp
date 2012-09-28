<?php echo $this->Html->script('http://maps.googleapis.com/maps/api/js?key='.Configure::read('Maps.key').'&sensor=false&libraries=geometry', array('inline' => false)); ?>
<?php echo $this->Html->script('admin_station_group', array('inline' => false)); ?>
<?php echo $this->Form->hidden('StationGroup.id', array('value' => $stationGroup['StationGroup']['id'])); ?>

<div class="row-fluid">
	<div class="page-header">
		<h1><?php echo h($stationGroup['StationGroup']['name']); ?></h1>
	</div>
</div>

<div class="row-fluid">
	<ul class="nav nav-tabs">
		<li class="active"><?php echo $this->Html->link('Detalii', array('controller' => 'station_groups', 'action' => 'view', $stationGroup['StationGroup']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Html->link(__('Editează'), array('controller' => 'station_groups', 'action' => 'edit', $stationGroup['StationGroup']['id']), array('escape' => false)); ?></li>
		<li><?php echo $this->Form->postLink(__('Șterge'), array('controller' => 'station_groups', 'action' => 'delete', $stationGroup['StationGroup']['id']), array('escape' => false), __('Eşti sigur că vrei să ştergi grupul de staţii # %s?', $stationGroup['StationGroup']['id'])); ?></li>
	</ul>
</div>

<div class="row-fluid">
	<div class="span4">
		<table class="table table-striped table-bordered">
			<tbody>
				<tr>
					<td>ID</td>
					<td><?php echo $this->Html->link('#' . $stationGroup['StationGroup']['id'], array('action' => 'view', $stationGroup['StationGroup']['id'])); ?></td>
				</tr>
				<tr>
					<td>Nume</td>
					<td><?php echo h($stationGroup['StationGroup']['name']); ?></td>
				</tr>
				<tr>
					<td>Staţii /<br>Linii</td>
					<td>
						<?php if(!empty($stationGroup['Station'])): ?>
							<?php foreach($stationGroup['Station'] as $station): ?>
								<table class="table table-bordered">
									<tbody>
										<tr><td><?php echo $this->Html->link($station['name'], array('controller' => 'stations', 'action' => 'view', $station['id'])); ?> <small class="muted">&rarr; <?php echo $station['direction']; ?></small><br></td></tr>
										<tr><td>
										<?php foreach($station['StationLine'] as $stationLine): ?>
											<?php echo $this->Html->line($stationLine['Line']['name'], $stationLine['Line']['colour'], $stationLine['Line']['id']); ?>
										<?php endforeach; ?>
										</td></tr>
									</tbody>
								</table>
							<?php endforeach; ?>
						<?php endif; ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="span8">
		<div class="well well-small">
			<figure id="map" style="height:200px;"></figure>
		</div>
	</div>
</div>